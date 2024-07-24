<?php


namespace App\Http\Controllers\Customer;


use App\Helper\CustomController;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AkunController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $user = User::with(['customer'])
            ->where('id', '=', auth()->id())
            ->where('role', '=', 'customer')
            ->firstOrFail();
        if ($this->request->method() === 'POST') {
            return $this->patch($user);
        }
        return view('customer.akun.index')->with(['user' => $user]);
    }

    private $rule = [
        'email' => 'required|email',
        'username' => 'required',
    ];

    private $message = [
        'email.required' => 'kolom email wajib di isi',
        'email.email' => 'alamat email tidak valid',
        'username.required' => 'kolom usernam wajib di isi',
    ];

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
            DB::beginTransaction();
            $data_account = [
                'email' => $this->postField('email'),
                'username' => $this->postField('username'),
            ];
            $data->update($data_account);
            $data_customer = [
                'nama' => $this->postField('name'),
                'no_hp' => $this->postField('phone'),
                'alamat' => $this->postField('address'),
            ];
            $data->customer->update($data_customer);
            DB::commit();
            return redirect()->back()->with('success', 'Berhasil memperbaharui data akun...');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('failed', 'terjadi kesalahan server...');
        }
    }
}
