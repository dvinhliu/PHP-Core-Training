<?php

namespace App\Helpers;

use App\Core\Config;

class IdEncoder
{
    private static ?string $secret = null;

    public static function init(string $key): void
    {
        self::$secret = $key;
    }

    public static function encode(int $id): string
    {
        $hash = hash_hmac('sha256', $id, self::$secret);
        return base64_encode($id . '::' . $hash);
    }

    public static function decode(string $encoded): ?int
    {
        $decoded = base64_decode($encoded, true);
        if ($decoded === false) {
            return null;
        }

        $parts = explode('::', $decoded);
        if (count($parts) !== 2) {
            return null;
        }

        [$id, $hash] = $parts;

        if (hash_hmac('sha256', $id, self::$secret) === $hash) {
            return (int)$id;
        }

        return null; // fake id => null
    }
}
