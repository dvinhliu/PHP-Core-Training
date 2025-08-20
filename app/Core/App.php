<?php

namespace App\Core;

use Dotenv\Dotenv;
use App\Core\Router;

class App
{
    public static function run()
    {
        if (file_exists(dirname(__DIR__, 2) . '/.env')) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();
        }

        $router = new Router();
        require dirname(__DIR__, 2) . '/routes/web.php';

        $router->dispatch();
    }
}
