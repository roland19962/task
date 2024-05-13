<?php

namespace App\Helpers;

class CommissionFeeHelper
{
    public static function baseCurrency(): string
    {
        return 'EUR';
    }

    public static function round($number): float
    {
        return ceil($number * 100) / 100;
    }


}
