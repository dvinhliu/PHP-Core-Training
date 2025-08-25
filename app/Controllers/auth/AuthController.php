<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;
use App\Core\Csrf;
use App\Models\User;
use App\Requests\ForgotPasswordRequest;
use App\Requests\ResetPasswordRequest;
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
        if (isset($_SESSION['user_id'])) {
            User::setRememberToken($_SESSION['user_id'], null, null);
        }

        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['role_id']);

        // Xóa cookie remember_token
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }

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

        if (!empty($_POST['remember'])) {
            // Ghi nhớ đăng nhập
            $token = bin2hex(random_bytes(32));
            $expired = date('Y-m-d H:i:s', strtotime('+10 days'));

            User::setRememberToken($user->getId(), password_hash($token, PASSWORD_DEFAULT), $expired);

            setcookie("remember_token", $token, [
                'expires'  => time() + (86400 * 10), // 10 ngày
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }

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

        $token = bin2hex(random_bytes(8));

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

        $email = $_SESSION['verification_email'] ?? null;
        if (!$email) {
            $_SESSION['errors'] = ['general' => 'Phiên đã hết hạn, vui lòng nhập lại email'];
            return $this->redirect('/forgot-password');
        }

        // Kiểm tra mã xác thực
        $isValid = User::checkVerificationCode($email, $_POST['verification_code']);

        if (!$isValid) {
            $_SESSION['errors'] = ['general' => 'Mã xác thực không hợp lệ'];
            return $this->view('auth/verifycode', [
                'title'  => 'Xác thực',
                'old'    => $_POST
            ]);
        }

        // Nếu mã xác thực hợp lệ, chuyển hướng đến trang đổi mật khẩu
        $_SESSION['success'] = 'Xác thực thành công, vui lòng đặt lại mật khẩu';
        $this->redirect('/reset-password');
    }

    public function resetPassword()
    {
        $this->view('auth/resetpassword', ['title' => 'Đặt lại mật khẩu']);
    }

    public function resetPasswordPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reset-password');
        }

        // Validate CSRF
        if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['errors'] = ['general' => 'Token không hợp lệ'];
            return $this->view('auth/resetpassword', [
                'title' => 'Đặt lại mật khẩu',
                'old'   => $_POST
            ]);
        }

        $email = $_SESSION['verification_email'] ?? null;
        if (!$email) {
            $_SESSION['errors'] = ['general' => 'Phiên đã hết hạn, vui lòng nhập lại email'];
            return $this->redirect('/forgot-password');
        }

        // Validate dữ liệu form
        $request = new ResetPasswordRequest($_POST);

        if ($request->fails()) {
            $_SESSION['errors'] = $request->errors();
            return $this->view('auth/resetpassword', [
                'title'  => 'Đặt lại mật khẩu',
                'old'    => $_POST
            ]);
        }

        // Cập nhật mật khẩu mới
        $updated = User::updatePasswordByEmail($email, $_POST['password']);

        if ($updated) {
            // Xóa token và email xác thực khỏi session
            unset($_SESSION['verification_email']);
            $_SESSION['success'] = 'Đặt lại mật khẩu thành công';
            $this->redirect('/login');
        } else {
            $_SESSION['errors'] = ['general' => 'Đặt lại mật khẩu không thành công'];
            return $this->view('auth/resetpassword', [
                'title' => 'Đặt lại mật khẩu',
                'old'   => $_POST
            ]);
        }
    }
}
