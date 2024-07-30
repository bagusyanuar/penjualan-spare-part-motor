<?php


namespace App\Http\Controllers\Admin;


use App\Helper\CustomController;
use App\Models\Penjualan;

class AngsuranController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->request->ajax()) {
            $data = Penjualan::with([])
                ->where('lunas', '=', 0)
                ->orderBy('created_at', 'DESC')
                ->get();
            return $this->basicDataTables($data);
        }
        return view('admin.angsuran.index');
    }

    public function detail($id)
    {
        $data = Penjualan::with(['pembayaran_status', 'keranjang', 'angsuran'])
            ->findOrFail($id);
        return view('admin.angsuran.detail')->with([
            'data' => $data
        ]);
    }
}
