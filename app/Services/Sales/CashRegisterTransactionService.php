<?php

namespace App\Services\Sales;

use App\Models\Sales\CashRegister;
use App\Models\Sales\CashRegisterTransaction;

class CashRegisterTransactionService
{
    function addCashRegisterTransaction(object $request, ?int $saleId, ?int $voucherDebitDescriptionId = null, ?int $saleRefId = null) : void {

        $addCashRegisterTransaction = new CashRegisterTransaction();
        $addCashRegisterTransaction->cash_register_id = $request->cash_register_id;
        $addCashRegisterTransaction->sale_id = $saleId;
        $addCashRegisterTransaction->voucher_description_id = $voucherDebitDescriptionId;
        $addCashRegisterTransaction->sale_ref_id = $saleRefId;
        $addCashRegisterTransaction->save();
    }
}
