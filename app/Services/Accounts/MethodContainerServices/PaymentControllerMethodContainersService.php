<?php

namespace App\Services\Accounts\MethodContainerServices;

use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\DayBookVoucherType;
use App\Interfaces\Accounts\PaymentControllerMethodContainersInterface;

class PaymentControllerMethodContainersService implements PaymentControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $accountingVoucherService,
    ): ?array {
        $data = [];
        $data['payment'] = $accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'branch',
                'branch.parentBranch',
                'voucherDescriptions',
                'voucherDescriptions.references',
                'voucherDescriptions.references.sale',
                'voucherDescriptions.references.purchaseReturn',
                'voucherDescriptions.references.stockAdjustment',
                'purchaseRef',
                'salesReturnRef',
                'stockAdjustmentRef',
            ],
        );

        return $data;
    }

    public function createMethodContainer(
        int $debitAccountId = null,
        object $accountService,
        object $accountFilterService,
        object $dayBookVoucherService,
        object $paymentMethodService,
    ): ?array {
        $data = [];
        $data['account'] = '';
        $data['vouchers'] = [];
        if (isset($debitAccountId)) {

            $data['account'] = $accountService->singleAccountById(id: $debitAccountId, with: ['contact']);

            $trans = $dayBookVoucherService->vouchersForPaymentReceipt(accountId: $debitAccountId, type: AccountingVoucherType::Payment->value);

            $data['vouchers'] = $dayBookVoucherService->filteredVoucher(vouchers: $trans);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

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

        $data['payableAccounts'] = '';
        if (! isset($creditAccountId)) {

            $data['payableAccounts'] = $accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $paymentService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $codeGenerator,
    ): ?array {

        $restrictions = $paymentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';

        // Add Accounting Voucher
        $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: $request->remarks, reference: $request->reference, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount);

        // Add Payment Description Debit Entry
        $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

        // Add Day Book entry for Payment
        $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Payment->value, date: $request->date, accountId: $request->debit_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

        // Add Accounting VoucherDescription References
        $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $request->debit_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: isset($request->ref_ids) ? $request->ref_ids : null);

        //Add Debit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->credit_account_id);

        // Add Credit Account Accounting voucher Description
        $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add Credit Ledger Entry
        $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->credit_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');

        $payment = $accountingVoucherService->singleAccountingVoucher(
            id: $addAccountingVoucher->id,
            with: [
                'branch',
                'branch.parentBranch',
                'voucherDescriptions',
                'voucherDescriptions.references',
                'voucherDescriptions.references.purchase',
                'voucherDescriptions.references.salesReturn',
                'purchaseRef',
                'salesReturnRef',
            ],
        );

        return ['payment' => $payment];
    }

    public function editMethodContainer(
        int $id,
        int $debitAccountId = null,
        object $accountingVoucherService,
        object $accountService,
        object $accountFilterService,
        object $dayBookVoucherService,
        object $paymentMethodService,
    ): ?array {

        $data = [];
        $payment = $accountingVoucherService->singleAccountingVoucher(
            id: $id,
            with: [
                'voucherCreditDescription',
                'voucherDebitDescription',
                'voucherDebitDescription.account',
                'voucherDebitDescription.account.group',
                'voucherDebitDescription.references',
                'voucherDebitDescription.references.purchase',
                'voucherDebitDescription.references.salesReturn',
            ],
        );

        $data['account'] = '';
        $data['vouchers'] = [];
        if (isset($debitAccountId)) {

            $data['account'] = $accountService->singleAccountById(id: $debitAccountId, with: ['contact']);
        }

        $transAccountId = isset($debitAccountId) ? $debitAccountId : $payment->voucherDebitDescription->account_id;
        $trans = $dayBookVoucherService->vouchersForPaymentReceipt(accountId: $transAccountId, type: AccountingVoucherType::Payment->value);
        $data['vouchers'] = $dayBookVoucherService->filteredVoucher(vouchers: $trans);

        $ownBranchIdOrParentBranchId = $payment?->branch?->parent_branch_id ? $payment?->branch?->parent_branch_id : $payment?->branch_id;

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,name,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $payment->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
            ->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['payableAccounts'] = '';
        if (! isset($debitAccountId)) {

            $data['payableAccounts'] = $accountService->branchAccessibleAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId);
        }

        $data['payment'] = $payment;

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $paymentService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $dayBookService,
        object $purchaseService,
        object $salesReturnService,
    ): ?array {

        $restrictions = $paymentService->restrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        // Add Accounting Voucher
        $updateAccountingVoucher = $accountingVoucherService->updateAccountingVoucher(id: $id, date: $request->date, remarks: $request->remarks, reference: $request->reference, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, totalAmount: $request->paying_amount);

        // Add Payment Description Debit Entry
        $updateAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherDebitDescription->id, accountId: $request->debit_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount);

        // Add Day Book entry for Payment
        $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Payment->value, date: $request->date, accountId: $request->debit_account_id, transId: $updateAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amountType: 'debit');

        if ($updateAccountingVoucher?->voucherDebitDescription && count($updateAccountingVoucher?->voucherDebitDescription?->references) > 0) {

            foreach ($updateAccountingVoucher?->voucherDebitDescription?->references as $reference) {

                $reference->delete();

                if ($reference->purchase) {

                    $purchaseService->adjustPurchaseInvoiceAmounts(purchase: $reference->purchase);
                } elseif ($reference->salesReturn) {

                    $salesReturnService->adjustSalesReturnVoucherAmounts(salesReturn: $reference->salesReturn);
                }
            }
        }

        // Add Accounting VoucherDescription References
        $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $updateAccountingVoucherDebitDescription->id, accountId: $request->debit_account_id, amount: $request->paying_amount, refIdColName: 'purchase_id', refIds: isset($request->ref_ids) ? $request->ref_ids : null);

        //Add Debit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherDebitDescription->current_account_id, cash_bank_account_id: $request->credit_account_id);

        // Add Credit Account Accounting voucher Description
        $updateAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->updateAccountingVoucherDescription(accountingVoucherId: $updateAccountingVoucher->id, accountingVoucherDescriptionId: $updateAccountingVoucher->voucherCreditDescription->id, accountId: $request->credit_account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no);

        //Add credit Ledger Entry
        $accountLedgerService->updateAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $request->date, account_id: $request->debit_account_id, trans_id: $updateAccountingVoucherCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit', branch_id: $updateAccountingVoucher->branch_id, current_account_id: $updateAccountingVoucherCreditDescription->current_account_id);

        return null;
    }

    public function deleteMethodContainer(
        int $id,
        object $paymentService,
        object $saleService,
        object $salesReturnService,
        object $purchaseService,
        object $purchaseReturnService,
    ): ?object {

        $deletePayment = $paymentService->deletePayment(id: $id);

        foreach ($deletePayment->voucherDescriptions as $voucherDescription) {

            if (count($voucherDescription->references)) {

                foreach ($voucherDescription->references as $reference) {

                    if ($reference->sale) {

                        $saleService->adjustSaleInvoiceAmounts(sale: $reference->sale);
                    } elseif ($reference->purchase) {

                        $purchaseService->adjustPurchaseInvoiceAmounts(purchase: $reference->purchase);
                    } elseif ($reference->salesReturn) {

                        $salesReturnService->adjustSalesReturnVoucherAmounts(salesReturn: $reference->salesReturn);
                    } elseif ($reference->purchaseReturn) {

                        $purchaseReturnService->adjustPurchaseReturnVoucherAmounts(purchaseReturn: $reference->purchaseReturn);
                    }
                }
            }
        }

        return $deletePayment;
    }
}
