<?php

namespace App\Models\CommissionFee;

abstract class Rule
{
    public string $typeOperation;
    public string|null $typeUser;
    public float $mainPercent;
    public bool $limitActive;
    public float|null $limitPercent;
    public int|null $limitSum;
    public int|null $limitOperations;
    public string|null $limitTime;
}
