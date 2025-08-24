<?php

use App\Helpers\IdEncoder;

if (!function_exists('encode')) {
    function encode(int $id): string
    {
        return IdEncoder::encode($id);
    }
}

if (!function_exists('decode')) {
    function decode(string $encoded): ?int
    {
        return IdEncoder::decode($encoded);
    }
}
