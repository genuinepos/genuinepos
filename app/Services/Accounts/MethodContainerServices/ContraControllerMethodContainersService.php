<?php

namespace App\Services\Accounts\MethodContainerServices;

use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\UserActivityLogActionType;
use App\Services\Accounts\ContraService;
use App\Services\Branches\BranchService;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\Setups\PaymentMethodService;
use App\Services\Users\UserActivityLogService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountLedgerService;
use App\Services\Accounts\AccountingVoucherService;
use App\Services\Accounts\AccountingVoucherDescriptionService;
use App\Interfaces\Accounts\ContraControllerMethodContainersInterface;

class ContraControllerMethodContainersService implements ContraControllerMethodContainersInterface
{
    public function __construct(
        private ContraService $contraService,
        private BranchService $branchService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private AccountLedgerService $accountLedgerService,
        private PaymentMethodService $paymentMethodService,
        private DayBookService $dayBookService,
        private AccountingVoucherService $accountingVoucherService,
        private AccountingVoucherDescriptionService $accountingVoucherDescriptionService,
        private UserActivityLogService $userActivityLogService,
    ) {}

    public function indexMethodContainer(object $request): object|array
    {
        $data = [];
        if ($request->ajax()) {

            return $this->contraService->contraTable(request: $request);
        }

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['contra'] = $this->accountingVoucherService->singleAccountingVoucher(
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

    public function printMethodContainer(int $id, object $request): ?array
    {
        $data = [];
        $data['contra'] = $this->accountingVoucherService->singleAccountingVoucher(
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

        return $data;
    }

    public function storeMethodContainer(object $request, object $codeGenerator): ?array
    {
        $restrictions = $this->contraService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $contraVoucherPrefix = $generalSettings['prefix__contra_voucher_prefix'] ? $generalSettings['prefix__contra_voucher_prefix'] : 'CO';

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

        $senderAccountName = $contra?->voucherCreditDescription?->account?->name;
        $receiverAccountName = $contra?->voucherDebitDescription?->account?->name;

        $remarks = 'Sender:' . $senderAccountName . ' - ' . $contra->total_amount . ', Receiver: ' . $receiverAccountName . ' - ' . $contra->total_amount;

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Contra->value, dataObj: $contra, remarks: $remarks);

        $printPageSize = $request->print_page_size;

        return ['contra' => $contra, 'printPageSize' => $printPageSize];
    }

    public function editMethodContainer(int $id): ?array
    {
        $data = [];
        $data['contra'] = $this->accountingVoucherService->singleAccountingVoucher(
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

        $data['accounts'] = $this->accountFilterService->filterCashBankAccounts($accounts);
        $data['methods'] = $this->paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): ?array
    {
        $restrictions = $this->contraService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
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

        $senderAccountName = $updateAccountingVoucher?->fresh('voucherCreditDescription.account')?->voucherCreditDescription?->account?->name;
        $receiverAccountName = $updateAccountingVoucher?->fresh('voucherDebitDescription.account')?->voucherDebitDescription?->account?->name;

        $remarks = 'Sender: ' . $senderAccountName . ' - ' . $updateAccountingVoucher->total_amount . ', Receiver: ' . $receiverAccountName . ' - ' . $updateAccountingVoucher->total_amount;

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Contra->value, dataObj: $updateAccountingVoucher, remarks: $remarks);

        return null;
    }

    public function deleteMethodContainer(int $id): void
    {
        $deleteContra = $this->contraService->deleteContra(id: $id);

        $senderAccountName = $deleteContra?->voucherCreditDescription?->account?->name;
        $receiverAccountName = $deleteContra?->voucherDebitDescription?->account?->name;

        $remarks = 'Sender: ' . $senderAccountName . ' - ' . $deleteContra->total_amount . ', Receiver: ' . $receiverAccountName . ' - ' . $deleteContra->total_amount;

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Contra->value, dataObj: $deleteContra, remarks: $remarks);
    }
}
