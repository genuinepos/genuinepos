<?php

namespace App\Services\Sales;

use App\Models\Sales\CashRegister;

class CashRegisterService
{
    public function addCashRegister(object $request)
    {
        $generalSettings = config('generalSettings');
        $dateFormat = $generalSettings['business__date_format'];
        $timeFormat = $generalSettings['business__time_format'];

        $__timeFormat = '';
        if ($timeFormat == '12') {

            $__timeFormat = ' h:i:s';
        } elseif ($timeFormat == '24') {

            $__timeFormat = ' H:i:s';
        }

        $addCashRegister = new CashRegister();
        $addCashRegister->user_id = auth()->user()->id;
        $addCashRegister->date = date($dateFormat.$__timeFormat);
        $addCashRegister->cash_counter_id = $request->cash_counter_id;
        $addCashRegister->cash_account_id = $request->cash_account_id;
        $addCashRegister->sale_account_id = $request->sale_account_id;
        $addCashRegister->opening_cash = $request->opening_cash;
        $addCashRegister->branch_id = auth()->user()->branch_id;
        $addCashRegister->save();
    }

    public function singleCashRegister(array $with = null): ?object
    {
        $query = CashRegister::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
