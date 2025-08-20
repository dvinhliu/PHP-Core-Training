<?php

namespace App\Core;

class Config
{
    protected static array $configs = [];

    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $file = $keys[0];

        if (!isset(self::$configs[$file])) {
            $path = dirname(__DIR__, 2) . "/config/{$file}.php";
            if (file_exists($path)) {
                self::$configs[$file] = require $path;
            } else {
                return $default;
            }
        }

        $value = self::$configs[$file];
        foreach (array_slice($keys, 1) as $k) {
            if (is_array($value) && isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }

        return $value;
    }
}
