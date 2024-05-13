<?php

use App\Http\Controllers\API\CommissionFeeController;
use Illuminate\Support\Facades\Route;

Route::post('commission-fee/calculate', [ CommissionFeeController::class, "calculate" ]);

