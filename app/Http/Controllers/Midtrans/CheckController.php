<?php


namespace App\Http\Controllers\Midtrans;


use App\Helper\CustomController;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckController extends CustomController
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
        if ($this->request->method() === 'POST') {
            return $this->pay();
        }
        return view('customer.check-midtrans');
    }

    private function pay()
    {
        try {
            DB::beginTransaction();
            $subTotal = 1000000;
            $shipping = 500000;
            $total = $subTotal + $shipping;
            $order_data = [
                'user_id' => 1,
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'no_penjualan' => 'PIM-'.date('YmdHis'),
                'sub_total' => $subTotal,
                'ongkir' => $shipping,
                'bunga' => 0,
                'total' => $total,
                'dp' => 0,
                'sisa' => 0,
                'jumlah_angsuran' => 4,
                'status' => 0,
                'lunas' => false,
                'is_kirim' => true,
                'kota' => 'Surakarta',
                'alamat' => 'Tipes',
                'kredit' => true,
            ];
            $order = Penjualan::create($order_data);
            $payload = [
                'transaction_details' => [
                    'order_id'     => $order->id,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => 'Johnny',
                    'email'      => 'bagus.yanuar613@gmail.com',
                ],
                'item_details' => [
                    [
                        'id'            => 1,
                        'price'         => $total,
                        'quantity'      => 1,
                        'name'          => 'Item A',
                        'brand'         => 'ABC',
                        'category'      => 'ABC',
                        'merchant_name' => 'Nevermore',
                    ],
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            $data_payment = [
                'penjualan_id' => $order->id,
                'angsuran_id' => null,
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'bank' => 'BCA',
                'atas_nama' => 'ABC',
                'bukti' => 'bukti',
                'status' => 0,
                'keterangan_status' => 'abc',
                'keterangan_pembayaran' => 'ket',
                'snap_token' => $snapToken
            ];
            Pembayaran::create($data_payment);
            DB::commit();
            return $this->jsonSuccessResponse('success', [
                'snap_token' => $snapToken
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonErrorResponse($e->getMessage());
        }
    }
}
