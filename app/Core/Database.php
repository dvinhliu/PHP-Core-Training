<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    protected static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $driver   = Config::get('database.connection', 'mysql');
            $host     = Config::get('database.host', '127.0.0.1');
            $port     = Config::get('database.port', '3306');
            $dbname   = Config::get('database.database', 'test');
            $username = Config::get('database.username', 'root');
            $password = Config::get('database.password', '');

            $dsn = "$driver:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

            try {
                self::$pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("DB connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
