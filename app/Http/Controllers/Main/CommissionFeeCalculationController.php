<?php

namespace App\Http\Controllers\Main;

use App\Helpers\CommissionFeeHelper;
use App\Helpers\CsvHelper;
use App\Helpers\RuleHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommissionFeeCalculate;
use App\Models\CommissionFee\DepositRule;
use App\Models\CommissionFee\Rule;
use App\Models\CommissionFee\WithdrawBusinessRule;
use App\Models\CommissionFee\WithdrawPrivateRule;
use App\Models\Operation;
use Carbon\Carbon;
use DateTime;

class CommissionFeeCalculationController extends Controller
{

    public DepositRule $depositRule;
    public WithdrawBusinessRule $withdrawBusinessRule;
    public WithdrawPrivateRule $withdrawPrivateRule;
    public CurrencyExchangeRatesController $currencyExchangeRatesController;

    public function __construct(
        CurrencyExchangeRatesController $currencyExchangeRatesController,
        DepositRule                     $depositRule,
        WithdrawBusinessRule            $withdrawBusinessRule,
        WithdrawPrivateRule             $withdrawPrivateRule
    )
    {
        $this->currencyExchangeRatesController = $currencyExchangeRatesController;
        $this->depositRule = $depositRule;
        $this->withdrawPrivateRule = $withdrawPrivateRule;
        $this->withdrawBusinessRule = $withdrawBusinessRule;
    }

    public function calculate(CommissionFeeCalculate $request): array
    {
        $result = [];

        $rates = $this->currencyExchangeRatesController->getRates();
        if (array_key_exists('message', $rates))
        {
            return $rates;
        }

        $data = CsvHelper::read($request->file('input'), ',');
        $operations = $this->convertArrayToOperations($data, $rates['data']);

        $operationsLimitActive = [];

        foreach ($operations as $operation)
        {
            $rule = RuleHelper::getRule($this->depositRule, $this->withdrawPrivateRule, $this->withdrawBusinessRule,
                $operation->typeOperation, $operation->typeUser);
            if ($rule instanceof Rule)
            {
                if ($rule->limitActive)
                {

                    $operationsLimitActive[$operation->group][] = $operation;
                    $totalBaseAmount = array_sum(array_column($operationsLimitActive[$operation->group], 'baseAmount'));
                    $commission = $this->getCommission($operationsLimitActive[$operation->group], $rule, $totalBaseAmount, $rates['data']);

                } else {
                    $commission = $operation->amount * $rule->mainPercent / 100;
                }
                $result[] = CommissionFeeHelper::round($commission);
            } else {
                $result[] = 'Rule not found';
            }
        }
        return [
            'commissions' => $result,
            'rates' => $rates['data']['date']
        ];
    }

    public function convertArrayToOperations(array $data, array $rates): array
    {
        $operations = [];
        foreach ($data as $item)
        {
            $operations[] = new Operation(
                $this->getGroup($item[0], $item[1], $item[2], $item[3], $operations),
                $item[0], $item[1], $item[2], $item[3], $item[4], $item[5],
                $this->getBaseAmount($item[5], $item[4], $rates));
        }

        return $operations;
    }

    public function getGroup(string $date, int $userId, string $typeUser, string $typeOperation, array $operations): string
    {
        foreach ($operations as $operation)
        {
            if ($operation->userId == $userId
                && $operation->typeUser == $typeUser
                && $operation->typeOperation == $typeOperation
                && $this->checkDate($operation->date, $date)
            ) {
                return $operation->group;
            }
        }
        return Carbon::parse($date)->format('Y-m-w') . '-' . $userId . '-' . $typeUser . '-' . $typeOperation;
    }

    public function checkDate(string $operationDate, string $currentDate): bool
    {
        $interval = new \DateInterval('P1D');
        $startDate = DateTime::createFromFormat('Y-m-d', $operationDate);
        $startDate->modify('Monday this week');
        $endDate = clone $startDate;
        $endDate->modify('Sunday this week');
        $range = new \DatePeriod($startDate, $interval, $endDate, \DatePeriod::INCLUDE_END_DATE);

        foreach ($range as $day)
        {
            if ($day->format('Y-m-d') == $currentDate) return true;
        }

        return false;
    }

    public function getBaseAmount(string $currency, float $amount, array $rates): float
    {
        if ($currency == CommissionFeeHelper::baseCurrency())
        {
            return $amount;
        } else {
            if ($rates['base'] != $currency) {
                $amount /= $rates['rates'][$currency];
            }
            if ($rates['base'] != CommissionFeeHelper::baseCurrency()) {
                $amount /= $rates['rates'][CommissionFeeHelper::baseCurrency()];
            }
            return $amount;
        }
    }

    public function getCommission(array $operations, Rule $rule, float $totalBaseAmount, array $rates): float
    {
        $lastOperation = $operations[count($operations) - 1];
        $commission = 0;
        if (count($operations) <= $rule->limitOperations)
        {
            if ($totalBaseAmount <= $rule->limitSum)
            {

                $commission = $lastOperation->amount * $rule->limitPercent / 100;

            } else {

                $totalBaseAmountWithoutLastOperation = $totalBaseAmount - $lastOperation->baseAmount;

                if ($totalBaseAmountWithoutLastOperation == 0)
                {

                    $commission = $rule->limitSum * $rule->limitPercent / 100;
                    $commission += ($lastOperation->baseAmount - $rule->limitSum) * $rule->mainPercent / 100;
                    $commission *= $rates['rates'][$lastOperation->currency];

                } else if ($totalBaseAmountWithoutLastOperation <= $rule->limitSum) {

                    $limitSum = $rule->limitSum - $totalBaseAmountWithoutLastOperation;
                    $commission = $limitSum * $rule->limitPercent / 100;
                    $commission += ($lastOperation->baseAmount - $limitSum) * $rule->mainPercent / 100;
                    $commission *= $rates['rates'][$lastOperation->currency];

                } else {

                    $commission = $lastOperation->amount * $rule->mainPercent / 100;

                }
            }
        } else {

            $commission = $lastOperation->amount * $rule->mainPercent / 100;

        }

        return $commission;
    }
}
