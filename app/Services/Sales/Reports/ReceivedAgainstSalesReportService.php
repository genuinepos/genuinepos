<?php

namespace App\Services\Sales\Reports;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Accounts\AccountingVoucherDescriptionReference;

class ReceivedAgainstSalesReportService
{
    public function receivedAgainstSalesReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $receivedAgainstSales = $this->query(request: $request);

        return DataTables::of($receivedAgainstSales)

            ->editColumn('receipt_voucher', function ($row) {

                $receiptVoucherNo = $row->voucherDescription?->accountingVoucher?->voucher_no;
                $receiptVoucherId = $row->voucherDescription?->accountingVoucher?->id;

                return '<a href="' . route('receipts.show', $receiptVoucherId) . '" id="details_btn">' . $receiptVoucherNo . '</a>';
            })

            ->editColumn('receipt_date', function ($row) use ($generalSettings) {

                $date = $row->voucherDescription?->accountingVoucher?->date;

                return date($generalSettings['business_or_shop__date_format'], strtotime($date));
            })

            ->editColumn('branch', function ($row) use ($generalSettings) {

                $branch_id = $row?->voucherDescription?->accountingVoucher?->branch_id;
                if ($branch_id) {

                    $branch = $row?->voucherDescription?->accountingVoucher?->branch;
                    $parentBranch = $row?->voucherDescription?->accountingVoucher?->branch?->parentBranch;
                    if ($parentBranch) {

                        return $parentBranch->name . '(' . $branch->area_name . ')';
                    } else {

                        return $branch->name . '(' . $branch->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })

            ->editColumn('sales_or_order_id', function ($row) {

                $saleId = $row?->sale?->id;
                $invoiceId = $row?->sale?->invoice_id;
                $salesOrderId = $row?->sale?->order_id;
                $saleStatus = $row?->sale?->status;

                if ($saleStatus == SaleStatus::Final->value) {

                    return __('Sales') . ':<a href="' . route('sales.show', $saleId) . '" id="details_btn">' . $invoiceId . '</a>';
                } elseif ($saleStatus == SaleStatus::Order->value) {

                    return __('Sales-Order') . ':<a href="' . route('sale.orders.show', $saleId) . '" id="details_btn">' . $salesOrderId . '</a>';
                }
            })

            ->editColumn('sales_date', function ($row) use ($generalSettings) {

                $saleDate = $row?->sale?->date;

                return date($generalSettings['business_or_shop__date_format'], strtotime($saleDate));
            })

            ->editColumn('customer', function ($row) {

                return $row?->sale?->customer?->name;
            })

            ->editColumn('total_invoice_amount', function ($row) {

                $amount = curr_cnv($row?->sale?->total_invoice_amount, $row?->voucherDescription?->accountingVoucher?->branch?->branchCurrency?->currency_rate, $row?->voucherDescription?->accountingVoucher?->branch_id);

                return \App\Utils\Converter::format_in_bdt($amount);
            })

            ->editColumn('debit_account', function ($row) {

                $accountName = $row->voucherDescription?->accountingVoucher?->voucherDebitDescription?->account?->name;
                $accountNumber = $row->voucherDescription?->accountingVoucher?->voucherDebitDescription?->account?->account_number;

                $__accountNumber = $accountNumber ? ' / ' . $accountNumber : '';

                return $accountName . $__accountNumber;
            })

            ->editColumn('payment_method', function ($row) {

                return $row->voucherDescription?->accountingVoucher?->voucherDebitDescription?->paymentMethod?->name;
            })

            ->editColumn('received_amount', function ($row) {

                $amount = curr_cnv($row->amount, $row?->voucherDescription?->accountingVoucher?->branch?->branchCurrency?->currency_rate, $row?->voucherDescription?->accountingVoucher?->branch_id);

                return '<span class="received_amount text-success" data-value="' . $amount . '">' . \App\Utils\Converter::format_in_bdt($amount) . '</span>';
            })

            ->rawColumns(['receipt_voucher', 'receipt_date', 'branch', 'sales_or_order_id', 'sales_date', 'customer', 'total_invoice_amount', 'debit_account', 'payment_method', 'received_amount'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = AccountingVoucherDescriptionReference::query()->with([
            'voucherDescription:id,accounting_voucher_id',
            'voucherDescription.accountingVoucher:id,voucher_no,branch_id,reference,remarks,date,date_ts',
            'voucherDescription.accountingVoucher.branch:id,currency_id,name,branch_code,area_name,parent_branch_id',
            'voucherDescription.accountingVoucher.branch.parentBranch:id,name',
            'voucherDescription.accountingVoucher.branch.branchCurrency:id,currency_rate',
            'voucherDescription.accountingVoucher.voucherDebitDescription:id,accounting_voucher_id,account_id,payment_method_id,cheque_no,cheque_serial_no',
            'voucherDescription.accountingVoucher.voucherDebitDescription.account:id,name,account_number',
            'voucherDescription.accountingVoucher.voucherDebitDescription.paymentMethod:id,name',
            'sale:id,invoice_id,order_id,customer_account_id,status,total_invoice_amount,date,sale_date_ts',
            'sale.customer:id,name,phone',
        ])->where('sale_id', '!=', null)
            ->leftJoin('accounting_voucher_descriptions', 'voucher_description_references.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id');

        $this->filter(request: $request, query: $query);

        return $query->select(
            'voucher_description_references.id',
            'voucher_description_references.voucher_description_id',
            'voucher_description_references.sale_id',
            'voucher_description_references.amount',
            'accounting_voucher_descriptions.id as accounting_voucher_description_id',
            'accounting_voucher_descriptions.accounting_voucher_id as avdid',
            'accounting_voucher_descriptions.account_id as customer_account_id',
            'accounting_vouchers.id as accounting_voucher_id',
        )->orderBy('accounting_vouchers.date_ts', 'desc');
    }

    private function filter(object $request, object $query): object
    {
        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounting_vouchers.branch_id', null);
            } else {

                $query->where('accounting_vouchers.branch_id', $request->branch_id);
            }
        }

        if ($request->customer_account_id) {

            $query->where('accounting_voucher_descriptions.account_id', $request->customer_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('accounting_vouchers.date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('accounting_vouchers.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
