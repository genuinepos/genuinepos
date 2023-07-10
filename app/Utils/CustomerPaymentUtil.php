<?php

namespace App\Utils;

use App\Models\Sale;
use App\Utils\SaleUtil;
use App\Models\SalePayment;
use App\Models\CustomerPayment;
use Illuminate\Support\Facades\Log;
use App\Models\CustomerPaymentInvoice;
use App\Utils\InvoiceVoucherRefIdUtil;

class CustomerPaymentUtil
{
    public $saleUtil;

    public function __construct(SaleUtil $saleUtil, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->saleUtil = $saleUtil;
    }

    public function addCustomerPayment(
        $customerId,
        $accountId,
        $receivedAmount,
        $paymentMethodId,
        $date,
        $invoiceVoucherRefIdUtil,
        $lessAmount = 0,
        $attachment = null,
        $reference = null,
        $note = null,

    ) {
        $voucherNo = 'CRV' . str_pad($invoiceVoucherRefIdUtil->getLastId('customer_payments'), 5, "0", STR_PAD_LEFT);
        $customerPayment = new CustomerPayment();
        $customerPayment->voucher_no = $voucherNo;
        $customerPayment->reference = $reference;
        $customerPayment->branch_id = auth()->user()->branch_id;
        $customerPayment->customer_id = $customerId;
        $customerPayment->account_id = $accountId;
        $customerPayment->paid_amount = $receivedAmount;
        $customerPayment->less_amount = $lessAmount;
        $customerPayment->payment_method_id = $paymentMethodId;
        $customerPayment->report_date = date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $customerPayment->date = $date;
        $customerPayment->time = date('h:i:s a');
        $customerPayment->month = date('F');
        $customerPayment->year = date('Y');

        if ($attachment) {

            $paymentAttachment = $attachment;
            $paymentAttachmentName = uniqid() . '-' . '.' . $paymentAttachment->getClientOriginalExtension();
            $paymentAttachment->move(public_path('uploads/payment_attachment/'), $paymentAttachmentName);
            $customerPayment->attachment = $paymentAttachmentName;
        }

        $customerPayment->note = $note;
        $customerPayment->save();

        return $customerPayment;
    }

    public function specificInvoiceOrOrderByPayment($saleIds, $receivedAmount, $customerPayment, $customerId, $receiptVoucherPrefix, $paymentMethodId, $accountId, $date, $invoiceVoucherRefIdUtil)
    {
        $dueInvoices = Sale::where('customer_id', $customerId)
            ->whereIn('id', $saleIds)
            ->orderBy('report_date', 'asc')
            ->get();

        Log::info($dueInvoices);

        if (count($dueInvoices) > 0) {

            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {

                if ($dueInvoice->due > $receivedAmount) {

                    if ($receivedAmount > 0) {

                        $this->saleOrSalesOrderFillUpByPayment(
                            customerPayment: $customerPayment,
                            customerId: $customerId,
                            receiptVoucherPrefix: $receiptVoucherPrefix,
                            dueInvoice: $dueInvoice,
                            receivedAmount: $receivedAmount,
                            paymentMethodId: $paymentMethodId,
                            accountId: $accountId,
                            date: $date,
                            invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                        );

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $receivedAmount);

                        $receivedAmount -= $receivedAmount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due ==  $receivedAmount) {

                    if ($receivedAmount > 0) {

                        $this->saleOrSalesOrderFillUpByPayment(
                            customerPayment: $customerPayment,
                            customerId: $customerId,
                            receiptVoucherPrefix: $receiptVoucherPrefix,
                            dueInvoice: $dueInvoice,
                            receivedAmount: $receivedAmount,
                            paymentMethodId: $paymentMethodId,
                            accountId: $accountId,
                            date: $date,
                            invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                        );

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $receivedAmount);

                        $receivedAmount -=  $receivedAmount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $receivedAmount) {

                    if ($dueInvoice->due > 0) {

                        $this->saleOrSalesOrderFillUpByPayment(
                            customerPayment: $customerPayment,
                            customerId: $customerId,
                            receiptVoucherPrefix: $receiptVoucherPrefix,
                            dueInvoice: $dueInvoice,
                            receivedAmount: $dueInvoice->due,
                            paymentMethodId: $paymentMethodId,
                            accountId: $accountId,
                            date: $date,
                            invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                        );

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $dueInvoice->due);

                        $receivedAmount -= $dueInvoice->due;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                }

                $index++;
            }
        }

        if ($receivedAmount > 0) {

            $dueInvoices = Sale::where('customer_id', $customerId)
                ->where('branch_id', auth()->user()->branch_id)
                ->where('due', '>', 0)
                ->orderBy('report_date', 'asc')
                ->get();

            if (count($dueInvoices) > 0) {

                $index = 0;
                foreach ($dueInvoices as $dueInvoice) {

                    if ($dueInvoice->due > $receivedAmount) {

                        if ($receivedAmount > 0) {

                            $this->saleOrSalesOrderFillUpByPayment(
                                customerPayment: $customerPayment,
                                customerId: $customerId,
                                receiptVoucherPrefix: $receiptVoucherPrefix,
                                dueInvoice: $dueInvoice,
                                receivedAmount: $receivedAmount,
                                paymentMethodId: $paymentMethodId,
                                accountId: $accountId,
                                date: $date,
                                invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                            );

                            // Add Customer Payment invoice
                            $this->customerPaymentInvoice($customerPayment, $dueInvoice, $receivedAmount);

                            $receivedAmount -=  $receivedAmount;
                            $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                        }
                    } elseif ($dueInvoice->due == $receivedAmount) {

                        if ($receivedAmount > 0) {

                            $this->saleOrSalesOrderFillUpByPayment(
                                customerPayment: $customerPayment,
                                customerId: $customerId,
                                receiptVoucherPrefix: $receiptVoucherPrefix,
                                dueInvoice: $dueInvoice,
                                receivedAmount: $receivedAmount,
                                paymentMethodId: $paymentMethodId,
                                accountId: $accountId,
                                date: $date,
                                invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                            );

                            // Add Customer Payment invoice
                            $this->customerPaymentInvoice($customerPayment, $dueInvoice, $receivedAmount);

                            $receivedAmount -= $receivedAmount;
                            $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                        }
                    } elseif ($dueInvoice->due < $receivedAmount) {

                        if ($dueInvoice->due > 0) {

                            $this->saleOrSalesOrderFillUpByPayment(
                                customerPayment: $customerPayment,
                                customerId: $customerId,
                                receiptVoucherPrefix: $receiptVoucherPrefix,
                                dueInvoice: $dueInvoice,
                                receivedAmount: $dueInvoice->due,
                                paymentMethodId: $paymentMethodId,
                                accountId: $accountId,
                                date: $date,
                                invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                            );

                            // Add Customer Payment invoice
                            $this->customerPaymentInvoice($customerPayment, $dueInvoice, $dueInvoice->due);

                            $receivedAmount -= $dueInvoice->due;
                            $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                        }
                    }

                    $index++;
                }
            }
        }
    }

