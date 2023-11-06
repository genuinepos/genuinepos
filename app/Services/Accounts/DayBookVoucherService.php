<?php

namespace App\Services\Accounts;

use App\Enums\AccountingVoucherType;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;

class DayBookVoucherService
{
    public function vouchersForPaymentReceipt(?int $accountId, $type): ?object
    {
        $voucherTypes = '';
        if ($type == AccountingVoucherType::Receipt->value) {

            $voucherTypes = [DayBookVoucherType::Sales->value, DayBookVoucherType::SalesOrder->value];
        } elseif ($type == AccountingVoucherType::Payment->value) {

            $voucherTypes = [DayBookVoucherType::Purchase->value, DayBookVoucherType::PurchaseOrder->value];
        }

        $query = DB::table('day_books')
            ->leftJoin('sales', 'day_books.sale_id', 'sales.id')
            ->leftJoin('purchases', 'day_books.purchase_id', 'purchases.id')
            ->leftJoin('sale_returns', 'day_books.sale_return_id', 'sale_returns.id')
            ->leftJoin('purchase_returns', 'day_books.purchase_return_id', 'purchase_returns.id');

        if ($type) {

            $query->whereIn('day_books.voucher_type', $voucherTypes);
        }

        if ($accountId) {

            $query->where('day_books.account_id', $accountId);
        }

        $vouchers = $query->select(
            'day_books.id',
            'day_books.voucher_type',
            'sales.id as sale_id',
            'sales.date as sale_date',
            'sales.invoice_id as sale_invoice',
            'sales.order_id as sale_order',
            'sales.total_invoice_amount as sold_amount',
            'sales.paid as sale_receipt',
            'sales.due as sale_due',
            'purchases.id as purchase_id',
            'purchases.date as purchase_date',
            'purchases.invoice_id as purchase_invoice',
            'purchases.total_purchase_amount as purchased_amount',
            'purchases.paid as purchase_paid',
            'purchases.due as purchase_due',
            'sale_returns.id as sales_return_id',
            'sale_returns.voucher_no as sales_return_voucher',
            'sale_returns.date as sales_return_date',
            'sale_returns.total_return_amount as sales_returned_amount',
            'sale_returns.paid as sales_returned_paid',
            'sale_returns.due as sales_returned_due',
            'purchase_returns.id as purchase_return_id',
            'purchase_returns.voucher_no as purchase_return_voucher',
            'purchase_returns.date as purchase_return_date',
            'purchase_returns.total_return_amount as purchase_returned_amount',
            'purchase_returns.received_amount as purchase_returned_received',
            'purchase_returns.due as purchase_returned_due',
        )->orderBy('day_books.date_ts', 'asc')->get();

        return $vouchers;
    }

    public function filteredVoucher(?object $vouchers): ?array
    {
        $arr = [];
        foreach ($vouchers as $key => $voucher) {

            if ($voucher->voucher_type == DayBookVoucherType::Sales->value) {

                if ($voucher->sale_due > 0) {

                    $receivable = $voucher->sold_amount;
                    $paymentStatus = '';
                    if ($voucher->sale_due > 0 && $voucher->sale_due < $receivable) {

                        $paymentStatus = __('Partial');
                    } elseif ($receivable == $voucher->sale_due) {

                        $paymentStatus = __('Due');
                    }

                    $arr[] = [
                        'voucherId' => $voucher->id,
                        'refId' => $voucher->sale_id,
                        'voucherType' => $voucher->voucher_type,
                        'voucherTypeStr' => __('Sales'),
                        'voucherNo' => $voucher->sale_invoice,
                        'paymentStatus' => $paymentStatus,
                        'due' => $voucher->sale_due,
                    ];
                }
            } elseif ($voucher->voucher_type == DayBookVoucherType::SalesOrder->value) {

                if ($voucher->sale_due > 0) {

                    $receivable = $voucher->sold_amount;
                    $paymentStatus = '';
                    if ($voucher->sale_due > 0 && $voucher->sale_due < $receivable) {

                        $paymentStatus = __('Partial');
                    } elseif ($receivable == $voucher->sale_due) {

                        $paymentStatus = __('Due');
                    }

                    $arr[] = [
                        'voucherId' => $voucher->id,
                        'refId' => $voucher->sale_id,
                        'voucherType' => $voucher->voucher_type,
                        'voucherTypeStr' => __('Sales-Order'),
                        'voucherNo' => $voucher->sale_order,
                        'paymentStatus' => $paymentStatus,
                        'due' => $voucher->sale_due,
                    ];
                }
            } elseif ($voucher->voucher_type == DayBookVoucherType::Purchase->value) {

                if ($voucher->purchase_due > 0) {

                    $payable = $voucher->purchased_amount;
                    $paymentStatus = '';
                    if ($voucher->purchase_due > 0 && $voucher->purchase_due < $payable) {

                        $paymentStatus = __('Partial');
                    } elseif ($payable == $voucher->purchase_due) {

                        $paymentStatus = __('Due');
                    }

                    $arr[] = [
                        'voucherId' => $voucher->id,
                        'refId' => $voucher->purchase_id,
                        'voucherType' => $voucher->voucher_type,
                        'voucherTypeStr' => __('Purchase'),
                        'voucherNo' => $voucher->purchase_invoice,
                        'paymentStatus' => $paymentStatus,
                        'due' => $voucher->purchase_due,
                    ];
                }
            } elseif ($voucher->voucher_type == DayBookVoucherType::PurchaseOrder->value) {

                if ($voucher->purchase_due > 0) {

                    $payable = $voucher->purchased_amount;
                    $paymentStatus = '';
                    if ($voucher->purchase_due > 0 && $voucher->purchase_due < $payable) {

                        $paymentStatus = __('Partial');
                    } elseif ($payable == $voucher->purchase_due) {

                        $paymentStatus = __('Due');
                    }

                    $arr[] = [
                        'voucherId' => $voucher->id,
                        'refId' => $voucher->purchase_id,
                        'voucherType' => $voucher->voucher_type,
                        'voucherTypeStr' => __('P/o'),
                        'voucherNo' => $voucher->purchase_invoice,
                        'paymentStatus' => $paymentStatus,
                        'due' => $voucher->purchase_due,
                    ];
                }
            }
        }

        return $arr;
    }
}
