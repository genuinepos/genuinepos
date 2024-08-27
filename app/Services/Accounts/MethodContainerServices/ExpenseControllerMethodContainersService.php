<?php

namespace App\Services\Accounts\MethodContainerServices;

use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Services\Branches\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Accounts\ExpenseService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Accounts\ExpenseControllerMethodContainersInterface;

class ExpenseControllerMethodContainersService implements ExpenseControllerMethodContainersInterface
{
    public function __construct(
        private ExpenseService $expenseService,
        private BranchService $branchService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
    ) {}

    public function indexMethodContainer(object $request): object|array
    {
        $data = [];
        if ($request->ajax()) {

            return $this->expenseService->expensesTable(request: $request);
        }

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['expense'] = $this->accountingVoucherService->singleAccountingVoucher(
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

    public function printMethodContainer(int $id, object $request): ?array
    {
        $data = [];
        $data['expense'] = $this->accountingVoucherService->singleAccountingVoucher(
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

    public function createMethodContainer(): ?array
    {
        $data = [];
        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['expenseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): ?array
    {
        $generalSettings = config('generalSettings');
        $expenseVoucherPrefix = $generalSettings['prefix__expense_voucher_prefix'] ? $generalSettings['prefix__expense_voucher_prefix'] : 'EV';
        $restrictions = $this->expenseService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Expense->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $expenseVoucherPrefix, debitTotal: $request->total_amount, creditTotal: $request->total_amount, totalAmount: $request->total_amount);

        foreach ($request->debit_account_ids as $index => $debit_account_id) {

            // Add Expense Description Debit Entry
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->amounts[$index]);

            if ($index == 0) {

                $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Expense->value, date: $request->date, accountId: $debit_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->total_amount, amountType: 'debit');
            }

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $request->date, account_id: $debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->amounts[$index], amount_type: 'debit', cash_bank_account_id: $request->credit_account_id);
        }

        // Add Credit Account Accounting voucher Description
        $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->total_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Credit Ledger Entry
        $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->total_amount, amount_type: 'credit');

        $expense = $this->accountingVoucherService->singleAccountingVoucher(
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

        $printPageSize = $request->print_page_size;
        return ['expense' => $expense, 'printPageSize' => $printPageSize];
    }

    public function editMethodContainer(int $id): ?array
    {
        $data = [];
        $expense = $this->accountingVoucherService->singleAccountingVoucher(id: $id, with: ['voucherDescriptions']);

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $expense->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['expenseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $expense->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $data['expense'] = $expense;

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): ?array
    {
        $restrictions = $this->expenseService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $updateAccountingVoucher = $this->accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->total_amount, creditTotal: $request->total_amount, totalAmount: $request->total_amount);

        $this->accountingVoucherDescriptionService->prepareUnusedDeletableAccountingDescriptions(descriptions: $updateAccountingVoucher->voucherDebitDescriptions);

        foreach ($request->debit_account_ids as $index => $debit_account_id) {

            // Update Expense Description Debit Entry
            $updateAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $request->accounting_voucher_description_ids[$index], accountId: $debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->amounts[$index]);

            if ($index == 0) {

                $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Expense->value, date: $request->date, accountId: $debit_account_id, transId: $updateAccountingVoucherDebitDescription->id, amount: $request->total_amount, amountType: 'debit', branchId: $updateAccountingVoucher->branch_id);
            }

            //update Debit Ledger Entry
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $request->date, account_id: $debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->amounts[$index], amount_type: 'debit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherDebitDescription->current_account_id, cash_bank_account_id: $request->credit_account_id);
        }

        $this->accountingVoucherDescriptionService->deleteUnusedAccountingVoucherDescriptions($updateAccountingVoucher->id);

        // Add Credit Account Accounting voucher Description
        $updateAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher?->voucherCreditDescription?->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->total_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Credit Ledger Entry
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->total_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id);

        return null;
    }

    public function deleteMethodContainer(int $id): void
    {
        $this->expenseService->deleteExpense(id: $id);
    }
}
