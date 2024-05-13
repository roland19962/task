<?php

namespace App\Models\CommissionFee;

class DepositRule extends Rule
{
    public string $typeOperation = 'deposit';
    public string|null $typeUser = null;
    public float $mainPercent = 0.03;
    public bool $limitActive = false;
    public float|null $limitPercent = null;
    public int|null $limitSum = null;
    public int|null $limitOperations = null;
    public string|null $limitTime = null;
}
