<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\CommissionFeeCalculationController;
use App\Http\Requests\CommissionFeeCalculate;
use Illuminate\Http\JsonResponse;

class CommissionFeeController extends Controller
{
    public CommissionFeeCalculationController $commissionFeeCalculationController;

    public function __construct(CommissionFeeCalculationController $commissionFeeCalculationController)
    {
        $this->commissionFeeCalculationController = $commissionFeeCalculationController;
    }

    public function calculate(CommissionFeeCalculate $request): JsonResponse
    {
        return response()->json($this->commissionFeeCalculationController->calculate($request));
    }
}
