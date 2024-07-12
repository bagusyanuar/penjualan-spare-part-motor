<?php


namespace App\Http\Controllers\Midtrans;


use App\Helper\CustomController;

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
        return view('admin.midtrans.check');
    }

    private function pay()
    {

    }
}
