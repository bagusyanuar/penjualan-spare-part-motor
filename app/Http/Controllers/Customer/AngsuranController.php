<?php


namespace App\Http\Controllers\Customer;


use App\Helper\CustomController;
use App\Models\Angsuran;
use Carbon\Carbon;

class AngsuranController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    }

    public function getSnapToken($id, $instalmentID)
    {
        try {
            $instalment = Angsuran::with(['penjualan.user.customer'])
                ->where('penjualan_id', '=', $id)
                ->where('id', '=', $instalmentID)
                ->first();

            if (!$instalment) {
                return $this->jsonNotFoundResponse('angsuran tidak ditemukan');
            }

            $customer = $instalment->penjualan->user;
            $payload = [
                'transaction_details' => [
                    'order_id'     => $instalment->id,
                    'gross_amount' => $instalment->total,
                ],
                'customer_details' => [
                    'first_name' => $customer->customer->nama,
                    'email'      => $customer->email,
                ],
                'item_details' => [
                    [
                        'id'            => 1,
                        'price'         => $instalment->total,
                        'quantity'      => 1,
                        'name'          => 'Angsuran ke '.$instalment->index,
                        'brand'         => 'Angsuran',
                        'category'      => 'Angsuran',
                        'merchant_name' => 'Nevermore',
                    ],
                ],
            ];
            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            $instalment->update([
                'snap_token' => $snapToken
            ]);
            return $this->jsonSuccessResponse('success', [
                'snap_token' => $snapToken
            ]);
        }catch (\Exception $e) {
            return $this->jsonErrorResponse($e->getMessage());
        }
    }

    public function pay($id, $instalmentID)
    {
        try {
            $instalment = Angsuran::with(['penjualan.user.customer'])
                ->where('penjualan_id', '=', $id)
                ->where('id', '=', $instalmentID)
                ->first();

            if (!$instalment) {
                return $this->jsonNotFoundResponse('angsuran tidak ditemukan');
            }
            $instalment->update([
                'lunas' => true,
                'tanggal' => Carbon::now()->format('Y-m-d')
            ]);
            return $this->jsonSuccessResponse('success');
        }catch (\Exception $e) {
            return $this->jsonErrorResponse($e->getMessage());
        }
    }
}
