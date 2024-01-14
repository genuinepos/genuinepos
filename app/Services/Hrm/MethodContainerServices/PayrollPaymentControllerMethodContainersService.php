<?php

namespace App\Services\Hrm\MethodContainerServices;

use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Interfaces\Hrm\PayrollPaymentControllerMethodContainersInterface;

class PayrollPaymentControllerMethodContainersService implements PayrollPaymentControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $accountingVoucherService
    ): ?array {

        $data = [];
        $data['payment'] = $accountingVoucherService->singleAccountingVoucher(
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

    public function createMethodContainer(
        int $payrollId,
        object $payrollService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array {

        $data = [];
        $data['payroll'] = $payrollService->singlePayroll(with: ['user', 'user.designation', 'expenseAccount'])->where('id', $payrollId)->first();

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,name,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['expenseAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $payrollPaymentService,
        object $payrollService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $accountLedgerService,
        object $codeGenerator,
    ): ?array {

        $restrictions = $payrollPaymentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $paymentVoucherPrefix = $generalSettings['prefix__payroll_payment_voucher_prefix'] ? $generalSettings['prefix__payroll_payment_voucher_prefix'] : 'PRLP';

        // Add Accounting Voucher
        $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::PayrollPayment->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount, payrollRefId: $request->payroll_id);

        // Add Payment Description Debit Entry
        $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

        // Add Day Book entry for Payment
        $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::PayrollPayment->value, date: $request->date, accountId: $request->debit_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

        // Add Accounting VoucherDescription References
        $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->debit_account_id, amount: $request->paying_amount, refIdColName: 'payroll_id', refIds: [$request->payroll_id]);

        // Add Debit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PayrollPayment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->credit_account_id);

        // Add Credit Account Accounting voucher Description
        $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        // Add Credit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PayrollPayment->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');

        $payment = $accountingVoucherService->singleAccountingVoucher(
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

        $payrollService->adjustPayrollAmounts(payroll: $payment?->payrollRef);

        return ['payment' => $payment];
    }

    public function editMethodContainer(
        int $id,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array {

        $data = [];
        $data['payment'] = $accountingVoucherService->singleAccountingVoucher(
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

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,name,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['expenseAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('account_groups.sub_group_number', [10, 11])
            ->select('accounts.id', 'accounts.name')
            ->get();

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $payrollPaymentService,
        object $payrollService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $accountLedgerService,
    ): ?array {

        $restrictions = $payrollPaymentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $updateAccountingVoucher = $accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount);

        // Add Payment Description Debit Entry
        $updateAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherDebitDescription->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

        // Add Day Book entry for Payment
        $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::PayrollPayment->value, date: $request->date, accountId: $request->debit_account_id, transId: $updateAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

        if ($updateAccountingVoucher?->voucherDebitDescription && count($updateAccountingVoucher?->voucherDebitDescription?->references) > 0) {

            foreach ($updateAccountingVoucher?->voucherDebitDescription?->references as $reference) {

                $reference->delete();

                if ($reference->payroll) {

                    $payrollService->adjustPayrollAmounts(payroll: $reference->payroll);
                }
            }
        }

        // Add Accounting VoucherDescription References
        $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $updateAccountingVoucherDebitDescription->id, accountId: $request->debit_account_id, amount: $request->paying_amount, refIdColName: 'payroll_id', refIds: [$updateAccountingVoucher->payroll_ref_id]);

        // Add Debit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PayrollPayment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', current_account_id: $updateAccountingVoucherDebitDescription->current_account_id, cash_bank_account_id: $request->credit_account_id);

        // Add Credit Account Accounting voucher Description
        $updateAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherCreditDescription->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        // Add Credit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PayrollPayment->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id);

        $payrollService->adjustPayrollAmounts(payroll: $updateAccountingVoucher?->payrollRef);

        return null;
    }

    public function deleteMethodContainer(int $id, object $payrollPaymentService,  object $payrollService,): void
    {
        $deletePayrollPayment = $payrollPaymentService->deletePayrollPayment(id: $id);
        if ($deletePayrollPayment?->payrollRef) {

            $payrollService->adjustPayrollAmounts(payroll: $deletePayrollPayment?->payrollRef);
        }
    }
}
