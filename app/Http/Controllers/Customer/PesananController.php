<?php


namespace App\Http\Controllers\Customer;


use App\Helper\CustomController;
use App\Models\Pembayaran;
use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PesananController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
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
            $payment = Pembayaran::with(['penjualan'])
                ->where('snap_token', '=', $snapToken)
                ->first();

            if (!$payment) {
                return $this->jsonNotFoundResponse('payment not found');
            }

            /** @var Model $order */
            $order = $payment->penjualan;
            $isCredit = $order->kredit;

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
            DB::commit();
            return $this->jsonSuccessResponse('success');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonErrorResponse($e->getMessage());
        }
    }
}
