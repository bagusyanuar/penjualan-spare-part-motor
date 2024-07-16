<?php


namespace App\Http\Controllers\Admin;


use App\Helper\CustomController;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->request->ajax()) {
            $data = User::with([])
                ->where('role', '!=', 'customer')
                ->where('role', '!=', 'pimpinan')
                ->get();
            return $this->basicDataTables($data);
        }
        return view('admin.pengguna.index');
    }

    public function add()
    {
        if ($this->request->method() === 'POST') {
            return $this->store();
        }
        return view('admin.pengguna.add');
    }

    public function edit($id)
    {
        $data = User::with([])
            ->findOrFail($id);
        if ($this->request->method() === 'POST') {
            return $this->patch($data);
        }
        return view('admin.pengguna.edit')->with(['data' => $data]);
    }

    public function delete($id)
    {
        try {
            User::destroy($id);
            return $this->jsonSuccessResponse('Berhasil menghapus data...');
        } catch (\Exception $e) {
            return $this->jsonErrorResponse();
        }
    }

    private $rule = [
        'password' => 'required',
        'username' => 'required',
        'email' => 'required',
    ];

    private $message = [
        'username.required' => 'kolom username wajib diisi',
        'password.required' => 'kolom password wajib diisi',
        'email.required' => 'kolom email wajib diisi',
    ];

    private function store()
    {
        try {
            $validator = Validator::make($this->request->all(), $this->rule, $this->message);
            if ($validator->fails()) {
                return redirect()->back()->with('failed', 'Harap mengisi kolom dengan benar...')->withErrors($validator)->withInput();
            }
            $data_user = [
                'email' => $this->postField('email'),
                'username' => $this->postField('username'),
                'password' => Hash::make($this->postField('password')),
                'role' => 'admin',
            ];

            User::create($data_user);
            return redirect()->back()->with('success', 'Berhasil menyimpan data admin...');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('failed', 'terjadi kesalahan server...');
        }
    }

    /**
     * @param Model $data
     * @return \Illuminate\Http\RedirectResponse
     */
    private function patch($data)
    {
        try {
            $newRule = Arr::except($this->rule, ['password']);
            $validator = Validator::make($this->request->all(), $newRule, $this->message);
            if ($validator->fails()) {
                return redirect()->back()->with('failed', 'Harap mengisi kolom dengan benar...')->withErrors($validator)->withInput();
            }
            $data_user = [
                'email' => $this->postField('email'),
                'username' => $this->postField('username'),
            ];

            if ($this->postField('password') !== '') {
                $data_user['password'] = Hash::make($this->postField('password'));
            }


            $data->update($data_user);
            return redirect()->back()->with('success', 'Berhasil merubah data pengguna...');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('failed', 'terjadi kesalahan server...');
        }
    }
}
