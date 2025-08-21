<?php

namespace App\Core;

use Dotenv\Dotenv;
use App\Core\Router;

class App
{
    private static $router;

    public static function run()
    {
        if (file_exists(dirname(__DIR__, 2) . '/.env')) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();
        }

        self::$router = new Router();

        require dirname(__DIR__, 2) . '/routes/web.php';

        self::$router->dispatch();
    }

    public static function router()
    {
        return self::$router;
    }
}
