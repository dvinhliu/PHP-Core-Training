<?php

namespace App\Core;

class Csrf
{
    // Tạo token mới mỗi lần load form
    public static function generate(): string
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    public static function tokenField(): string
    {
        $token = self::generate(); // luôn tạo mới khi render form
        return '<input type="hidden" name="csrf_token" value="'
            . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validate(?string $token): bool
    {
        if (!is_string($token) || $token === '') {
            return false;
        }

        $ok = isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);

        if ($ok) {
            unset($_SESSION['csrf_token']); // token dùng 1 lần
        }

        return $ok;
    }
}
