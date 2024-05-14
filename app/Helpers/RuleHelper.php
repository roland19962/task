<?php

namespace App\Helpers;

use App\Models\CommissionFee\DepositRule;
use App\Models\CommissionFee\WithdrawPrivateRule;
use App\Models\CommissionFee\WithdrawBusinessRule;
use App\Models\CommissionFee\Rule;

class RuleHelper
{
    public static function getRule(DepositRule $depositRule, WithdrawPrivateRule $withdrawPrivateRule,
                            WithdrawBusinessRule $withdrawBusinessRule,
                            string $typeOperation, string|null $typeUser): Rule|null
    {
        if ($typeOperation == $depositRule->typeOperation)
        {
            return $depositRule;
        }
        if ($typeOperation == $withdrawPrivateRule->typeOperation && $typeUser == $withdrawPrivateRule->typeUser)
        {
            return $withdrawPrivateRule;
        }
        if ($typeOperation == $withdrawBusinessRule->typeOperation && $typeUser == $withdrawBusinessRule->typeUser)
        {
            return $withdrawBusinessRule;
        }
        return null;
    }

}
