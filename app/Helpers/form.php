<?php

use App\Helpers\Flash;

if (!function_exists('showError')) {
    function showError(?string $field, string $key = 'errors')
    {
        // gọi Flash::showError và return về chuỗi HTML
        ob_start();
        Flash::showError($field, $key);
        return ob_get_clean();
    }
}
