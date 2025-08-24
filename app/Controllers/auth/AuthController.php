<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;
use App\Core\Csrf;
use App\Models\User;
use App\Requests\ForgotPasswordRequest;
use App\Services\MailService;

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
        // Xóa thông tin user
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['role_id']);
        $_SESSION['success'] = 'Đăng xuất thành công';
        $this->redirect('/');
    }

    public function loginPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Nếu không phải POST thì quay lại login
            $this->redirect('/login');
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
        $username = $_POST['user_name'];
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
        $_SESSION['role_id']     = $user->getRoleId() ?? 'member';

        $_SESSION['success'] = 'Đăng nhập thành công';
        // Redirect home
        $this->redirect('/');
    }

    public function registerPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Nếu không phải POST thì quay lại register
            $this->redirect('/register');
        }

        if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['errors'] = ['general' => 'Token không hợp lệ'];
            return $this->view('auth/register', [
                'title' => 'Trang đăng ký',
                'old'   => $_POST
            ]);
        }

        // Validate dữ liệu form
        $request = new RegisterRequest($_POST);

        if ($request->fails()) {
            $_SESSION['errors'] = $request->errors();
            return $this->view('auth/register', [
                'title'  => 'Trang đăng ký',
                'old'    => $_POST
            ]);
        }

        // Tạo user mới
        $user = User::createUser([
            'user_name'     => $_POST['user_name'],
            'email'    => $_POST['email'],
            'password' => $_POST['password'],
            'role_id'  => $_POST['role_id']
        ]);

        if ($user) {
            // Đăng ký thành công thì redirect login
            $_SESSION['success'] = 'Đăng ký thành công';
            $this->redirect('/');
        } else {
            $_SESSION['errors'] = ['general' => 'Đăng ký không thành công'];
            return $this->view('auth/register', [
                'title' => 'Trang đăng ký',
                'old' => $_POST
            ]);
        }
    }
    public function forgotPassword()
    {
        $this->view('auth/forgotpassword', ['title' => 'Quên mật khẩu']);
    }

    public function forgotPasswordPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Nếu không phải POST thì quay lại forgot password
            $this->redirect('/forgot-password');
        }

        // Validate CSRF
        if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['errors'] = ['general' => 'Token không hợp lệ'];
            return $this->view('auth/forgotpassword', [
                'title' => 'Quên mật khẩu',
                'old'   => $_POST
            ]);
        }

        // Validate dữ liệu form
        $request = new ForgotPasswordRequest($_POST);

        if ($request->fails()) {
            $_SESSION['errors'] = $request->errors();
            return $this->view('auth/forgotpassword', [
                'title'  => 'Quên mật khẩu',
                'old'    => $_POST
            ]);
        }

        // Lấy dữ liệu
        $email = $_POST['email'];

        // Tìm user theo email
        $userExists = User::checkEmailExists($email);

        if (!$userExists) {
            $_SESSION['errors'] = ['general' => 'Email không tồn tại'];
            return $this->view('auth/forgotpassword', [
                'title'  => 'Quên mật khẩu',
                'old'    => $_POST
            ]);
        }

        $token = bin2hex(random_bytes(32));

        $saved = User::setResetToken($email, $token);

        if (!$saved) {
            $_SESSION['errors'] = ['general' => 'Không thể tạo liên kết đặt lại mật khẩu'];
            return $this->view('auth/forgotpassword', [
                'title'  => 'Quên mật khẩu',
                'old'    => $_POST
            ]);
        }

        // Gửi email chứa liên kết đặt lại mật khẩu
        $subject = "Đặt lại mật khẩu";
        $message = "Mã xác thực của bạn là: $token thời gian hiệu lực trong 5 phút";
        MailService::sendMail($email, $subject, $message);

        $_SESSION['verification_email'] = $email;
        $_SESSION['success'] = 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn';
        $this->redirect('/verify');
    }

    public function verify()
    {
        $this->view('auth/verifycode', ['title' => 'Xác thực']);
    }

    public function verifyPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/verify');
        }

        // Validate CSRF
        if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['errors'] = ['general' => 'Token không hợp lệ'];
            return $this->view('auth/verifycode', [
                'title' => 'Xác thực',
                'old'   => $_POST
            ]);
        }

        // Validate dữ liệu form
        $request = new VerifyRequest($_POST);

        if ($request->fails()) {
            $_SESSION['errors'] = $request->errors();
            return $this->view('auth/verifycode', [
                'title'  => 'Xác thực',
                'old'    => $_POST
            ]);
        }

        // Kiểm tra mã xác thực
        $isValid = User::checkVerificationCode($_POST['verification_code']);

        if (!$isValid) {
            $_SESSION['errors'] = ['general' => 'Mã xác thực không hợp lệ'];
            return $this->view('auth/verifycode', [
                'title'  => 'Xác thực',
                'old'    => $_POST
            ]);
        }

        // Nếu mã xác thực hợp lệ, chuyển hướng đến trang đổi mật khẩu
        $this->redirect('/reset-password');
    }
}
