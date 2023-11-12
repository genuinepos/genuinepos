<?php

namespace App\Services\Sales;

use App\Models\Sales\CashRegister;
use App\Models\Sales\CashRegisterTransaction;

class CashRegisterTransactionService
{
    function addCashRegisterTransaction(object $request, object $sale, ?int $voucherDebitDescriptionId = null) : void {

        $addCashRegisterTransaction = new CashRegisterTransaction();
        $addCashRegisterTransaction->cash_register_id = $request->cash_register_id;
        $addCashRegisterTransaction->sale_id = $sale->id;
        $addCashRegisterTransaction->voucher_description_id = $voucherDebitDescriptionId;
        $addCashRegisterTransaction->save();
    }
}
