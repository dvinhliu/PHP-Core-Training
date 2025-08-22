<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user_name'])) {
            header("Location: /login");
            exit;
        }

        $this->view('home', ['title' => 'Trang chủ']);
    }
}
