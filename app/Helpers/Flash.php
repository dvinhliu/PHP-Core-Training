<?php

namespace App\Helpers;

class Flash
{
    public static function showError(string $field, string $key = 'errors')
    {
        if (!empty($_SESSION[$key][$field])) {
            echo '<div class="text-red-600 text-sm">';
            foreach ($_SESSION[$key][$field] as $message) {
                echo htmlspecialchars($message);
            }
            echo '</div>';
            unset($_SESSION[$key][$field]);
        }
    }
}
