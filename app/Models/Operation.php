<?php

namespace App\Models;

class Operation
{
    public string $date;
    public string $group;
    public int $userId;
    public string $typeUser;
    public string $typeOperation;
    public float $amount;
    public string $currency;
    public float $baseAmount;

    public function __construct(string $group, string $date, int $userId, string $typeUser,
                                string $typeOperation, float $amount, string $currency, float $baseAmount)
    {
        $this->group         = $group;
        $this->date          = $date;
        $this->userId        = $userId;
        $this->typeOperation = $typeOperation;
        $this->typeUser      = $typeUser;
        $this->currency      = $currency;
        $this->amount        = $amount;
        $this->baseAmount    = $baseAmount;
    }
}
