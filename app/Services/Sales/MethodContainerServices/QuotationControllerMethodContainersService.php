<?php

namespace App\Services\Sales\MethodContainerServices;

use App\Enums\SaleStatus;
use App\Enums\SaleScreenType;
use App\Enums\DayBookVoucherType;
use App\Enums\AccountingVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\BooleanType;
use App\Enums\ProductLedgerVoucherType;
use App\Interfaces\Sales\QuotationControllerMethodContainersInterface;

class QuotationControllerMethodContainersService implements QuotationControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $quotationService,
        object $saleProductService,
    ): array {

        $data = [];
        $quotation = $quotationService->singleQuotation(id: $id, with: [
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
        ]);

        $data['customerCopySaleProducts'] = $saleProductService->customerCopySaleProducts(saleId: $quotation->id);
        $data['quotation'] = $quotation;

        return $data;
    }

    function editMethodContainer(
        int $id,
        object $quotationService,
        object $accountService,
        object $accountFilterService,
        object $paymentMethodService,
        object $priceGroupService,
        object $managePriceGroupService,
    ): array {

        $quotation = $quotationService->singleQuotation(id: $id, with: [
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.warehouse:id,warehouse_name,warehouse_code',
            'saleProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.unit.baseUnit:id,name,code_name,base_unit_id',
        ]);

        $generalSettings = config('generalSettings');
        $ownBranchIdOrParentBranchId = $quotation?->branch?->parent_branch_id ? $quotation?->branch?->parent_branch_id : $quotation->branch_id;

        $accounts = $accountService->accounts(with: [
            'bank:id,name',
            'group:id,sorting_number,sub_sub_group_number',
            'bankAccessBranch'
        ])->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('branch_id', $quotation->branch_id)
            ->whereIn('account_groups.sub_sub_group_number', [2])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
            ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])->get();

        $data['accounts'] = $accountFilterService->filterCashBankAccounts($accounts);

        $data['methods'] = $paymentMethodService->paymentMethods(with: ['paymentMethodSetting'])->get();

        $data['saleAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->where('accounts.branch_id', $quotation->branch_id)
            ->get(['accounts.id', 'accounts.name']);

        $data['taxAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            // ->where('accounts.branch_id', $quotation->branch_id)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['customerAccounts'] = $accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $data['priceGroups'] = $priceGroupService->priceGroups()->get(['id', 'name']);
        $data['priceGroupProducts'] = $managePriceGroupService->priceGroupProducts();
        $data['quotation'] = $quotation;

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $branchSettingService,
        object $saleService,
        object $quotationService,
        object $salesOrderService,
        object $quotationProductService,
        object $accountService,
        object $accountLedgerService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
        object $codeGenerator,
    ): ?array {

        $quotation = $quotationService->singleQuotation(id: $id, with: ['saleProducts', 'references']);

        $restrictions = $saleService->restrictions(request: $request, accountService: $accountService, checkCustomerChangeRestriction: true, saleId: $id);

        $quotationRestrictions = $quotationService->restrictions(request: $request, quotation: $quotation);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        } else if ($quotationRestrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $quotationRestrictions['msg']];
        }

        $generalSettings = config('generalSettings');
        $branchSetting = $branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);

        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];

        $salesOrderPrefix = isset($branchSetting) && $branchSetting?->sales_order_prefix ? $branchSetting?->sales_order_prefix : 'OR';


        $updateQuotation = $quotationService->updateQuotation(request: $request, updateQuotation: $quotation);

        $updateQuotationProducts = $quotationProductService->updateQuotationProducts(request: $request, quotation: $updateQuotation);

        $quotation = $quotationService->singleQuotation(id: $id, with: ['saleProducts']);

        $deletedUnusedQuotationProducts = $quotation->saleProducts()->where('is_delete_in_update', 1)->get();

        if (count($deletedUnusedQuotationProducts) > 0) {

            foreach ($deletedUnusedQuotationProducts as $deletedUnusedQuotationProduct) {

                $deletedUnusedQuotationProduct->delete();
            }
        }

        $updateQuotationStatus = $quotationService->updateQuotationStatus(request: $request, id: $id, codeGenerator: $codeGenerator, salesOrderPrefix: $salesOrderPrefix);

        if ($updateQuotationStatus->order_status == BooleanType::True->value) {

            $salesOrderService->calculateDeliveryLeftQty(order: $updateQuotationStatus);

            if ($request->received_amount) {

                $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Receipt->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $updateQuotationStatus->id);

                // Add Debit Account Accounting voucher Description
                $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

                //Add Debit Ledger Entry
                $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

                // Add Accounting VoucherDescription References
                $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$updateQuotationStatus->id]);

                //Add Credit Ledger Entry
                $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
            }
        }

        $saleService->adjustSaleInvoiceAmounts(sale: $quotation);

        return null;
    }
}
