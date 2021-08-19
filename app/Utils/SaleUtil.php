<?php

namespace App\Utils;

use App\Models\Sale;
use App\Models\Account;
use App\Models\Product;
use App\Models\CashFlow;
use App\Models\SalePayment;
use App\Models\ProductBranch;
use App\Models\CustomerLedger;
use App\Models\ProductVariant;
use App\Models\ProductBranchVariant;

class SaleUtil
{
    public function __getSalePaymentForAddSaleStore($request, $addSale, $paymentInvoicePrefix, $invoiceId)
    {
        if ($request->paying_amount > 0) {
            $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0.00;
            $paidAmount = $request->paying_amount - $changedAmount;

            if ($request->previous_due > 0) {
                if ($paidAmount >= $request->total_invoice_payable) {
                    $this->addPayment($paymentInvoicePrefix, $request, $request->total_invoice_payable, $invoiceId, $addSale->id);
                    $payingPreviousDue = $paidAmount - $request->total_invoice_payable;
                    if ($payingPreviousDue > 0) {
                        $dueAmounts = $payingPreviousDue;
                        $dueInvoices = Sale::where('customer_id', $request->customer_id)
                            ->where('due', '>', 0)
                            ->get();
                        if (count($dueInvoices) > 0) {
                            $index = 0;
                            foreach ($dueInvoices as $dueInvoice) {
                                if ($dueInvoice->due > $dueAmounts) {
                                    $dueInvoice->paid = $dueInvoice->paid + $dueAmounts;
                                    $dueInvoice->due = $dueInvoice->due - $dueAmounts;
                                    $dueInvoice->save();
                                    $this->addPayment($paymentInvoicePrefix, $request, $dueAmounts, $invoiceId, $dueInvoice->id);
                                    //$dueAmounts -= $dueAmounts; 
                                    if ($index == 1) {
                                        break;
                                    }
                                } elseif ($dueInvoice->due == $dueAmounts) {
                                    $dueInvoice->paid = $dueInvoice->paid + $dueAmounts;
                                    $dueInvoice->due = $dueInvoice->due - $dueAmounts;
                                    $dueInvoice->save();
                                    $this->addPayment($paymentInvoicePrefix, $request, $dueAmounts, $invoiceId, $dueInvoice->id);
                                    if ($index == 1) {
                                        break;
                                    }
                                } elseif ($dueInvoice->due < $dueAmounts) {
                                    $this->addPayment($paymentInvoicePrefix, $request, $dueInvoice->due, $invoiceId, $dueInvoice->id);
                                    $dueAmounts = $dueAmounts - $dueInvoice->due;
                                    $dueInvoice->paid = $dueInvoice->paid + $dueInvoice->due;
                                    $dueInvoice->due = $dueInvoice->due - $dueInvoice->due;
                                    $dueInvoice->save();
                                }
                                $index++;
                            }
                        }
                    }
                } elseif ($paidAmount < $request->invoice_payable_amount) {
                    $this->addPayment($paymentInvoicePrefix, $request, $paidAmount, $invoiceId, $addSale->id);
                }
            } else {
                $this->addPayment($paymentInvoicePrefix, $request, $paidAmount, $invoiceId, $addSale->id);
            }
        }
    }

    public function updateProductBranchStock($request, $branch_id)
    {
        // update product quantity
        $quantities = $request->quantities;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;

        $index = 0;
        foreach ($product_ids as $product_id) {
            // Update Branch product stock
            if ($branch_id) {
                $updateProductQty = Product::where('id', $product_id)->first();
                if ($updateProductQty->type == 1) {
                    $updateProductQty->quantity -= (float)$quantities[$index];
                    $updateProductQty->number_of_sale -= (float)$quantities[$index];
                    $updateProductQty->save();

                    $updateBranchProductQty = ProductBranch::where('branch_id', $branch_id)
                        ->where('product_id', $product_id)->first();
                    $updateBranchProductQty->product_quantity -= (float)$quantities[$index];
                    $updateBranchProductQty->save();

                    if ($variant_ids[$index] != 'noid') {
                        $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])
                            ->where('product_id', $product_id)->first();
                        $updateProductVariant->variant_quantity -= (float)$quantities[$index];
                        $updateProductVariant->number_of_sale += (float)$quantities[$index];
                        $updateProductVariant->save();

                        $updateProductBranchVariant = ProductBranchVariant::where('product_branch_id', $updateBranchProductQty->id)
                            ->where('product_id', $product_id)
                            ->where('product_variant_id', $variant_ids[$index])
                            ->first();
                        $updateProductBranchVariant->variant_quantity -= (float)$quantities[$index];
                        $updateProductBranchVariant->save();
                    }
                }
            } else {
                $updateProductQty = Product::where('id', $product_id)->first();
                if ($updateProductQty->type == 1) {
                    $updateProductQty->quantity -= (float)$quantities[$index];
                    $updateProductQty->number_of_sale += (float)$quantities[$index];
                    $updateProductQty->mb_stock -= (float)$quantities[$index];
                    $updateProductQty->save();

                    if ($variant_ids[$index] != 'noid') {
                        $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])
                            ->where('product_id', $product_id)
                            ->first();

                        $updateProductVariant->variant_quantity -= (float)$quantities[$index];
                        $updateProductVariant->number_of_sale += (float)$quantities[$index];

                        $updateProductVariant->mb_stock -= (float)$quantities[$index];
                        $updateProductVariant->save();
                    }
                }
            }
        }
    }

    // Add sale add payment util method
    public function addPayment($invoicePrefix, $request, $payingAmount, $invoiceId, $saleId)
    {
        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = ($invoicePrefix != null ? $invoicePrefix : 'SPI') . date('ymd') . $invoiceId;
        $addSalePayment->sale_id = $saleId;
        $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
        $addSalePayment->account_id = $request->account_id;
        $addSalePayment->pay_mode = $request->payment_method;
        $addSalePayment->paid_amount = $payingAmount;
        $addSalePayment->date = $request->date;
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->note = $request->payment_note;

        if ($request->payment_method == 'Card') {
            $addSalePayment->card_no = $request->card_no;
            $addSalePayment->card_holder = $request->card_holder_name;
            $addSalePayment->card_transaction_no = $request->card_transaction_no;
            $addSalePayment->card_type = $request->card_type;
            $addSalePayment->card_month = $request->month;
            $addSalePayment->card_year = $request->year;
            $addSalePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addSalePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addSalePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addSalePayment->transaction_no = $request->transaction_no;
        }

        $addSalePayment->admin_id = auth()->user()->id;
        $addSalePayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->credit = $account->credit + $payingAmount;
            $account->balance = $account->balance + $payingAmount;
            $account->save();

            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->credit = $payingAmount;
            $addCashFlow->balance = $account->balance;
            $addCashFlow->sale_payment_id = $addSalePayment->id;
            $addCashFlow->transaction_type = 2;
            $addCashFlow->cash_type = 2;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
        }

        if ($request->customer_id) {
            $addCustomerLedger = new CustomerLedger();
            $addCustomerLedger->customer_id = $request->customer_id;
            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
            $addCustomerLedger->row_type = 2;
            $addCustomerLedger->save();
        }
    }
}