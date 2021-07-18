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
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $addSale->id;
                    $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->paid_amount = $request->total_invoice_payable;
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
                        $account->credit = $account->credit + $request->total_invoice_payable;
                        $account->balance = $account->balance + $request->total_invoice_payable;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $request->total_invoice_payable;
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
                                    $addSalePayment = new SalePayment();
                                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                    $addSalePayment->sale_id = $dueInvoice->id;
                                    $addSalePayment->customer_id = $request->customer_id;
                                    $addSalePayment->account_id = $request->account_id;
                                    $addSalePayment->paid_amount = $dueAmounts;
                                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                                    $addSalePayment->time = date('h:i:s a');
                                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                                    $addSalePayment->month = date('F');
                                    $addSalePayment->year = date('Y');
                                    $addSalePayment->pay_mode = $request->payment_method;

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

                                    if ($request->hasFile('attachment')) {
                                        $SalePaymentAttachment = $request->file('attachment');
                                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $SalePaymentAttachment->getClientOriginalExtension();
                                        $SalePaymentAttachment->move(public_path('uploads/payment_attachment/'), $SalePaymentAttachment);
                                        $addSalePayment->attachment = $salePaymentAttachmentName;
                                    }

                                    $addSalePayment->admin_id = auth()->user()->id;
                                    $addSalePayment->payment_on = 1;
                                    $addSalePayment->save();

                                    if ($request->account_id) {
                                        // update account
                                        $account = Account::where('id', $request->account_id)->first();
                                        $account->credit = $account->credit + $dueAmounts;
                                        $account->balance = $account->balance + $dueAmounts;
                                        $account->save();

                                        // Add cash flow
                                        $addCashFlow = new CashFlow();
                                        $addCashFlow->account_id = $request->account_id;
                                        $addCashFlow->credit = $dueAmounts;
                                        $addCashFlow->balance = $account->balance;
                                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                                        $addCashFlow->transaction_type = 2;
                                        $addCashFlow->cash_type = 2;
                                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                                        $addCashFlow->month = date('F');
                                        $addCashFlow->year = date('Y');
                                        $addCashFlow->admin_id = auth()->user()->id;
                                        $addCashFlow->save();
                                    }

                                    if ($dueInvoice->customer_id) {
                                        $addCustomerLedger = new CustomerLedger();
                                        $addCustomerLedger->customer_id = $request->customer_id;
                                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                        $addCustomerLedger->row_type = 2;
                                        $addCustomerLedger->save();
                                    }

                                    //$dueAmounts -= $dueAmounts; 
                                    if ($index == 1) {
                                        break;
                                    }
                                } elseif ($dueInvoice->due == $dueAmounts) {
                                    $dueInvoice->paid = $dueInvoice->paid + $dueAmounts;
                                    $dueInvoice->due = $dueInvoice->due - $dueAmounts;
                                    $dueInvoice->save();
                                    $addSalePayment = new SalePayment();
                                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                    $addSalePayment->sale_id = $dueInvoice->id;
                                    $addSalePayment->customer_id = $request->customer_id;
                                    $addSalePayment->account_id = $request->account_id;
                                    $addSalePayment->paid_amount = $dueAmounts;
                                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                                    $addSalePayment->time = date('h:i:s a');
                                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                                    $addSalePayment->month = date('F');
                                    $addSalePayment->year = date('Y');
                                    $addSalePayment->pay_mode = $request->payment_method;

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

                                    if ($request->hasFile('attachment')) {
                                        $salePaymentAttachment = $request->file('attachment');
                                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
                                        $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
                                        $addSalePayment->attachment = $salePaymentAttachmentName;
                                    }

                                    $addSalePayment->admin_id = auth()->user()->id;
                                    $addSalePayment->payment_on = 1;
                                    $addSalePayment->save();

                                    if ($request->account_id) {
                                        // update account
                                        $account = Account::where('id', $request->account_id)->first();
                                        $account->credit = $account->credit + $dueAmounts;
                                        $account->balance = $account->balance + $dueAmounts;
                                        $account->save();

                                        // Add cash flow
                                        $addCashFlow = new CashFlow();
                                        $addCashFlow->account_id = $request->account_id;
                                        $addCashFlow->credit = $dueAmounts;
                                        $addCashFlow->balance = $account->balance;
                                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                                        $addCashFlow->transaction_type = 2;
                                        $addCashFlow->cash_type = 2;
                                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                                        $addCashFlow->month = date('F');
                                        $addCashFlow->year = date('Y');
                                        $addCashFlow->admin_id = auth()->user()->id;
                                        $addCashFlow->save();
                                    }

                                    if ($dueInvoice->customer_id) {
                                        $addCustomerLedger = new CustomerLedger();
                                        $addCustomerLedger->customer_id = $request->customer_id;
                                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                        $addCustomerLedger->row_type = 2;
                                        $addCustomerLedger->save();
                                    }

                                    if ($index == 1) {
                                        break;
                                    }
                                } elseif ($dueInvoice->due < $dueAmounts) {
                                    $addSalePayment = new SalePayment();
                                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                    $addSalePayment->sale_id = $dueInvoice->id;
                                    $addSalePayment->customer_id = $request->customer_id;
                                    $addSalePayment->account_id = $request->account_id;
                                    $addSalePayment->paid_amount = $dueInvoice->due;
                                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                                    $addSalePayment->time = date('h:i:s a');
                                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                                    $addSalePayment->month = date('F');
                                    $addSalePayment->year = date('Y');
                                    $addSalePayment->pay_mode = $request->payment_method;

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

                                    if ($request->hasFile('attachment')) {
                                        $salePaymentAttachment = $request->file('attachment');
                                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
                                        $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
                                        $addSalePayment->attachment = $salePaymentAttachmentName;
                                    }

                                    $addSalePayment->admin_id = auth()->user()->id;
                                    $addSalePayment->payment_on = 1;
                                    $addSalePayment->save();

                                    if ($request->account_id) {
                                        // update account
                                        $account = Account::where('id', $request->account_id)->first();
                                        $account->credit = $account->credit + $dueInvoice->due;
                                        $account->balance = $account->balance + $dueInvoice->due;
                                        $account->save();

                                        // Add cash flow
                                        $addCashFlow = new CashFlow();
                                        $addCashFlow->account_id = $request->account_id;
                                        $addCashFlow->credit = $dueInvoice->due;
                                        $addCashFlow->balance = $account->balance;
                                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                                        $addCashFlow->transaction_type = 2;
                                        $addCashFlow->cash_type = 2;
                                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
                                        $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                                        $addCashFlow->month = date('F');
                                        $addCashFlow->year = date('Y');
                                        $addCashFlow->admin_id = auth()->user()->id;
                                        $addCashFlow->save();
                                    }

                                    if ($dueInvoice->customer_id) {
                                        $addCustomerLedger = new CustomerLedger();
                                        $addCustomerLedger->customer_id = $request->customer_id;
                                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                        $addCustomerLedger->row_type = 2;
                                        $addCustomerLedger->save();
                                    }

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
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $paidAmount;
                    $addSalePayment->customer_id = $request->customer_id;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $paidAmount;
                    $addSalePayment->date = date('d-m-Y', strtotime($request->date));
                    $addSalePayment->time = date('h:i:s a');
                    $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->pay_mode = $request->payment_method;

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

                    if ($request->hasFile('attachment')) {
                        $salePaymentAttachment = $request->file('attachment');
                        $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
                        $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
                        $addSalePayment->attachment = $salePaymentAttachmentName;
                    }

                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->payment_on = 1;
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->credit = $account->credit + $paidAmount;
                        $account->balance = $account->balance - $paidAmount;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $paidAmount;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y', strtotime($request->date));
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
            } else {
                $addSalePayment = new SalePayment();
                $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                $addSalePayment->sale_id = $addSale->id;
                $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                $addSalePayment->account_id = $request->account_id;
                $addSalePayment->pay_mode = $request->payment_method;
                $addSalePayment->paid_amount = $paidAmount;
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
                    $account->credit = $account->credit + $paidAmount;
                    $account->balance = $account->balance + $paidAmount;
                    $account->save();

                    // Add cash flow
                    $addCashFlow = new CashFlow();
                    $addCashFlow->account_id = $request->account_id;
                    $addCashFlow->credit = $paidAmount;
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
}
