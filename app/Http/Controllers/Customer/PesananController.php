<?php


namespace App\Http\Controllers\Customer;


use App\Helper\CustomController;
use App\Models\Angsuran;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PesananController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->request->ajax()) {
            $data = Penjualan::with([])
                ->where('user_id', '=', auth()->id())
                ->get();
            return $this->basicDataTables($data);
        }
        return view('customer.akun.pesanan.index');
    }

    public function detail($id)
    {
//        if ($this->request->method() === 'POST' && $this->request->ajax()) {
//
//        }
        $data = Penjualan::with(['keranjang.product', 'angsuran'])
            ->findOrFail($id);

        return view('customer.akun.pesanan.detail')->with([
            'data' => $data,
        ]);
    }

    public function pembayaran($id)
    {
        $data = Penjualan::with(['pembayaran_status', 'pembayaran_token'])
            ->findOrFail($id);
        if ($this->request->method() === 'POST' && $this->request->ajax()) {
            return $this->changeStatusPayment();
        }
        $snapToken = $data->pembayaran_token->snap_token;
        return view('customer.akun.pesanan.pembayaran')->with([
            'data' => $data,
            'snapToken' => $snapToken
        ]);
    }

    private function changeStatusPayment()
    {
        try {
            DB::beginTransaction();
            $snapToken = $this->postField('snap_token');
            $payment = Pembayaran::with(['penjualan.keranjang.product'])
                ->where('snap_token', '=', $snapToken)
                ->first();

            if (!$payment) {
                return $this->jsonNotFoundResponse('payment not found');
            }

            /** @var Model $order */
            $order = $payment->penjualan;
            $isCredit = $order->kredit;

            $countInterest = $order->jumlah_angsuran;
            $rest = $order->sisa;

            $interest = round(($rest / $countInterest), 0, PHP_ROUND_HALF_UP);

            $payment->update([
                'status' => 1,
                'snap_token' => null
            ]);

            $data_order = [
                'status' => 1,
                'lunas' => true,
            ];

            if ($isCredit) {
                $data_order['lunas'] = false;
            }
            $order->update($data_order);

            //set interest
            for ($i = 0; $i < $countInterest; $i++) {
                $dataInterest = [
                    'penjualan_id' => $order->id,
                    'tanggal' => null,
                    'index' => ($i + 1),
                    'total' => $interest,
                    'lunas'=> false,
                    'snap_token'=> null
                ];
                Angsuran::create($dataInterest);
            }

            //update stock
            $carts = $order->keranjang;
            foreach ($carts as $cart) {
                $qtyOut = $cart->qty;
                /** @var Model $product */
                $product = $cart->product;
                $currentQty = $product->qty;
                $restQty = $currentQty - $qtyOut;
                $product->update([
                    'qty' => $restQty
                ]);
            }
            DB::commit();
            return $this->jsonSuccessResponse('success');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonErrorResponse($e->getMessage());
        }
    }
}
