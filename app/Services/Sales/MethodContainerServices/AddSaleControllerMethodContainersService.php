<?php

namespace App\Services\Sales;

use App\Enums\SaleStatus;
use App\Enums\DayBookVoucherType;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Interfaces\Sales\AddSaleControllerMethodContainersInterface;

class AddSaleControllerMethodContainersService implements AddSaleControllerMethodContainersInterface{
    
    function storeMethodContainer(
        object $request,
        object $branchSettingService,
        object $saleService,
        object $saleProductService,
        object $dayBookService,
        object $accountLedgerService,
        object $productStockService,
        object $productLedgerService,
        object $purchaseProductService,
        object $accountingVoucherService,
        object $accountingVoucherDescriptionService,
        object $accountingVoucherDescriptionReferenceService,
        object $userActivityLogUtil,
    ): ?array {
        $generalSettings = config('generalSettings');
        $branchSetting = $branchSettingService->singleBranchSetting(branchId: auth()->user()->branch_id);
        $invoicePrefix = isset($branchSetting) && $branchSetting?->sale_invoice_prefix ? $branchSetting?->sale_invoice_prefix : $generalSettings['prefix__sale_invoice'];
        $quotationPrefix = isset($branchSetting) && $branchSetting?->quotation_prefix ? $branchSetting?->quotation_prefix : 'Q';
        $salesOrderPrefix = isset($branchSetting) && $branchSetting?->sales_order_prefix ? $branchSetting?->sales_order_prefix : 'OR';
        $receiptVoucherPrefix = isset($branchSetting) && $branchSetting?->receipt_voucher_prefix ? $branchSetting?->receipt_voucher_prefix : $generalSettings['prefix__receipt'];
        $stockAccountingMethod = $generalSettings['business__stock_accounting_method'];

        $restrictions = $saleService->restrictions(request: $request, accountService: $accountService);
        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        $addSale = $saleService->addSale(request: $request, saleScreenType: SaleScreenType::AddSale->value, codeGenerator: $codeGenerator, invoicePrefix: $invoicePrefix, quotationPrefix: $quotationPrefix, salesOrderPrefix: $salesOrderPrefix);

        if ($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) {

            $dayBookVoucherType = $request->status == SaleStatus::Final->value ? DayBookVoucherType::Sales->value : DayBookVoucherType::SalesOrder->value;

            // Add Day Book entry for Final Sale or Sales Order
            $dayBookService->addDayBook(voucherTypeId: $dayBookVoucherType, date: $request->date, accountId: $request->customer_account_id, transId: $addSale->id, amount: $request->total_invoice_amount, amountType: 'debit');
        }

        if ($request->status == SaleStatus::Final->value) {

            // Add Sale A/c Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $request->date, account_id: $request->sale_account_id, trans_id: $addSale->id, amount: $request->sales_ledger_amount, amount_type: 'credit');

            // Add supplier A/c ledger Entry For Purchase
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->customer_account_id, date: $request->date, trans_id: $addSale->id, amount: $request->total_invoice_amount, amount_type: 'debit');


            if ($request->sale_tax_ac_id) {

                // Add Tax A/c ledger Entry For Purchase
                $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $request->sale_tax_ac_id, date: $request->date, trans_id: $addSale->id, amount: $request->order_tax_amount, amount_type: 'credit');
            }
        }

        $index = 0;
        foreach ($request->product_ids as $productId) {

            $addSaleProduct = $saleProductService->addSaleProduct(request: $request, sale: $addSale, index: $index);

            if ($request->status == SaleStatus::Final->value) {

                // Add Product Ledger Entry
                $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $request->date, productId: $productId, transId: $addSaleProduct->id, rate: $addSaleProduct->unit_price_inc_tax, quantityType: 'out', quantity: $addSaleProduct->quantity, subtotal: $addSaleProduct->subtotal, variantId: $addSaleProduct->variant_id, warehouseId: (isset($request->warehouse_count) ? $request->warehouse_id : null));

                if ($addSaleProduct->tax_ac_id) {

                    // Add Tax A/c ledger Entry
                    $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType->SaleProductTax->value, date: $request->date, account_id: $addSaleProduct->tax_ac_id, trans_id: $addSaleProduct->id, amount: ($addSaleProduct->unit_tax_amount * $addSaleProduct->quantity), amount_type: 'credit');
                }
            }

            $index++;
        }

        if (($request->status == SaleStatus::Final->value || $request->status == SaleStatus::Order->value) && $request->received_amount > 0) {

            $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $request->date, voucherType: AccountingVoucherType::Payment->value, remarks: $request->payment_note, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, totalAmount: $request->received_amount, saleRefId: $addSale->id);

            // Add Debit Account Accounting voucher Description
            $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount);

            //Add Debit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            // Add Payment Description Credit Entry
            $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $request->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, note: $request->payment_note);

            // Add Accounting VoucherDescription References
            $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $request->customer_account_id, amount: $request->received_amount, refIdColName: 'sale_id', refIds: [$addSale->id]);

            //Add Credit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $request->date, account_id: $request->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', cash_bank_account_id: $request->account_id);
        }

        $sale = $this->saleService->singleSale(
            id: $addSale->id,
            with: [
                'branch',
                'branch.parentBranch',
                'branch.branchSetting:id,add_sale_invoice_layout_id',
                'branch.branchSetting.addSaleInvoiceLayout',
                'customer',
                'saleProducts',
                'saleProducts.product',
            ]
        );

        if ($sale->due > 0) {

            $accountingVoucherDescriptionReferenceService->invoiceOrVoucherDueAmountAutoDistribution(accountId: $request->customer_account_id, accountingVoucherType: AccountingVoucherType::Receipt->value, refIdColName: 'sale_id', sale: $sale);
        }

        if ($request->status == SaleStatus::Final->value) {

            $__index = 0;
            foreach ($request->product_ids as $productId) {

                $variantId = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $productStockService->adjustMainProductAndVariantStock(productId: $productId, variantId: $variantId);

                if (isset($request->warehouse_ids[$__index])) {

                    $productStockService->adjustWarehouseStock(productId: $productId, variantId: $variantId, warehouseId: $request->warehouse_ids[$__index]);
                } else {

                    $productStockService->adjustBranchStock($productId, $variantId, branchId: auth()->user()->branch_id);
                }

                $purchaseProductService->addPurchaseSaleProductChain($sale, $stockAccountingMethod);

                $__index++;
            }
        }

        $customerCopySaleProducts = $saleProductService->customerCopySaleProducts(saleId: $sale->id);

        // $this->userActivityLogUtil->addLog(action: 1, subject_type: $request->status == 1 ? 7 : 8, data_obj: $sale);

        return ['sale' => $sale, 'customerCopySaleProducts' => $customerCopySaleProducts];
    }
}
