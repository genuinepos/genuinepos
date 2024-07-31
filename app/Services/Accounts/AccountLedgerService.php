<?php

namespace App\Services\Accounts;

use App\Models\Accounts\AccountLedger;

class AccountLedgerService
{
    public function voucherType($voucher_type_id)
    {
        $data = [
            0 => ['name' => 'Opening Balance', 'id' => 'account_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'account_id', 'link' => ''],
            1 => ['name' => 'Sales', 'id' => 'sale_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'sale_id', 'link' => 'sales.show'],
            2 => ['name' => 'Sales Return', 'id' => 'sale_return_id', 'voucher_no' => 'sale_return_voucher', 'details_id' => 'sale_return_id', 'link' => 'sales.returns.show'],
            3 => ['name' => 'Purchase', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher', 'details_id' => 'purchase_id', 'link' => 'purchases.show'],
            4 => ['name' => 'Purchase Return', 'id' => 'purchase_return_id', 'voucher_no' => 'purchase_return_voucher', 'details_id' => 'purchase_return_id', 'link' => 'purchase.returns.show'],
            5 => ['name' => 'Expenses', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'expenses.show'],
            7 => ['name' => 'Stock Adjustment', 'id' => 'adjustment_id', 'voucher_no' => 'stock_adjustment_voucher', 'details_id' => 'adjustment_id', 'link' => 'stock.adjustments.show'],
            8 => ['name' => 'Receipt', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'receipts.show'],
            9 => ['name' => 'Payment', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'payments.show'],
            12 => ['name' => 'Contra', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'contras.show'],
            13 => ['name' => 'Journal', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'journals.show'],
            15 => ['name' => 'Incomes', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'incomes.show'],
            16 => ['name' => 'Sales', 'id' => 'sale_product_id', 'voucher_no' => 'product_sale_voucher', 'details_id' => 'product_sale_id', 'link' => 'sales.show'],
            17 => ['name' => 'Purchase', 'id' => 'purchase_product_id', 'voucher_no' => 'product_purchase_voucher', 'details_id' => 'product_purchase_id', 'link' => 'purchases.show'],
            18 => ['name' => 'Sales Return', 'id' => 'sale_return_product_id', 'voucher_no' => 'product_sale_return_voucher', 'details_id' => 'product_sale_return_id', 'link' => 'sales.returns.show'],
            19 => ['name' => 'Purchase Return', 'id' => 'purchase_return_product_id', 'voucher_no' => 'product_purchase_return_voucher', 'details_id' => 'product_purchase_return_id', 'link' => 'purchase.returns.show'],
            20 => ['name' => 'Exchange', 'id' => 'sale_product_id', 'voucher_no' => 'product_sale_voucher', 'details_id' => 'product_sale_id', 'link' => 'sales.show'],
            21 => ['name' => 'Payroll Payment', 'id' => 'voucher_description_id', 'voucher_no' => 'accounting_voucher_no', 'details_id' => 'accounting_voucher_id', 'link' => 'hrm.payroll.payments.show'],
        ];

        return $data[$voucher_type_id];
    }

    public function addAccountLedgerEntry(
        $voucher_type_id,
        $date,
        $account_id,
        $trans_id,
        $amount,
        $amount_type,
        $cash_bank_account_id = null,
        $branch_id = null,
        $temporary_time = null,
    ) {
        $branchId = $branch_id ? $branch_id : auth()->user()->branch_id;
        $voucherType = $this->voucherType($voucher_type_id);
        $add = new AccountLedger();
        $time = $voucher_type_id == 0 ? ' 01:00:00' : date(' H:i:s');
        $__time = $temporary_time ? ' '.$temporary_time : $time;
        // $add->date = date('Y-m-d H:i:s', strtotime($date.$time));
        $add->date = date('Y-m-d H:i:s', strtotime($date.$__time));
        $add->account_id = $account_id;
        $add->voucher_type = $voucher_type_id;
        $add->{$voucherType['id']} = $trans_id;
        $add->{$amount_type} = $amount;
        $add->amount_type = $amount_type;
        $add->is_cash_flow = isset($cash_bank_account_id) ? 1 : 0;
        $add->branch_id = $branchId;
        $add->save();
    }

    public function updateAccountLedgerEntry(
        $voucher_type_id,
        $date,
        $account_id,
        $trans_id,
        $amount,
        $amount_type,
        $branch_id = null,
        $current_account_id = null,
        $cash_bank_account_id = null
    ) {
        $branchId = $branch_id ? $branch_id : auth()->user()->branch_id;
        $voucherType = $this->voucherType($voucher_type_id);
        $update = '';
        $query = AccountLedger::where($voucherType['id'], $trans_id)->where('voucher_type', $voucher_type_id);

        if ($current_account_id) {

            $query->where('account_id', $current_account_id);
        }

        $update = $query->where('branch_id', $branchId)->first();

        if ($update) {

            $update->debit = 0;
            $update->credit = 0;
            $previousAccountId = $update->account_id;
            $previousTime = date(' H:i:s', strtotime($update->date));
            $update->date = date('Y-m-d H:i:s', strtotime($date.$previousTime));
            $update->account_id = $account_id;
            $update->{$amount_type} = $amount;
            $update->amount_type = $amount_type;
            $update->is_cash_flow = isset($cash_bank_account_id) ? 1 : 0;
            $update->save();
        } else {

            $this->addAccountLedgerEntry(
                $voucher_type_id,
                $date,
                $account_id,
                $trans_id,
                $amount,
                $amount_type,
                $cash_bank_account_id,
                $branch_id,
            );
        }
    }

    public function deleteUnusedLedgerEntry($voucherType, $transId, $accountId)
    {
        $voucherTypeArray = $this->voucherType($voucherType);
        $deleteAccountLedger = AccountLedger::where('voucher_type', $voucherType)
            ->where($voucherTypeArray['id'], $transId)->where('account_id', $accountId)->first();

        if (! is_null($deleteAccountLedger)) {

            $deleteAccountLedger->delete();
        }
    }

    public function singleLedgerEntry($voucherType, $transId, $accountId)
    {
        $voucherTypeArray = $this->voucherType($voucherType);

        return AccountLedger::where('voucher_type', $voucherType)
            ->where($voucherTypeArray['id'], $transId)
            ->where('account_id', $accountId)->first();
    }
}
