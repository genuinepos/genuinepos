<?php

namespace App\Services\Accounts;

use App\Models\Accounts\AccountingVoucher;

class AccountingVoucherService
{
    public function addAccountingVoucher(string $date, int $voucherType, ?string $remarks, object $codeGenerator, string $voucherPrefix, float $debitTotal, float $creditTotal, float $totalAmount, int $isTransactionDetails = 1, ?int $saleRefId = null, ?int $purchaseRefId = null, ?int $stockAdjustmentRefId = null): ?object
    {
        $voucherNo = $voucherGenerator->generateMonthAndTypeWise(table: 'accounting_vouchers', column: 'voucher_no', typeColName: 'voucher_type', typeValue: $voucherType, prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-');

        $addAccountingVoucher= new AccountingVoucher();
        $addAccountingVoucher->branch_id = auth()->user()->branch_id;
        $addAccountingVoucher->sale_ref_id = $saleRefId;
        $addAccountingVoucher->purchase_ref_id = $purchaseRefId;
        $addAccountingVoucher->stock_adjustment_ref_id = $stockAdjustmentRefId;
        $addAccountingVoucher->addAccountingVoucher_type = $voucherType;
        $addAccountingVoucher->mode = $mode;
        $addAccountingVoucher->voucher_no = $voucherNo;
        $addAccountingVoucher->debit_total = $debitTotal;
        $addAccountingVoucher->credit_total = $creditTotal;
        $addAccountingVoucher->total_amount = $totalAmount;
        $addAccountingVoucher->date = $date;
        $addAccountingVoucher->remarks = $remarks;
        $addAccountingVoucher->date_ts = date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $addAccountingVoucher->created_by_id = auth()->user()->id;
        $addAccountingVoucher->is_transaction_details = $isTransactionDetails;
        $addAccountingVoucher->save();

        return $addAccountingVoucher;
    }

    public function updateAccountingVoucher($id, $date, $remarks, $debitTotal, $creditTotal, $isTransactionDetails)
    {
        $updateAccountingVoucher = AccountingVoucher::with(['descriptions'])->where('id', $id)->first();
        $updateAccountingVoucher->debit_total = $debitTotal;
        $updateAccountingVoucher->credit_total = $creditTotal;
        $updateAccountingVoucher->date = $date;
        $updateAccountingVoucher->remarks = $remarks;
        $previousTime = date(' H:i:s', strtotime($updatePayment->date_ts));
        $updateAccountingVoucher->date_ts = date('Y-m-d H:i:s', strtotime($date . $previousTime));
        $updateAccountingVoucher->is_transaction_details = $isTransactionDetails;
        $updateAccountingVoucher->save();

        return $updateAccountingVoucher;
    }

    public function deleteAccountingVoucher($id)
    {
        $deletePayment = AccountingVoucher::where('id', $id)->first();

        if (!is_null($deletePayment)) {

            $deletePayment->delete();
        }
    }
}
