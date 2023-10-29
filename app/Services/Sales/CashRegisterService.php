<?php

namespace App\Services\Sales;

use App\Models\Sales\CashRegister;
use Illuminate\Support\Facades\DB;

class CashRegisterService
{
    public function singleCashRegister(?array $with = null): ?object
    {
        $query = CashRegister::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

}
