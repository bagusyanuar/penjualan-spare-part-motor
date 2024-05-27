<?php


namespace App\Http\Controllers\Admin;


use App\Helper\CustomController;

class LoginController extends CustomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        return view('admin.login');
    }
}
