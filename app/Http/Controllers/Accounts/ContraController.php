<?php

namespace App\Http\Controllers\Accounts;

use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\DayBookVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\ContraService;
use App\Services\Accounts\DayBookService;
use App\Services\CodeGenerationService;
use App\Services\Setups\BranchService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContraController extends Controller
{
    public function __construct(
        private ContraService $contraService,
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

    public function index(Request $request)
    {
        if (! auth()->user()->can('view_expense')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->contraService->contraTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('accounting.accounting_vouchers.contras.index', compact('branches'));
    }

    public function show($id)
    {
        $contra = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'voucherDebitDescription',
                'voucherDebitDescription.account:id,name,account_number,bank_id',
                'voucherDebitDescription.account.bank:id,name',
                'voucherCreditDescription',
                'voucherCreditDescription.account:id,name,account_number',
                'voucherCreditDescription.paymentMethod:id,name',
            ],
        );

        return view('accounting.accounting_vouchers.contras.ajax_view.show', compact('contra'));
    }

    public function create()
    {
        if (! auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

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

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return view('accounting.accounting_vouchers.contras.ajax_view.create', compact('accounts', 'methods'));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'received_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $restrictions = $this->contraService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $generalSettings = config('generalSettings');
            $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            $contraVoucherPrefix = 'CO'.auth()->user()?->branch?->branch_code;

            // Add Accounting Voucher
            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Contra->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $contraVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount);

            // Add Contra Description Debit Entry
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount);

            // Add Day Book entry for Contra
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Contra->value, date: $request->date, accountId: $request->debit_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amountType: 'debit');

            //Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Contra->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Credit Account Accounting voucher Description
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

            //Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Contra->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit');

            $contra = $this->accountingVoucherService->singleAccountingVoucher(
                id: $addAccountingVoucher->id,
                with: [
                    'branch',
                    'branch.parentBranch',
                    'voucherDebitDescription',
                    'voucherDebitDescription.account:id,name,account_number,bank_id',
                    'voucherDebitDescription.account.bank:id,name',
                    'voucherCreditDescription',
                    'voucherCreditDescription.account:id,name,account_number',
                    'voucherCreditDescription.paymentMethod:id,name',
                ],
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.save_and_print_template.print_contra', compact('contra'));
        } else {

            return response()->json(['successMsg' => __('Contra added successfully.')]);
        }
    }

    public function edit($id)
    {
        if (! auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $contra = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: ['voucherDebitDescription', 'voucherCreditDescription']
        );

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

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return view('accounting.accounting_vouchers.contras.ajax_view.edit', compact('accounts', 'methods', 'contra'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'received_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $restrictions = $this->contraService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            // Add Accounting Voucher
            $updateAccountingVoucher = $this->accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount);

            // Add Contra Description Debit Entry
            $updateAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherDebitDescription->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount);

            // Add Day Book entry for Contra
            $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Contra->value, date: $request->date, accountId: $request->debit_account_id, transId: $updateAccountingVoucherDebitDescription->id, amount: $request->received_amount, amountType: 'debit');

            //Add Debit Ledger Entry
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Contra->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherDebitDescription->current_account_id);

            // Add Credit Account Accounting voucher Description
            $updateAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherCreditDescription->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

            //Add Credit Ledger Entry
            $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Contra->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Contra updated successfully.'));
    }

    public function delete($id)
    {
        if (! auth()->user()->can('delete_expense')) {

            abort(403, 'Access Forbidden.');
        }

        try {
            DB::beginTransaction();

            $deleteContra = $this->contraService->deleteContra(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Expense deleted successfully.'));
    }
}
