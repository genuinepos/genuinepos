<?php

namespace App\Utils;

use App\Models\Purchase;
use App\Utils\PurchaseUtil;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierPaymentInvoice;
use App\Utils\InvoiceVoucherRefIdUtil;

class SupplierPaymentUtil
{
    public $purchaseUtil;
    public $invoiceVoucherRefIdUtil;

    public function __construct(PurchaseUtil $purchaseUtil, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,)
    {
        $this->purchaseUtil = $purchaseUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function specificPurchaseOrOrderByPayment($request, $supplierPayment, $supplierId, $paymentInvoicePrefix)
    {
        $dueInvoices = Purchase::where('supplier_id', $supplierId)
            ->where('branch_id', auth()->user()->branch_id)
            ->whereIn('id', $request->purchase_ids)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($dueInvoices) > 0) {

            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {

                if ($dueInvoice->due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                        //$dueAmounts -= $dueAmounts; 
                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $request->paying_amount) {

                    if ($dueInvoice->due > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $dueInvoice->due);

                        // Calculate next payment amount
                        $request->paying_amount -= $dueInvoice->due;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                }
                $index++;
            }
        }

        if ($request->paying_amount > 0) {

            $dueInvoices = Purchase::where('supplier_id', $supplierId)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('due', '>', 0)
                ->orderBy('report_date', 'asc')
                ->get();

            if (count($dueInvoices) > 0) {

                $index = 0;
                foreach ($dueInvoices as $dueInvoice) {

                    if ($dueInvoice->due > $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                            $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                            //$dueAmounts -= $dueAmounts; 
                            $request->paying_amount -= $request->paying_amount;
                            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                        }
                    } elseif ($dueInvoice->due == $request->paying_amount) {

                        if ($request->paying_amount > 0) {

                            $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                            $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                            $request->paying_amount -= $request->paying_amount;
                            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                        }
                    } elseif ($dueInvoice->due < $request->paying_amount) {

                        if ($dueInvoice->due > 0) {

                            $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                            $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $dueInvoice->due);

                            // Calculate next payment amount
                            $request->paying_amount -= $dueInvoice->due;
                            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                        }
                    }

                    $index++;
                }
            }
        }
    }

    public function randomPurchaseOrOrderPayment($request, $supplierPayment, $supplierId, $paymentInvoicePrefix)
    {
        $dueInvoices = Purchase::where('supplier_id', $supplierId)
            ->where('branch_id', auth()->user()->branch_id)
            ->where('due', '>', 0)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($dueInvoices) > 0) {

            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {

                if ($dueInvoice->due > $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);
                        //$dueAmounts -= $dueAmounts; 
                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $request->paying_amount) {

                    if ($request->paying_amount > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $request->paying_amount);

                        // Add Supplier Payment invoice
                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $request->paying_amount);

                        $request->paying_amount -= $request->paying_amount;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $request->paying_amount) {

                    if ($dueInvoice->due > 0) {

                        $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $dueInvoice->due);

                        // Add Supplier Payment invoice
                        $this->supplierPaymentInvoice($supplierPayment, $dueInvoice, $dueInvoice->due);

                        // Calculate next payment amount
                        $request->paying_amount -= $dueInvoice->due;
                        $this->purchaseUtil->adjustPurchaseInvoiceAmounts($dueInvoice);
                    }
                }

                $index++;
            }
        }
    }

    public function purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $dueInvoice, $payingAmount)
    {
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_payments'), 5, "0", STR_PAD_LEFT);
        $addPurchasePayment->purchase_id = $dueInvoice->id;
        $addPurchasePayment->branch_id = auth()->user()->branch_id;
        $addPurchasePayment->supplier_payment_id = $supplierPayment->id;
        $addPurchasePayment->account_id = $request->account_id;
        $addPurchasePayment->paid_amount = $payingAmount;
        $addPurchasePayment->date = $request->date;
        $addPurchasePayment->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchasePayment->month = date('F');
        $addPurchasePayment->year = date('Y');
        $addPurchasePayment->payment_method_id = $request->payment_method_id;
        $addPurchasePayment->admin_id = auth()->user()->id;
        $addPurchasePayment->payment_on = 1;
        $addPurchasePayment->save();
    }

    public function supplierPaymentInvoice($supplierPayment, $dueInvoice, $payingAmount)
    {
        // Add Supplier Payment invoice
        $addSupplierPaymentInvoice = new SupplierPaymentInvoice();
        $addSupplierPaymentInvoice->supplier_payment_id = $supplierPayment->id;
        $addSupplierPaymentInvoice->purchase_id = $dueInvoice->id;
        $addSupplierPaymentInvoice->paid_amount = $payingAmount;
        $addSupplierPaymentInvoice->save();
    }

    public function distributePurchaseDueAmount($request, $purchase, $paymentInvoicePrefix)
    {
        $supplierPayments = DB::table('supplier_payments')
            ->where('supplier_payments.supplier_id', $purchase->supplier_id)
            ->leftJoin('supplier_payment_invoices', 'supplier_payments.id', 'supplier_payment_invoices.supplier_payment_id')
            ->select(
                'supplier_payments.id',
                'supplier_payments.payment_method_id',
                'supplier_payments.account_id',
                'supplier_payments.date',
                'supplier_payments.voucher_no',
                'supplier_payments.paid_amount',
                // DB::raw('SUM(supplier_payment_invoices.paid_amount) as total_invoice_paid_amount'),
                DB::raw('SUM(- IFNULL(supplier_payment_invoices.paid_amount, 0)) + supplier_payments.paid_amount as left_amount')
            )
            ->having('left_amount', '!=', 0)
            ->groupBy('supplier_payments.id')
            ->groupBy('supplier_payments.voucher_no')
            ->groupBy('supplier_payment_invoices.supplier_payment_id')
            ->get();

        foreach ($supplierPayments as $supplierPayment) {

            $request->payment_method_id = $supplierPayment->payment_method_id;
            $request->account_id = $supplierPayment->account_id;
            $request->date = $supplierPayment->date;

            if ($purchase->due > $supplierPayment->left_amount) {

                if ($purchase->due > 0) {

                    $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $purchase, $supplierPayment->left_amount);

                    $this->supplierPaymentInvoice($supplierPayment, $purchase, $supplierPayment->left_amount);

                    //$dueAmounts -= $dueAmounts; 
                    $purchase->due -= $supplierPayment->left_amount;
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                }else {

                    break;
                }
            } elseif ($purchase->due == $supplierPayment->left_amount) {

                if ($purchase->due > 0) {

                    $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $purchase, $supplierPayment->left_amount);

                    $this->supplierPaymentInvoice($supplierPayment, $purchase, $supplierPayment->left_amount);

                    $purchase->due -= $supplierPayment->left_amount;
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                }else {

                    break;
                }
            } elseif ($purchase->due < $supplierPayment->left_amount) {

                if ($purchase->due > 0) {

                    $this->purchaseDueFillupBySupplierPayment($request, $supplierPayment, $paymentInvoicePrefix, $purchase, $purchase->due);

                    $this->supplierPaymentInvoice($supplierPayment, $purchase, $purchase->due);

                    // Calculate next payment amount
                    $purchase->due -= $purchase->due;
                    $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);
                }else {

                    break;
                }
            }
        }
    }
}
