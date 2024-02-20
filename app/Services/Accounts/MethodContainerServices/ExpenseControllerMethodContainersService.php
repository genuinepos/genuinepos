<?php

namespace App\Services\Accounts\MethodContainerServices;

use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\DayBookVoucherType;
use App\Interfaces\Accounts\ExpenseControllerMethodContainersInterface;

class ExpenseControllerMethodContainersService implements ExpenseControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $accountingVoucherService,
    ): ?array {

        $data = [];
        $data['expense'] = $accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'voucherDescriptions',
                'voucherDescriptions.account:id,name,account_number',
                'voucherDescriptions.paymentMethod:id,name',
                'createdBy:id,prefix,name,last_name',
            ],
        );

        return $data;
    }

    public function printMethodContainer(
        int $id,
        object $request,
        object $accountingVoucherService,
    ): ?array {

        $data = [];
        $data['expense'] = $accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'voucherDescriptions',
                'voucherDescriptions.account:id,name,account_number',
                'voucherDescriptions.paymentMethod:id,name',
                'createdBy:id,prefix,name,last_name',
            ],
        );

        $data['printPageSize'] = $request->print_page_size;

        return $data;
    }

    public function createMethodContainer(
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array {
        $data = [];
        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['expenseAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $expenseService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $dayBookService,
        object $codeGenerator,
    ): ?array {

        $generalSettings = config('generalSettings');
        $expenseVoucherPrefix = $generalSettings['prefix__expense_voucher_prefix'] ? $generalSettings['prefix__expense_voucher_prefix'] : 'EV';
        $restrictions = $expenseService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Expense->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $expenseVoucherPrefix, debitTotal: $request->total_amount, creditTotal: $request->total_amount, totalAmount: $request->total_amount);

        foreach ($request->debit_account_ids as $index => $debit_account_id) {

            // Add Expense Description Debit Entry
            $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->amounts[$index]);

            if ($index == 0) {

                $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Expense->value, date: $request->date, accountId: $debit_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->total_amount, amountType: 'debit');
            }

            //Add Debit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $request->date, account_id: $debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->amounts[$index], amount_type: 'debit', cash_bank_account_id: $request->credit_account_id);
        }

        // Add Credit Account Accounting voucher Description
        $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->total_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Credit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->total_amount, amount_type: 'credit');

        $expense = $accountingVoucherService->singleAccountingVoucher(
            id: $addAccountingVoucher->id,
            with: [
                'branch',
                'branch.parentBranch',
                'voucherDescriptions',
                'voucherDescriptions.account:id,name,account_number',
                'voucherDescriptions.paymentMethod:id,name',
                'createdBy:id,prefix,name,last_name',
            ],
        );

        return ['expense' => $expense];
    }

    public function editMethodContainer(
        int $id,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array {

        $data = [];
        $expense = $accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: ['voucherDescriptions'],
        );

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $expense->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['expenseAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $expense->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $data['expense'] = $expense;

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $expenseService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $dayBookService,
    ): ?array {

        $restrictions = $expenseService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $updateAccountingVoucher = $accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->total_amount, creditTotal: $request->total_amount, totalAmount: $request->total_amount);

        $accountingVoucherDescriptionService->prepareUnusedDeletableAccountingDescriptions(descriptions: $updateAccountingVoucher->voucherDebitDescriptions);

        foreach ($request->debit_account_ids as $index => $debit_account_id) {

            // Update Expense Description Debit Entry
            $updateAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $request->accounting_voucher_description_ids[$index], accountId: $debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->amounts[$index]);

            if ($index == 0) {

                $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Expense->value, date: $request->date, accountId: $debit_account_id, transId: $updateAccountingVoucherDebitDescription->id, amount: $request->total_amount, amountType: 'debit', branchId: $updateAccountingVoucher->branch_id);
            }

            //update Debit Ledger Entry
            $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $request->date, account_id: $debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->amounts[$index], amount_type: 'debit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherDebitDescription->current_account_id, cash_bank_account_id: $request->credit_account_id);
        }

        $accountingVoucherDescriptionService->deleteUnusedAccountingVoucherDescriptions($updateAccountingVoucher->id);

        // Add Credit Account Accounting voucher Description
        $updateAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher?->voucherCreditDescription?->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->total_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Credit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->total_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id);

        return null;
    }
}
