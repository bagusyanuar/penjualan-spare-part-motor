<?php


namespace App\Http\Controllers\Customer;


use App\Helper\CustomController;
use App\Models\BiayaPengiriman;
use App\Models\Customer;
use App\Models\Keranjang;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\Product;
use App\Models\SettingKredit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KeranjangController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    }

    public function index()
    {
        if ($this->request->method() === 'POST' && $this->request->ajax()) {
            return $this->addToCart();
        }
        $profile = Customer::with([])
            ->where('user_id', '=', auth()->id())
            ->first();

        $address = $profile->alamat;
        /** @var Collection $carts */
        $carts = Keranjang::with(['product.category'])
            ->whereNull('penjualan_id')
            ->where('user_id', '=', auth()->id())
            ->get();
        $subTotal = 0;
        if (count($carts) > 0) {
            $subTotal = $carts->sum('total');
        }
        /** @var Collection $shipments */
        $shipments = BiayaPengiriman::all();
        $totalShipment = 0;
        if (count($shipments) > 0) {
            $totalShipment = $shipments->first()->harga;
        }

        $interests = SettingKredit::with([])
            ->orderBy('jangka_waktu', 'ASC')
            ->get();

        $totalInterest = 0;
        if (count($interests) > 0) {
            $interest = $interests->first()->bunga;
            $tmpTotalInterest = ($interest / 100) * $subTotal;
            $totalInterest = round($tmpTotalInterest, 0, PHP_ROUND_HALF_UP);
        }

        return view('customer.keranjang')->with([
            'shipments' => $shipments,
            'carts' => $carts,
            'subTotal' => $subTotal,
            'totalShipment' => $totalShipment,
            'address' => $address,
            'interests' => $interests,
            'totalInterest' => $totalInterest,
        ]);
    }

    private function addToCart()
    {
        try {
            $userID = auth()->id();
            $productID = $this->postField('id');
            $qty = $this->postField('qty');

            $product = Product::with([])
                ->where('id', '=', $productID)
                ->firstOrFail();
            if (!$product) {
                return $this->jsonErrorResponse('product tidak ditemukan');
            }

            $productPrice = $product->harga;
            $total = (int)$qty * $productPrice;
            $data_request = [
                'user_id' => $userID,
                'penjualan_id' => null,
                'product_id' => $productID,
                'qty' => $qty,
                'harga' => $productPrice,
                'total' => $total
            ];
            Keranjang::create($data_request);
            return $this->jsonSuccessResponse('success', 'Berhasil menambahkan keranjang...');
        } catch (\Exception $e) {
            return $this->jsonErrorResponse();
        }
    }

    public function checkout()
    {
        try {
            DB::beginTransaction();
            $userID = auth()->id();
            $shippingMethod = $this->postField('shipping_method');
            $destination = $this->postField('destination');
            $address = $this->postField('address');
            $dp = $this->postField('dp');
            $buyingMethod = $this->postField('buying_method');
            $interest = $this->postField('interest');
            $countInterest = $this->postField('count_interest');

            $customer = Customer::with(['user'])
                ->where('user_id', '=', $userID)
                ->first();

            if (!$customer) {
                return $this->jsonErrorResponse('customer tidak ditemukan');
            }

            $shipment = BiayaPengiriman::with([])
                ->where('id', '=', $destination)
                ->first();
            if (!$shipment) {
                return $this->jsonErrorResponse('kota tujuan tidak ditemukan');
            }

            $transactionRef = 'HP-' . date('YmdHis');
            /** @var Collection $carts */
            $carts = Keranjang::with(['product.category'])
                ->whereNull('penjualan_id')
                ->where('user_id', '=', auth()->id())
                ->get();

            if (count($carts) <= 0) {
                return $this->jsonErrorResponse('belum ada data belanja...');
            }
            $subTotal = $carts->sum('total');
            $shippingPayment = $shipment->harga;
            if ($shippingMethod === 'pickup') {
                $shippingPayment = 0;
            }
            $total = $subTotal + $shippingPayment + $interest;
            $data_request = [
                'user_id' => $userID,
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'no_penjualan' => $transactionRef,
                'sub_total' => $subTotal,
                'ongkir' => $shippingPayment,
                'bunga' => $interest,
                'total' => $total,
                'dp' => $dp,
                'sisa' => $total - $dp,
                'jumlah_angsuran' => $countInterest,
                'status' => 0,
                'lunas' => false,
                'is_kirim' => true,
                'kota' => $shipment->kota,
                'alamat' => $address,
                'kredit' => true,
            ];

            if ($shippingMethod === 'pickup') {
                $data_request['is_kirim'] = false;
                $data_request['kota'] = '-';
                $data_request['alamat'] = '-';

            }

            if ($buyingMethod === 'cash') {
                $data_request['bunga'] = 0;
                $data_request['dp'] = 0;
                $data_request['kredit'] = false;
                $data_request['sisa'] = 0;
                $data_request['jumlah_angsuran'] = 0;
            }

            $transaction = Penjualan::create($data_request);
            /** @var Model $cart */
            foreach ($carts as $cart) {
                $cart->update(['penjualan_id' => $transaction->id]);
            }
            $transID = $transaction->id;

            $totalPay = $buyingMethod === 'cash' ? $total : $dp;
            $snapToken = $this->generateSnapToken($transID, $totalPay, $customer);
            if ($snapToken === null) {
                DB::rollBack();
                return $this->jsonErrorResponse('error generate snap token');
            }

            $data_payment = [
                'penjualan_id' => $transID,
                'angsuran_id' => null,
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'bank' => '-',
                'atas_nama' => '-',
                'bukti' => '-',
                'status' => 0,
                'keterangan_status' => '-',
                'keterangan_pembayaran' => '-',
                'snap_token' => $snapToken
            ];
            Pembayaran::create($data_payment);
            DB::commit();
            return $this->jsonSuccessResponse('success', $transID);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonErrorResponse($e->getMessage());
        }
    }

    private function generateSnapToken($transactionID, $total, $customer)
    {
        $payload = [
            'transaction_details' => [
                'order_id'     => $transactionID,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $customer->nama,
                'email'      => $customer->user->email,
            ],
            'item_details' => [
                [
                    'id'            => 1,
                    'price'         => $total,
                    'quantity'      => 1,
                    'name'          => 'Pembayaran Transaksi',
                    'brand'         => 'ABC',
                    'category'      => 'ABC',
                    'merchant_name' => 'Nevermore',
                ],
            ],
        ];

        $result = null;
        try {
            $result = \Midtrans\Snap::getSnapToken($payload);
        } catch (\Exception $e) {
            $result = null;
        }
        return $result;
    }
}
