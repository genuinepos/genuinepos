<?php

namespace App\Services\Accounts;

use App\Models\Accounts\AccountingVoucherDescription;

class AccountingVoucherDescriptionService
{
    public function addAccountingVoucherDescription(
        int $accountingVoucherId,
        int $accountId,
        int $paymentMethodId,
        string $amountType,
        float $amount,
        ?string $transactionNo = null,
        ?string $chequeNo = null,
        ?string $chequeSerialNo = null,
        ?string $chequeIssueDate = null,
        ?string $note = null,
    ) {
        $accountGroup = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        $AccountingVoucherDescription = new AccountingVoucherDescription();
        $AccountingVoucherDescription->accounting_voucher_id = $accountingVoucherId;
        $AccountingVoucherDescription->account_id = $accountId;
        $AccountingVoucherDescription->payment_method_id = $paymentMethodId;
        $AccountingVoucherDescription->amount_type = $amountType;
        $AccountingVoucherDescription->amount = $amount;
        $AccountingVoucherDescription->transaction_no = $transactionNo;
        $AccountingVoucherDescription->cheque_no = $chequeNo;
        $AccountingVoucherDescription->cheque_serial_no = $chequeSerialNo;
        $AccountingVoucherDescription->cheque_issue_date = $chequeIssueDate;
        $AccountingVoucherDescription->note = $note;
        $AccountingVoucherDescription->save();

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 2 || $accountGroup->sub_sub_group_number == 11) {

            $AccountingVoucherDescription->is_cash_bank_ac = 1;
        } else {

            $AccountingVoucherDescription->is_cash_bank_ac = 0;
        }

        return $AccountingVoucherDescription;
    }

    public function updatePaymentDescription(
        int $accountingVoucherId,
        int $accountingVoucherDescriptionId,
        int $accountId,
        int $paymentMethodId,
        string $amountType,
        float $amount,
        ?string $transactionNo = null,
        ?string $chequeNo = null,
        ?string $chequeSerialNo = null,
        ?string $chequeIssueDate = null,
        ?string $note = null
    ) {
        $accountingVoucherDescription = AccountingVoucherDescription::where('id', $accountingVoucherDescriptionId)->where('payment_id', $accountingVoucherId)->first();
        $addOrUpdateAccountingVoucherDescription = '';

        if ($accountingVoucherDescription) {

            $addOrUpdateAccountingVoucherDescription = $accountingVoucherDescription;
        } else {

            $addOrUpdateAccountingVoucherDescription = new AccountingVoucherDescription();
        }

        $accountGroup = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        $addOrUpdateAccountingVoucherDescription->accounting_voucher_id = $accountingVoucherId;
        $addOrUpdateAccountingVoucherDescription->account_id = $accountId;
        $addOrUpdateAccountingVoucherDescription->payment_method_id = $paymentMethodId;
        $addOrUpdateAccountingVoucherDescription->amount_type = $amountType;
        $addOrUpdateAccountingVoucherDescription->amount = $amount;
        $addOrUpdateAccountingVoucherDescription->transaction_no = $transactionNo;
        $addOrUpdateAccountingVoucherDescription->cheque_no = $chequeNo;
        $addOrUpdateAccountingVoucherDescription->cheque_serial_no = $chequeSerialNo;
        $addOrUpdateAccountingVoucherDescription->cheque_issue_date = $chequeIssueDate;
        $addOrUpdateAccountingVoucherDescription->cheque_issue_date = $chequeIssueDate;
        $addOrUpdateAccountingVoucherDescription->note = $note;
        $addOrUpdateAccountingVoucherDescription->is_delete_in_update = 0;
        $addOrUpdateAccountingVoucherDescription->save();

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 2 || $accountGroup->sub_sub_group_number == 11) {

            $addOrUpdateAccountingVoucherDescription->is_cash_bank_ac = 1;
        } else {

            $addOrUpdateAccountingVoucherDescription->is_cash_bank_ac = 0;
        }

        return $addOrUpdateAccountingVoucherDescription;
    }

    public function prepareUnusedDeletableAccountingDescriptions($descriptions)
    {
        foreach ($descriptions as $description) {

            $description->is_delete_in_update = 1;
            $description->save();
        }
    }

    public function deleteUnusedAccountingVoucherDescriptions($paymentId)
    {
        $deletableDescriptions = AccountingVoucherDescription::where('payment_id', $paymentId)->where('is_delete_in_update', 1)->get();

        foreach ($deletableDescriptions as $deletableDescription) {

            $deletableDescription->delete();
        }
    }

    public function getCashBankAccountId($request)
    {
        $cashBankAccountId = null;
        foreach ($request->account_ids as $accountId) {

            $account = DB::table('accounts')->where('accounts.id', $accountId)
                ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->select('accounts.id', 'account_groups.sub_sub_group_number')->first();

            if ($account->sub_sub_group_number == 1 || $account->sub_sub_group_number == 2 || $account->sub_sub_group_number == 11) {

                if (! isset($cashBankAccountId)) {

                    $cashBankAccountId = $account->id;
                }
            }

            if ($cashBankAccountId != null) {

                break;
            }
        }

        return $cashBankAccountId;
    }
}