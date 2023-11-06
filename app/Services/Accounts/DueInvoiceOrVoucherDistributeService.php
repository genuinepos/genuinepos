<?php

namespace App\Services\Accounts;

use App\Models\Accounts\AccountingVoucherDescriptionReference;
use Illuminate\Support\Facades\DB;

class DueInvoiceOrVoucherDistributeService
{
    public function invoiceOrVoucherDueAmountAutoDistribution(
        int $accountId,
        int $accountingVoucherType,
        int $refIdColName,
        object $purchase = null,
        object $sale = null,
    ): void {

        $saleService = new \App\Services\Sales\SaleService();
        $purchaseService = new \App\Services\Purchases\PurchaseService();

        $dueAmount = $refIdColName == 'purchase_id' ? $purchase->due : $sale->due;

        $voucherDescriptions = DB::table('accounting_voucher_descriptions')
            ->leftJoin('voucher_description_references', 'accounting_voucher_descriptions.id', 'voucher_description_references.voucher_description_id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id')
            ->where('accounting_voucher_descriptions.account_id', $accountId)
            ->where('accounting_vouchers.voucher_type', $accountingVoucherType)
            ->select(
                'accounting_vouchers.id as accounting_voucher_id',
                'accounting_voucher_descriptions.id as voucher_description_id',
                // 'supplier_payments.account_id',
                // 'supplier_payments.date',
                // 'supplier_payments.voucher_no',
                'accounting_voucher_descriptions.amount as received_or_paid_amount',
                // DB::raw('SUM(supplier_payment_invoices.paid_amount) as total_invoice_paid_amount'),
                // DB::raw('SUM(- IFNULL(supplier_payment_invoices.paid_amount, 0)) + supplier_payments.paid_amount as left_amount')
                DB::raw('SUM(- IFNULL(voucher_description_references.amount, 0)) + accounting_voucher_descriptions.amount as left_amount')
            )
            ->having('left_amount', '!=', 0)
            ->groupBy('accounting_vouchers.id')
            ->groupBy('accounting_voucher_descriptions.id')
            ->groupBy('accounting_voucher_descriptions.amount')
            ->get();

        foreach ($voucherDescriptions as $voucherDescription) {

            if ($dueAmount > $voucherDescription->left_amount) {

                if ($dueAmount > 0) {

                    $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                    $addAccountingVoucherDescriptionRef->voucher_description_id = $voucherDescription->voucher_description_id;
                    $addAccountingVoucherDescriptionRef->{$refIdColName} = $refIdColName == 'purchase_id' ? $purchase->id : $sale->id;
                    $addAccountingVoucherDescriptionRef->amount = $voucherDescription->left_amount;
                    $addAccountingVoucherDescriptionRef->save();

                    $dueAmount -= $voucherDescription->left_amount;

                    if ($refIdColName == 'sale_id') {

                        $saleService->adjustSaleInvoiceAmounts($sale);
                    } elseif ($refIdColName == 'purchase_id') {

                        $purchaseService->adjustPurchaseInvoiceAmounts($purchase);
                    }
                } else {

                    break;
                }
            } elseif ($dueAmount == $voucherDescription->left_amount) {

                if ($dueAmount > 0) {

                    $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                    $addAccountingVoucherDescriptionRef->voucher_description_id = $voucherDescription->voucher_description_id;
                    $addAccountingVoucherDescriptionRef->{$refIdColName} = $refIdColName == 'purchase_id' ? $purchase->id : $sale->id;
                    $addAccountingVoucherDescriptionRef->amount = $voucherDescription->left_amount;
                    $addAccountingVoucherDescriptionRef->save();

                    $dueAmount -= $voucherDescription->left_amount;

                    if ($refIdColName == 'sale_id') {

                        $saleService->adjustSaleInvoiceAmounts($sale);
                    } elseif ($refIdColName == 'purchase_id') {

                        $purchaseService->adjustPurchaseInvoiceAmounts($purchase);
                    }
                } else {

                    break;
                }
            } elseif ($dueAmount < $voucherDescription->left_amount) {

                if ($dueAmount > 0) {

                    $addAccountingVoucherDescriptionRef = new AccountingVoucherDescriptionReference();
                    $addAccountingVoucherDescriptionRef->voucher_description_id = $voucherDescription->voucher_description_id;
                    $addAccountingVoucherDescriptionRef->{$refIdColName} = $refIdColName == 'purchase_id' ? $purchase->id : $sale->id;
                    $addAccountingVoucherDescriptionRef->amount = $dueAmount;
                    $addAccountingVoucherDescriptionRef->save();

                    $dueAmount -= $dueAmount;

                    if ($refIdColName == 'sale_id') {

                        $saleService->adjustSaleInvoiceAmounts($sale);
                    } elseif ($refIdColName == 'purchase_id') {

                        $purchaseService->adjustPurchaseInvoiceAmounts($purchase);
                    }
                } else {

                    break;
                }
            }
        }
    }
}
