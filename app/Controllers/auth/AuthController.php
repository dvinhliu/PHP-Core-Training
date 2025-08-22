<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;
use App\Core\Csrf;
use App\Models\User;

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

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function loginPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Nếu không phải POST thì quay lại login
            header("Location: /login");
            exit;
        }

        // Validate CSRF
        if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['errors'] = ['general' => 'Token không hợp lệ'];
            return $this->view('auth/login', [
                'title' => 'Trang đăng nhập',
                'old'   => $_POST
            ]);
        }

        // Validate dữ liệu form
        $request = new LoginRequest($_POST);

        if ($request->fails()) {
            $_SESSION['errors'] = $request->errors();
            return $this->view('auth/login', [
                'title'  => 'Trang đăng nhập',
                'old'    => $_POST
            ]);
        }

        // Lấy dữ liệu
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Tìm user
        $user = User::getUserByCredentials($username, $password);

        if (!$user) {
            $_SESSION['errors'] = ['general' => 'Username hoặc mật khẩu không chính xác'];
            return $this->view('auth/login', [
                'title'  => 'Trang đăng nhập',
                'old'    => $_POST
            ]);
        }

        // Đăng nhập thành công
        $_SESSION['user_id']  = $user->getId();
        $_SESSION['user_name'] = $user->getUserName();
        $_SESSION['role']     = $user->getRoleId() ?? 'member';

        // Redirect home
        header("Location: /");
        exit;
    }

    public function registerPost()
    {
        // Gọi request + validate
        $request = new RegisterRequest($_POST);

        if ($request->fails()) {
            return $this->view('auth/register', [
                'title' => 'Trang đăng ký',
                'errors' => $request->errors(),
                'old'   => $_POST
            ]);
        }

        // Tạo user mới
        $user = User::createUser([
            'name'     => $_POST['username'],
            'email'    => $_POST['email'],
            'password' => $_POST['password']
        ]);

        if ($user) {
            // Đăng ký thành công thì redirect login
            header("Location: /login");
            exit;
        } else {
            return $this->view('auth/register', [
                'title' => 'Trang đăng ký',
                'errors' => ['general' => ['Đăng ký thất bại, vui lòng thử lại']],
                'old' => $_POST
            ]);
        }
    }
}
