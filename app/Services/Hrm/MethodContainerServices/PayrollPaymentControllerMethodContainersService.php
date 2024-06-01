<?php

namespace App\Services\Hrm\MethodContainerServices;

use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Services\Hrm\PayrollService;
use App\Enums\AccountLedgerVoucherType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Hrm\PayrollPaymentService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Services\Accounts\AccountingVoucherDescriptionReferenceService;
use App\Interfaces\Hrm\PayrollPaymentControllerMethodContainersInterface;

class PayrollPaymentControllerMethodContainersService implements PayrollPaymentControllerMethodContainersInterface
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

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['payment'] = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
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

        return $data;
    }

    public function printMethodContainer(int $id, object $request): ?array
    {
        $data = [];
        $data['payment'] = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
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

        $data['printPageSize'] = $request->print_page_size;
        return $data;
    }

    public function createMethodContainer(int $payrollId): ?array
    {
        $data = [];
        $data['payroll'] = $this->payrollService->singlePayroll(with: ['user', 'user.designation', 'expenseAccount'])->where('id', $payrollId)->first();

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

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['expenseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->payrollPaymentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $paymentVoucherPrefix = $generalSettings['prefix__payroll_payment_voucher_prefix'] ? $generalSettings['prefix__payroll_payment_voucher_prefix'] : 'PRLP';

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

        $printPageSize = $request->print_page_size;

        return ['payment' => $payment, 'printPageSize' => $printPageSize];
    }

    public function editMethodContainer(int $id): ?array
    {
        $data = [];
        $data['payment'] = $this->accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'payrollRef',
                'payrollRef.user',
                'voucherCreditDescription',
                'voucherCreditDescription',
                'voucherDebitDescription',
                'voucherDebitDescription.account',
                'voucherDebitDescription.account.group',
            ],
        );

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

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);

        $data['expenseAccounts'] = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): ?array
    {
        $restrictions = $this->payrollPaymentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $updateAccountingVoucher = $this->accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount);

        // Add Payment Description Debit Entry
        $updateAccountingVoucherDebitDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherDebitDescription->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

        // Add Day Book entry for Payment
        $this->dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::PayrollPayment->value, date: $request->date, accountId: $request->debit_account_id, transId: $updateAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

        if ($updateAccountingVoucher?->voucherDebitDescription && count($updateAccountingVoucher?->voucherDebitDescription?->references) > 0) {

            foreach ($updateAccountingVoucher?->voucherDebitDescription?->references as $reference) {

                $reference->delete();

                if ($reference->payroll) {

                    $this->payrollService->adjustPayrollAmounts(payroll: $reference->payroll);
                }
            }
        }

        // Add Accounting VoucherDescription References
        $this->accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $updateAccountingVoucherDebitDescription->id, accountId: $request->debit_account_id, amount: $request->paying_amount, refIdColName: 'payroll_id', refIds: [$updateAccountingVoucher->payroll_ref_id]);

        // Add Debit Ledger Entry
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PayrollPayment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', current_account_id: $updateAccountingVoucherDebitDescription->current_account_id, cash_bank_account_id: $request->credit_account_id);

        // Add Credit Account Accounting voucher Description
        $updateAccountingVoucherCreditDescription = $this->accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherCreditDescription->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        // Add Credit Ledger Entry
        $this->accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PayrollPayment->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id);

        $this->payrollService->adjustPayrollAmounts(payroll: $updateAccountingVoucher?->payrollRef);

        return null;
    }

    public function deleteMethodContainer(int $id): void
    {
        $deletePayrollPayment = $this->payrollPaymentService->deletePayrollPayment(id: $id);
        if ($deletePayrollPayment?->payrollRef) {

            $this->payrollService->adjustPayrollAmounts(payroll: $deletePayrollPayment?->payrollRef);
        }
    }
}