    public function randomInvoiceOrSalesOrderPayment($customerPayment, $customerId, $receiptVoucherPrefix, $receivedAmount, $paymentMethodId, $accountId, $date, $invoiceVoucherRefIdUtil)
    {
        $dueInvoices = Sale::where('customer_id', $customerId)
            ->where('branch_id', auth()->user()->branch_id)
            ->where('due', '>', 0)
            ->orderBy('report_date', 'asc')
            ->get();

        if (count($dueInvoices) > 0) {

            $index = 0;
            foreach ($dueInvoices as $dueInvoice) {

                if ($dueInvoice->due > $receivedAmount) {

                    if ($receivedAmount > 0) {

                        $this->saleOrSalesOrderFillUpByPayment(
                            customerPayment: $customerPayment,
                            customerId: $customerId,
                            receiptVoucherPrefix: $receiptVoucherPrefix,
                            dueInvoice: $dueInvoice,
                            receivedAmount: $receivedAmount,
                            paymentMethodId: $paymentMethodId,
                            accountId: $accountId,
                            date: $date,
                            invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                        );

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $receivedAmount);

                        $receivedAmount -= $receivedAmount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due == $receivedAmount) {

                    if ($receivedAmount > 0) {

                        $this->saleOrSalesOrderFillUpByPayment(
                            customerPayment: $customerPayment,
                            customerId: $customerId,
                            receiptVoucherPrefix: $receiptVoucherPrefix,
                            dueInvoice: $dueInvoice,
                            receivedAmount: $receivedAmount,
                            paymentMethodId: $paymentMethodId,
                            accountId: $accountId,
                            date: $date,
                            invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                        );

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $receivedAmount);

                        $receivedAmount -= $receivedAmount;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                } elseif ($dueInvoice->due < $receivedAmount) {

                    if ($dueInvoice->due > 0) {

                        $this->saleOrSalesOrderFillUpByPayment(
                            customerPayment: $customerPayment,
                            customerId: $customerId,
                            receiptVoucherPrefix: $receiptVoucherPrefix,
                            dueInvoice: $dueInvoice,
                            receivedAmount: $dueInvoice->due,
                            paymentMethodId: $paymentMethodId,
                            accountId: $accountId,
                            date: $date,
                            invoiceVoucherRefIdUtil: $invoiceVoucherRefIdUtil
                        );

                        // Add Customer Payment invoice
                        $this->customerPaymentInvoice($customerPayment, $dueInvoice, $dueInvoice->due);

                        $receivedAmount -= $dueInvoice->due;
                        $this->saleUtil->adjustSaleInvoiceAmounts($dueInvoice);
                    }
                }

                $index++;
            }
        }
    }

    public function saleOrSalesOrderFillUpByPayment($customerPayment, $customerId, $receiptVoucherPrefix, $dueInvoice, $receivedAmount, $paymentMethodId, $accountId, $date, $invoiceVoucherRefIdUtil)
    {
        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = ($receiptVoucherPrefix != null ? $receiptVoucherPrefix : 'SRV') . str_pad($invoiceVoucherRefIdUtil->getLastId('sale_payments'), 5, "0", STR_PAD_LEFT);
        $addSalePayment->branch_id = auth()->user()->branch_id;
        $addSalePayment->sale_id = $dueInvoice->id;
        $addSalePayment->customer_id = $customerId;
        $addSalePayment->account_id = $accountId;
        $addSalePayment->customer_payment_id = $customerPayment->id;
        $addSalePayment->paid_amount = $receivedAmount;
        $addSalePayment->date = date('d-m-Y', strtotime($date));
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->payment_method_id = $paymentMethodId;
        $addSalePayment->admin_id = auth()->user()->id;
        $addSalePayment->payment_on = 1;
        $addSalePayment->save();
    }

    public function customerPaymentInvoice($customerPayment, $dueInvoice, $receivedAmount)
    {
        $addCustomerPaymentInvoice = new CustomerPaymentInvoice();
        $addCustomerPaymentInvoice->customer_payment_id = $customerPayment->id;
        $addCustomerPaymentInvoice->sale_id = $dueInvoice->id;
        $addCustomerPaymentInvoice->paid_amount = $receivedAmount;
        $addCustomerPaymentInvoice->save();
    }
}
