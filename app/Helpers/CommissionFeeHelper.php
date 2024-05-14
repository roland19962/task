<?php

namespace App\Helpers;

class CommissionFeeHelper
{
    public static function baseCurrency(): string
    {
        return 'EUR';
    }

    public static function round(float $value): float
    {
        return ceil($value * 100) / 100;
    }


}
