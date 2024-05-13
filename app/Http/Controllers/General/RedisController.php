<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public static function setItemToRedis(string $key, string $value, string $timeKey, int $timeValue): void
    {
        Redis::set($key, $value, $timeKey, $timeValue);
    }

    public static function getItemFromRedis(string $key): string|null
    {
        return Redis::get($key);
    }
}
