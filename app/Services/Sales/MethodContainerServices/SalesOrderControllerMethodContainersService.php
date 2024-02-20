<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\DayBookVoucherType;
use App\Interfaces\Sales\SalesOrderControllerMethodContainersInterface;

class SalesOrderControllerMethodContainersService implements SalesOrderControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $salesOrderService,
        object $saleProductService,
    ): array {

        $data = [];
        $order = $salesOrderService->singleSalesOrder(id: $id, with: [
            'customer:id,name,phone,address',
            'createdBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.branch:id,name,branch_code,area_name,parent_branch_id',
            'saleProducts.branch.parentBranch:id,name,branch_code,area_name',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,base_unit_id,code_name',

            'references:id,voucher_description_id,sale_id,amount',
            'references.voucherDescription:id,accounting_voucher_id',
            'references.voucherDescription.accountingVoucher:id,voucher_no,date,voucher_type',
            'references.voucherDescription.accountingVoucher.voucherDescriptions:id,accounting_voucher_id,account_id,payment_method_id',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.paymentMethod:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.bank:id,name',
            'references.voucherDescription.accountingVoucher.voucherDescriptions.account.group:id,sub_sub_group_number',
        ]);

        $data['customerCopySaleProducts'] = $saleProductService->customerCopySaleProducts(saleId: $order->id);
        $data['order'] = $order;

        return $data;
    }

    public function editMethodContainer(
        int $id,
        object $salesOrderService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): array {

        $order = $salesOrderService->singleSalesOrder(id: $id, with: [
            'customer',
            'customer.group',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $generalSettings = config('generalSettings');
        $ownBranchIdOrParentBranchId = $order?->branch?->parent_branch_id ? $order?->branch?->parent_branch_id : $order->branch_id;

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch',
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $order->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['saleAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $order->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $order->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroups'] = $priceGroupService->priceGroups()->get(['id', 'name']);

        $data['priceGroupProducts'] = $managePriceGroupService->priceGroupProducts();

        $data['order'] = $order;

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $saleService,
        object $salesOrderService,
        object $salesOrderProductService,
        object $dayBookService,
        object $accountService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator,
    ): ?array {

        $restrictions = $saleService->restrictions(request: $request, accountService: $accountService, checkCustomerChangeRestriction: true, saleId: $id);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';
        $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'];

        $order = $salesOrderService->singleSalesOrder(id: $id, with: ['saleProducts']);

        $storedCurrSaleAccountId = $order->sale_account_id;
        $storedCurrCustomerAccountId = $order->customer_account_id;
        $storedCurrSaleTaxAccountId = $order->sale_tax_ac_id;

        $updateSalesOrder = $salesOrderService->updateSalesOrder(request: $request, updateSalesOrder: $order);

        // Update Day Book entry for Sale
        $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::SalesOrder->value, date: $request->date, accountId: $request->customer_account_id, transId: $updateSalesOrder->id, amount: $request->total_invoice_amount, amountType: 'debit');

        $updateSalesOrderProducts = $salesOrderProductService->updateSalesOrderProducts(request: $request, salesOrder: $updateSalesOrder);

        if ($request->received_amount > 0) {

            $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $updateSalesOrder->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$updateSalesOrder->id]);

            //Add Credit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $order = $salesOrderService->singleSalesOrder(id: $id, with: ['saleProducts']);

        $deletedUnusedSalesOrderProducts = $order->saleProducts()->where('is_delete_in_update', 1)->get();

        if (count($deletedUnusedSalesOrderProducts) > 0) {

            foreach ($deletedUnusedSalesOrderProducts as $deletedUnusedSalesOrderProduct) {

                $deletedUnusedSalesOrderProduct->delete();
            }
        }

        $saleService->adjustSaleInvoiceAmounts(sale: $order);

        $salesOrderService->calculateDeliveryLeftQty(order: $order);

        // Add user Log
        $userActivityLogUtil->addLog(action: 2, subject_type: 8, data_obj: $order);

        return null;
    }
}
