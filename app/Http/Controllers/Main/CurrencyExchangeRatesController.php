<?php

namespace App\Http\Controllers\Main;

use App\Helpers\RedisHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\General\RedisController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CurrencyExchangeRatesController extends Controller
{
    public function getRatesFromApi(): array
    {
        $result = [];

        $response = Http::get(env('CURRENCY_EXCHANGE_RATES_URL'));

        if ($response->successful())
        {
            $validator = Validator::make($response->json(), [
                'base' => [ 'required', 'string' ],
                'date' => [ 'required', 'string' ],
                'rates' => [ 'required', 'array' ]
            ]);

            if ($validator->fails()) {
                $result['message'] = 'Invalid api response structure from ' . env('CURRENCY_EXCHANGE_RATES_URL');
            }

            RedisController::setItemToRedis(
                RedisHelper::redisRatesKey(), $response->body(),
                RedisHelper::redisTimeSecondsKey(), 24 * 60 * 60 * 60
            );

            $result['data'] = $response->json();

        } else {
            $result['message'] = 'Response is not successful from ' . env('CURRENCY_EXCHANGE_RATES_URL');
        }

        return $result;
    }

    public function getRates(): array|null
    {
        $data = RedisController::getItemFromRedis(RedisHelper::redisRatesKey());

        if (!empty($data))
        {
            $data = json_decode($data, true);
            if ($data['date'] == Carbon::now()->format('Y-m-d'))
            {
                return [
                    'data' => $data
                ];
            }
        }

        return $this->getRatesFromApi();
    }
}
