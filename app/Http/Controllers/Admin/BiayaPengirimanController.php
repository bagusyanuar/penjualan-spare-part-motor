<?php


namespace App\Http\Controllers\Admin;


use App\Helper\CustomController;
use App\Models\BiayaPengiriman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BiayaPengirimanController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->request->ajax()) {
            $data = BiayaPengiriman::with([])
                ->orderBy('created_at', 'DESC')
                ->get();
            return $this->basicDataTables($data);
        }
        return view('admin.biaya-pengiriman.index');
    }

    public function add()
    {
        if ($this->request->method() === 'POST') {
            return $this->store();
        }
        return view('admin.biaya-pengiriman.add');
    }

    public function edit($id)
    {
        $data = BiayaPengiriman::with([])
            ->findOrFail($id);
        if ($this->request->method() === 'POST') {
            return $this->patch($data);
        }
        return view('admin.biaya-pengiriman.edit')->with(['data' => $data]);
    }

    public function delete($id)
    {
        try {
            BiayaPengiriman::destroy($id);
            return $this->jsonSuccessResponse('Berhasil menghapus data...');
        } catch (\Exception $e) {
            return $this->jsonErrorResponse();
        }
    }

    private $rule = [
        'name' => 'required',
        'price' => 'required',
    ];

    private $message = [
        'name.required' => 'kolom nama wajib diisi',
        'price.required' => 'kolom harga wajib diisi',
    ];

    private function store()
    {
        try {
            $validator = Validator::make($this->request->all(), $this->rule, $this->message);
            if ($validator->fails()) {
                return redirect()->back()->with('failed', 'Harap mengisi kolom dengan benar...')->withErrors($validator)->withInput();
            }
            $data_request = $this->getDataRequest();
            BiayaPengiriman::create($data_request);
            return redirect()->back()->with('success', 'Berhasil menyimpan data biaya pengiriman...');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('failed', 'terjadi kesalahan server...');
        }
    }

    /**
     * @param $data Model
     * @return \Illuminate\Http\RedirectResponse
     */
    private function patch($data)
    {
        try {
            $validator = Validator::make($this->request->all(), $this->rule, $this->message);
            if ($validator->fails()) {
                return redirect()->back()->with('failed', 'Harap mengisi kolom dengan benar...')->withErrors($validator)->withInput();
            }
            $data_request = $this->getDataRequest();
            $data->update($data_request);
            return redirect()->back()->with('success', 'Berhasil merubah data biaya pengiriman...');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('failed', 'terjadi kesalahan server...');
        }
    }

    private function getDataRequest()
    {
        return [
            'kota' => $this->postField('name'),
            'harga' => $this->postField('price'),
        ];
    }
}
