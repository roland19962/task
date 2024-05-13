<?php

namespace App\Http\Controllers\Main;
use App\Helpers\RedisHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\General\RedisController;
use Illuminate\Support\Facades\Http;

class CurrencyExchangeRatesController extends Controller
{
    public function getRates(): array|null
    {
        $data = RedisController::getItemFromRedis(RedisHelper::redisRatesKey());

        if (empty($data))
        {
            $response = Http::get(env('CURRENCY_EXCHANGE_RATES_URL'));
            if ($response->successful())
            {
                RedisController::setItemToRedis(
                    RedisHelper::redisRatesKey(), $response->body(),
                    RedisHelper::redisTimeSecondsKey(), 24 * 60 * 60 * 60
                );
                $data = $response->body();
            }
        }

        return json_decode($data, true);
    }
}
