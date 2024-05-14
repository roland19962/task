<?php

namespace App\Models\CommissionFee;

class WithdrawPrivateRule extends Rule
{
    public string $typeOperation = 'withdraw';
    public string|null $typeUser = 'private';
    public float $mainPercent = 0.3;
    public bool $limitActive = true;
    public float|null $limitPercent = 0;
    public int|null $limitSum = 1000;
    public int|null $limitOperations = 3;
}
