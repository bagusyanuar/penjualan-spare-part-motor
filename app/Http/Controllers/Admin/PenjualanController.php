<?php


namespace App\Http\Controllers\Admin;


use App\Helper\CustomController;
use App\Models\Keranjang;
use App\Models\Penjualan;

class PenjualanController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->request->ajax()) {
            $status = $this->field('status');
            $data = [];
            if ($status === '1') {
                $data = Penjualan::with([])
                    ->where('status', '=', 1)
                    ->orderBy('updated_at', 'ASC')
                    ->get();
            }

            if ($status === '2') {
                $rangeStatus = [2, 3];
                $data = Penjualan::with([])
                    ->whereIn('status', $rangeStatus)
                    ->orderBy('updated_at', 'ASC')
                    ->get();
            }

            if ($status === '3') {
                $data = Penjualan::with([])
                    ->where('status', '=', 4)
                    ->orderBy('updated_at', 'ASC')
                    ->get();
            }

            return $this->basicDataTables($data);
        }
        return view('admin.penjualan.index');
    }

    public function detail_process($id)
    {
        if ($this->request->ajax()) {
            if ($this->request->method() === 'POST') {
                return $this->change_process_status($id);
            }
            $data = Keranjang::with(['product'])
                ->where('penjualan_id', '=', $id)
                ->get();
            return $this->basicDataTables($data);
        }
        $data = Penjualan::with(['pembayaran_status', 'keranjang'])
            ->findOrFail($id);
        return view('admin.penjualan.detail.baru')->with([
            'data' => $data
        ]);
    }

    private function change_process_status($id)
    {
        try {
            $order = Penjualan::with(['pembayaran_status'])
                ->where('id', '=', $id)
                ->first();
            if (!$order) {
                return $this->jsonNotFoundResponse('data tidak ditemukan...');
            }

            $isKirim = $order->is_kirim;

            $data_request_order = [
                'status' => 2,
            ];

            if ($isKirim) {
                $data_request_order['status'] = 3;
            }

            $order->update($data_request_order);
            return $this->jsonSuccessResponse('success', 'Berhasil merubah data product...');
        } catch (\Exception $e) {
            return $this->jsonErrorResponse($e->getMessage());
        }
    }

    public function detail_packing($id)
    {
        if ($this->request->ajax()) {
            if ($this->request->method() === 'POST') {
                return $this->submit_to_finish($id);
            }
            $data = Keranjang::with(['product'])
                ->where('penjualan_id', '=', $id)
                ->get();
            return $this->basicDataTables($data);
        }
        $data = Penjualan::with(['pembayaran_status', 'keranjang'])
            ->findOrFail($id);
        return view('admin.penjualan.detail.selesai-packing')->with([
            'data' => $data
        ]);
    }


    private function submit_to_finish($id)
    {
        try {
            $order = Penjualan::with(['pembayaran_status'])
                ->where('id', '=', $id)
                ->first();
            if (!$order) {
                return $this->jsonNotFoundResponse('data tidak ditemukan...');
            }
            $data_request_order = [
                'status' => 4,
            ];
            $order->update($data_request_order);
            return $this->jsonSuccessResponse('success', 'Berhasil merubah data product...');
        } catch (\Exception $e) {
            return $this->jsonErrorResponse($e->getMessage());
        }
    }

    public function detail_finish($id)
    {
        if ($this->request->ajax()) {
            $data = Keranjang::with(['product'])
                ->where('penjualan_id', '=', $id)
                ->get();
            return $this->basicDataTables($data);
        }
        $data = Penjualan::with(['pembayaran_status', 'keranjang'])
            ->findOrFail($id);
        return view('admin.penjualan.detail.selesai')->with([
            'data' => $data
        ]);
    }
}
