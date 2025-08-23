<?php

namespace App\Helpers;

class Flash
{
    public static function showError(?string $field = null, string $key = 'errors')
    {
        if (!isset($_SESSION[$key])) {
            return;
        }

        if ($field === null) {
            // Hiển thị tất cả lỗi
            foreach ($_SESSION[$key] as $messages) {
                foreach ($messages as $message) {
                    echo '<div class="text-red-600 text-sm">' . htmlspecialchars($message) . '</div>';
                }
            }
            unset($_SESSION[$key]);
        } else {
            // Hiển thị lỗi theo field
            if (!empty($_SESSION[$key][$field])) {
                echo '<div class="text-red-600 text-sm">';
                foreach ($_SESSION[$key][$field] as $message) {
                    echo htmlspecialchars($message);
                }
                echo '</div>';
                unset($_SESSION[$key][$field]);
            }
        }
        unset($_SESSION[$key]); // Xóa hết lỗi sau khi hiển thị
    }
}
