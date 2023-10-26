<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\AccountLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Accounts\ExpenseService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseService $expenseService,
        private BranchService $branchService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private BranchSettingService $branchSettingService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
    ) {
    }

    function index(Request $request)
    {
        if (!auth()->user()->can('view_expense')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->expenseService->expensesTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('accounting.accounting_vouchers.expenses.index', compact('branches'));
    }

    function show($id)
    {
        $expense = $this->accountingVoucherService->singleAccountingVoucher(
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

        return view('accounting.accounting_vouchers.expenses.ajax_view.show', compact('expense'));
    }

    function create()
    {
        if (!auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $expenseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        return view('accounting.accounting_vouchers.expenses.ajax_view.create', compact('accounts', 'methods', 'expenseAccounts'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        if (!auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required|date',
            'total_amount' => 'required',
            'payment_method_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $expenseVoucherPrefix = 'EV' . auth()->user()?->branch?->branch_code;

            $restrictions = $this->expenseService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.save_and_print_template.print_expense', compact('expense'));
        } else {

            return response()->json(['successMsg' => __("Expense added successfully.")]);
        }
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $expense = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: ['voucherDescriptions'],
        );

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $expense->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $expenseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $expense->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        return view('accounting.accounting_vouchers.expenses.ajax_view.edit', compact('expense', 'accounts', 'methods', 'expenseAccounts'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required|date',
            'total_amount' => 'required',
            'payment_method_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $generalSettings = config('generalSettings');

            $restrictions = $this->expenseService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Expense updated successfully."));
    }

    function delete($id)
    {
        if (!auth()->user()->can('delete_expense')) {

            abort(403, 'Access Forbidden.');
        }

        try {
            DB::beginTransaction();

            $deleteExpense = $this->expenseService->deleteExpense(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Expense deleted successfully."));
    }
}
