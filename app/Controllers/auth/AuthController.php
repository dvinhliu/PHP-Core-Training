<?php

namespace App\Controllers\Auth;

use App\Core\Controller;

class AuthController extends Controller
{
    public function login()
    {
        $this->view('auth/login', ['title' => 'Trang đăng nhập']);
    }

    public function register()
    {
        $this->view('auth/register', ['title' => 'Trang đăng ký']);
    }
}
