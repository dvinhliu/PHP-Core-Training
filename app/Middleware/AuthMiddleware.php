<?php

namespace App\Middleware;

use App\Models\User;

class AuthMiddleware
{
    public static function check($permissions = [])
    {
        // Nếu có email verification session thì clear
        if (isset($_SESSION['verification_email'])) {
            unset($_SESSION['verification_email']);
        }

        // Nếu chưa login → check remember_token
        if (!isset($_SESSION['user_id'])) {
            if (!empty($_COOKIE['remember_token'])) {
                $user = User::getUserByRememberToken($_COOKIE['remember_token']);
                if ($user) {
                    $_SESSION['user_id']   = $user->getId();
                    $_SESSION['user_name'] = $user->getUserName();
                    $_SESSION['role_id']   = $user->getRoleId();
                } else {
                    // Clear cookie sai
                    setcookie("remember_token", '', [
                        'expires'  => time() - 3600,
                        'path'     => '/',
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                    $_SESSION['errors'] = ['general' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.'];
                    header("Location: /login");
                    exit;
                }
            } else {
                $_SESSION['errors'] = ['general' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.'];
                header("Location: /login");
                exit;
            }
        }

        // Check user trong DB
        $user = User::getUserById($_SESSION['user_id']);
        if (!$user) {
            header("Location: /logout");
            exit;
        }

        // Lấy danh sách quyền user
        $userPermissions = User::getPermissions($_SESSION['user_id']);

        // Nếu middleware không yêu cầu quyền → chỉ cần login
        if (empty($permissions)) {
            return true;
        }

        // Nếu có yêu cầu quyền → check từng quyền
        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }

        // Không có quyền
        $_SESSION['errors'] = ['general' => 'Bạn không có quyền truy cập vào chức năng này.'];
        header("Location: /404");
        exit;
    }
}
