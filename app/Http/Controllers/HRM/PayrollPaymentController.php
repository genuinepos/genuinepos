<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Hrm\PayrollService;
use App\Enums\AccountLedgerVoucherType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Hrm\PayrollPaymentService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;

class PayrollPaymentController extends Controller
{
    public function __construct(
        private PayrollPaymentService $payrollPaymentService,
        private AccountService $accountService,
        private PayrollService $payrollService,
        private AccountFilterService $accountFilterService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private AccountLedgerService $accountLedgerService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private AccountingVoucherDescriptionReferenceService $accountingVoucherDescriptionReferenceService,
    ) {
    }

    public function create($payrollId)
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $payroll = $this->payrollService->singlePayroll(with: ['user', 'user.designation', 'expenseAccount'])->where('id', $payrollId)->first();

        $accounts = $this->accountService->accounts(with: [
            'bank:id,name',
            'group:id,name,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $accounts = $this->accountFilterService->filterCashBankAccounts($accounts);

        $expenseAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $methods = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return view('hrm.payroll_payments.create', compact('accounts', 'expenseAccounts', 'methods', 'payroll'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerator)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'paying_amount' => 'required',
            'payment_method_id' => 'required',
            'credit_account_id' => 'required',
            'debit_account_id' => 'required',
            'payroll_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $restrictions = $this->payrollPaymentService->restrictions(request: $request);

            if ($restrictions['pass'] == false) {

                return ['pass' => false, 'msg' => $restrictions['msg']];
            }

            $generalSettings = config('generalSettings');
            // $branchSetting = $branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
            // $paymentVoucherPrefix = isset($branchSetting) && $branchSetting?->payment_voucher_prefix ? $branchSetting?->payment_voucher_prefix : $generalSettings['prefix__payment'];
            $paymentVoucherPrefix = 'PRP';

            // Add Accounting Voucher
            $addAccountingVoucher = $this->accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::PayrollPayment->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, payrollRefId: $request->payroll_id);

            // Add Payment Description Debit Entry
            $addAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

            // Add Day Book entry for Payment
            $this->dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::PayrollPayment->value, date: $request->date, accountId: $request->debit_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

            // Add Accounting VoucherDescription References
            $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->debit_account_id, amount: $request->paying_amount, refIdColName: 'payroll_id', refIds: [$request->payroll_id]);

            // Add Debit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PayrollPayment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->credit_account_id);

            // Add Credit Account Accounting voucher Description
            $addAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

            // Add Credit Ledger Entry
            $this->accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PayrollPayment->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');

            $payment = $this->accountingVoucherService->singleAccountingVoucher(
                id: $addAccountingVoucher->id,
                with: [
                    'branch',
                    'branch.parentBranch',
                    'voucherDescriptions',
                    'voucherDescriptions.references',
                    'payrollRef',
                    'payrollRef.user',
                    'payrollRef.expenseAccount',
                ],
            );

            $this->payrollService->adjustPayrollAmounts(payroll: $payment?->payrollRef);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('hrm.save_and_print_templates.print_payroll_payment', compact('payment'));
        } else {

            return response()->json(['successMsg' => __('Payroll payment added successfully.')]);
        }
    }
}
