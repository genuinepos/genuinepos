<?php

namespace App\Services\Sales;

use App\Models\Sales\CashRegisterTransaction;

class CashRegisterTransactionService
{
    function addCashRegisterTransaction(object $request, ?int $saleId, ?int $voucherDebitDescriptionId = null, ?int $saleRefId = null): void
    {
        $cashRegisterTransaction = $this->singleCashRegisterTransaction()->where('sale_id', $saleId)->first();

        $addCashRegisterTransaction = new CashRegisterTransaction();
        if ($cashRegisterTransaction) {

            if ($voucherDebitDescriptionId) {

                $addCashRegisterTransaction->cash_register_id = $request->cash_register_id;
                $addCashRegisterTransaction->voucher_description_id = $voucherDebitDescriptionId;
                $addCashRegisterTransaction->sale_ref_id = $saleRefId;
                $addCashRegisterTransaction->save();
            }
        }else {

            $addCashRegisterTransaction->cash_register_id = $request->cash_register_id;
            $addCashRegisterTransaction->sale_id = $saleId;
            $addCashRegisterTransaction->voucher_description_id = $voucherDebitDescriptionId;
            $addCashRegisterTransaction->sale_ref_id = $saleRefId;
            $addCashRegisterTransaction->save();
        }
    }

    public function singleCashRegisterTransaction(array $with = null): ?object
    {
        $query = CashRegisterTransaction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
