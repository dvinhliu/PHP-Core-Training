<?php

namespace App\Core;

class Csrf
{
    // Tạo token mới và lưu vào session
    public static function generate(): string
    {
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_tokens'][$token] = time(); // lưu token + timestamp

        // Dọn rác token cũ > 10 phút
        foreach ($_SESSION['csrf_tokens'] as $tok => $createdAt) {
            if ($createdAt < time() - 600) {
                unset($_SESSION['csrf_tokens'][$tok]);
            }
        }

        return $token;
    }

    // Sinh input hidden cho form
    public static function tokenField(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="'
            . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    // Kiểm tra token khi submit
    public static function validate(?string $token): bool
    {
        if (!is_string($token) || $token === '') {
            return false;
        }

        if (isset($_SESSION['csrf_tokens'][$token])) {
            unset($_SESSION['csrf_tokens'][$token]); // chỉ dùng 1 lần
            return true;
        }

        return false;
    }
}
