<?php

namespace App\Models\CommissionFee;

class WithdrawBusinessRule extends Rule
{
    public string $typeOperation = 'withdraw';
    public string|null $typeUser = 'business';
    public float $mainPercent = 0.5;
    public bool $limitActive = false;
    public float|null $limitPercent = null;
    public int|null $limitSum = null;
    public int|null $limitOperations = null;
}
