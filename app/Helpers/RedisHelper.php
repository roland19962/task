<?php

namespace App\Helpers;

class RedisHelper
{
    public static function redisTimeSecondsKey(): string
    {
        return 'EX';
    }

    public static function redisRatesKey(): string
    {
        return 'rates';
    }

}
