<?php

namespace App\Services\Accounts\MethodContainerServices;

use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Interfaces\Accounts\ContraControllerMethodContainersInterface;

class ContraControllerMethodContainersService implements ContraControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $accountingVoucherService,
    ): ?array {

        $data = [];
        $data['contra'] = $accountingVoucherService->singleAccountingVoucher(
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
        return $data;
    }

    public function printMethodContainer(
        int $id,
        object $request,
        object $accountingVoucherService,
    ): ?array {

        $data = [];
        $data['contra'] = $accountingVoucherService->singleAccountingVoucher(
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

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $contraService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $dayBookService,
        object $codeGenerator,
    ): ?array {

        $restrictions = $contraService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $contraVoucherPrefix = $generalSettings['prefix__contra_voucher_prefix'] ? $generalSettings['prefix__contra_voucher_prefix'] : 'CO';

        // Add Accounting Voucher
        $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Contra->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $contraVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount);

        // Add Contra Description Debit Entry
        $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount);

        // Add Day Book entry for Contra
        $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Contra->value, date: $request->date, accountId: $request->debit_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amountType: 'debit');

        //Add Debit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Contra->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

        // Add Credit Account Accounting voucher Description
        $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Credit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Contra->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit');

        $contra = $accountingVoucherService->singleAccountingVoucher(
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

        return ['contra' => $contra];
    }

    public function editMethodContainer(
        int $id,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
    ): ?array {

        $data = [];
        $data['contra'] = $accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: ['voucherDebitDescription', 'voucherCreditDescription']
        );

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

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $contraService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $dayBookService,
    ): ?array {

        $restrictions = $contraService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $updateAccountingVoucher = $accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount);

        // Add Contra Description Debit Entry
        $updateAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherDebitDescription->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->received_amount);

        // Add Day Book entry for Contra
        $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Contra->value, date: $request->date, accountId: $request->debit_account_id, transId: $updateAccountingVoucherDebitDescription->id, amount: $request->received_amount, amountType: 'debit');

        //Add Debit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Contra->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherDebitDescription->current_account_id);

        // Add Credit Account Accounting voucher Description
        $updateAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherCreditDescription->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Credit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Contra->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id);

        return null;
    }
}
