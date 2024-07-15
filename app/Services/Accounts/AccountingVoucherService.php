<?php

namespace App\Services\Accounts;

use App\Models\Accounts\AccountingVoucher;

class AccountingVoucherService
{
    public function addAccountingVoucher(string $date, int $voucherType, ?string $remarks, object $codeGenerator, string $voucherPrefix, float $debitTotal, float $creditTotal, float $totalAmount, int $isTransactionDetails = 1, ?string $reference = null, ?int $saleRefId = null, ?int $saleReturnRefId = null, ?int $purchaseRefId = null, ?int $purchaseReturnRefId = null, ?int $stockAdjustmentRefId = null, ?int $payrollRefId = null, ?int $branchId = null): ?object
    {
        $voucherNo = $codeGenerator->generateMonthAndTypeWise(table: 'accounting_vouchers', column: 'voucher_no', typeColName: 'voucher_type', typeValue: $voucherType, prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addAccountingVoucher = new AccountingVoucher();
        $addAccountingVoucher->branch_id = auth()->user()->branch_id;
        $addAccountingVoucher->sale_ref_id = $saleRefId;
        $addAccountingVoucher->sale_return_ref_id = $saleReturnRefId;
        $addAccountingVoucher->purchase_ref_id = $purchaseRefId;
        $addAccountingVoucher->purchase_return_ref_id = $purchaseReturnRefId;
        $addAccountingVoucher->stock_adjustment_ref_id = $stockAdjustmentRefId;
        $addAccountingVoucher->payroll_ref_id = $payrollRefId;
        $addAccountingVoucher->voucher_type = $voucherType;
        $addAccountingVoucher->voucher_no = $voucherNo;
        $addAccountingVoucher->debit_total = $debitTotal;
        $addAccountingVoucher->credit_total = $creditTotal;
        $addAccountingVoucher->total_amount = $totalAmount;
        $addAccountingVoucher->date = $date;
        $addAccountingVoucher->remarks = $remarks;
        $addAccountingVoucher->reference = $reference;
        $addAccountingVoucher->date_ts = date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $addAccountingVoucher->created_by_id = auth()->user()->id;
        $addAccountingVoucher->is_transaction_details = $isTransactionDetails;
        $addAccountingVoucher->save();

        return $addAccountingVoucher;
    }

    public function updateAccountingVoucher(int $id, string $date, ?string $remarks, float $debitTotal, float $creditTotal, float $totalAmount, int $isTransactionDetails = 1, string $reference = null, ?int $saleRefId = null, ?int $saleReturnRefId = null, ?int $purchaseRefId = null, ?int $purchaseReturnRefId = null, ?int $stockAdjustmentRefId = null, ?int $payrollRefId = null)
    {
        $updateAccountingVoucher = $this->singleAccountingVoucher(id: $id, with: [
            'payrollRef',
            'voucherDescriptions',
            'voucherDebitDescription',
            'voucherDebitDescription.references',
            'voucherDebitDescription.references.sale',
            'voucherDebitDescription.references.purchase',
            'voucherDebitDescription.references.salesReturn',
            'voucherDebitDescription.references.purchaseReturn',
            'voucherDebitDescription.references.stockAdjustment',
            'voucherDebitDescription.references.payroll',
            'voucherCreditDescription',
            'voucherCreditDescription.references',
            'voucherCreditDescription.references.sale',
            'voucherCreditDescription.references.purchase',
            'voucherCreditDescription.references.salesReturn',
            'voucherCreditDescription.references.purchaseReturn',
            'voucherCreditDescription.references.stockAdjustment',
            'voucherCreditDescription.references.payroll',
            'voucherDebitDescriptions',
        ]);

        $updateAccountingVoucher->sale_ref_id = $saleRefId ? $saleRefId : $updateAccountingVoucher->sale_ref_id;
        $updateAccountingVoucher->sale_return_ref_id = $saleReturnRefId ? $saleReturnRefId : $updateAccountingVoucher->sale_return_ref_id;
        $updateAccountingVoucher->purchase_ref_id = $purchaseRefId ? $purchaseRefId : $updateAccountingVoucher->purchase_ref_id;
        $updateAccountingVoucher->purchase_return_ref_id = $purchaseReturnRefId ? $purchaseReturnRefId : $updateAccountingVoucher->purchase_return_ref_id;
        $updateAccountingVoucher->stock_adjustment_ref_id = $stockAdjustmentRefId ? $stockAdjustmentRefId : $updateAccountingVoucher->stock_adjustment_ref_id;
        $updateAccountingVoucher->payroll_ref_id = $payrollRefId ? $payrollRefId : $updateAccountingVoucher->payroll_ref_id;
        $updateAccountingVoucher->debit_total = $debitTotal;
        $updateAccountingVoucher->credit_total = $creditTotal;
        $updateAccountingVoucher->total_amount = $totalAmount;
        $updateAccountingVoucher->date = $date;
        $updateAccountingVoucher->remarks = $remarks;
        $updateAccountingVoucher->reference = $reference;
        $time = date(' H:i:s', strtotime($updateAccountingVoucher->date_ts));
        $updateAccountingVoucher->date_ts = date('Y-m-d H:i:s', strtotime($date . $time));
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

    public function accountingVouchers(array $with = null)
    {
        $query = AccountingVoucher::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleAccountingVoucher(int $id, array $with = null)
    {
        $query = AccountingVoucher::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
