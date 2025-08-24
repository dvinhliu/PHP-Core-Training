<?php

namespace App\Core;

use Dotenv\Dotenv;
use App\Core\Router;
use App\Helpers\IdEncoder;

class App
{
    private static $router;

    public static function run()
    {
        // Khởi động session ngay tại đây
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_lifetime', 0);
            session_start();
        }

        if (file_exists(dirname(__DIR__, 2) . '/.env')) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();
        }

        IdEncoder::init($_ENV['SECRET_KEY'] ?? 'change-me');

        // Load helpers
        require_once dirname(__DIR__) . '/Helpers/form.php';
        require_once dirname(__DIR__) . '/Helpers/encoder.php';

        self::$router = new Router();

        require_once dirname(__DIR__, 2) . '/routes/web.php';

        self::$router->dispatch();
    }

    public static function router()
    {
        return self::$router;
    }
}
