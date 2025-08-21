<?php

namespace App\Core;

class Controller
{
    /**
     * Load model
     */
    public function model($model)
    {
        $modelClass = "\\App\\Models\\" . $model;
        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            die("Model $modelClass not found");
        }
    }

    /**
     * Render view
     */
    public function view($view, $data = [])
    {
        // đường dẫn tới file view cụ thể (vd: auth/login.php)
        $viewFile = dirname(__DIR__, 2) . '/resources/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View $viewFile not found");
        }

        // tạo biến từ $data
        $data['router'] = App::router(); // truyền biến $router vào view
        extract($data);

        // layout chính
        $layoutFile = dirname(__DIR__, 2) . '/resources/views/layouts/layout.php';

        if (file_exists($layoutFile)) {
            // biến này sẽ được layout.php dùng để nhúng view thật sự
            $contentView = $viewFile;
            require $layoutFile;
        } else {
            // fallback: chỉ load view nếu không có layout
            require $viewFile;
        }
    }


    /**
     * Redirect to URL
     */
    public function redirect($url)
    {
        header("Location: $url");
        exit();
    }
}
