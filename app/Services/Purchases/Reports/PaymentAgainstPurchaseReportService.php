<?php

namespace App\Services\Purchases\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Accounts\AccountingVoucherDescriptionReference;

class PaymentAgainstPurchaseReportService
{
    public function paymentAgainstPurchaseReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $paidAgainstPurchases = $this->query(request: $request);

        return DataTables::of($paidAgainstPurchases)

            ->editColumn('payment_voucher', function ($row) {

                $paymentVoucherNo = $row->voucherDescription?->accountingVoucher?->voucher_no;
                $paymentVoucherId = $row->voucherDescription?->accountingVoucher?->id;

                return '<a href="' . route('payments.show', $paymentVoucherId) . '" id="details_btn">' . $paymentVoucherNo . '</a>';
            })

            ->editColumn('payment_date', function ($row) use ($generalSettings) {

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

            ->editColumn('purchase_or_order_id', function ($row) {

                $purchaseId = $row?->purchase?->id;
                $invoiceId = $row?->purchase?->invoice_id;
                $purchaseStatus = $row?->purchase?->purchase_status;

                if ($purchaseStatus == PurchaseStatus::Purchase->value) {

                    return __('Purchase') . ':<a href="' . route('purchases.show', $purchaseId) . '" id="details_btn">' . $invoiceId . '</a>';
                } elseif ($purchaseStatus == PurchaseStatus::PurchaseOrder->value) {

                    return __('P/o') . ':<a href="' . route('purchase.orders.show', $purchaseId) . '" id="details_btn">' . $invoiceId . '</a>';
                }
            })

            ->editColumn('purchase_date', function ($row) use ($generalSettings) {

                $purchaseDate = $row?->purchase?->date;

                return date($generalSettings['business_or_shop__date_format'], strtotime($purchaseDate));
            })

            ->editColumn('supplier', function ($row) {

                return $row?->purchase?->supplier?->name;
            })

            ->editColumn('total_purchase_amount', function ($row) {

                $amount = curr_cnv($row?->purchase?->total_purchase_amount, $row?->voucherDescription?->accountingVoucher?->branch?->branchCurrency?->currency_rate, $row?->voucherDescription?->accountingVoucher?->branch_id);

                return \App\Utils\Converter::format_in_bdt($amount);
            })

            ->editColumn('credit_account', function ($row) {

                $accountName = $row->voucherDescription?->accountingVoucher?->voucherCreditDescription?->account?->name;
                $accountNumber = $row->voucherDescription?->accountingVoucher?->voucherCreditDescription?->account?->account_number;

                $__accountNumber = $accountNumber ? ' / ' . $accountNumber : '';

                return $accountName . $__accountNumber;
            })

            ->editColumn('payment_method', function ($row) {

                return $row->voucherDescription?->accountingVoucher?->voucherCreditDescription?->paymentMethod?->name;
            })

            ->editColumn('paid_amount', function ($row) {

                $amount = curr_cnv($row->amount, $row?->voucherDescription?->accountingVoucher?->branch?->branchCurrency?->currency_rate, $row?->voucherDescription?->accountingVoucher?->branch_id);

                return '<span class="paid_amount text-danger" data-value="' . $amount . '">' . \App\Utils\Converter::format_in_bdt($amount) . '</span>';
            })

            ->rawColumns(['payment_voucher', 'payment_date', 'branch', 'purchase_or_order_id', 'purchase_date', 'supplier', 'total_purchase_amount', 'credit_account', 'payment_method', 'paid_amount'])
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
            'voucherDescription.accountingVoucher.voucherCreditDescription:id,accounting_voucher_id,account_id,payment_method_id,cheque_no,cheque_serial_no',
            'voucherDescription.accountingVoucher.voucherCreditDescription.account:id,name,account_number',
            'voucherDescription.accountingVoucher.voucherCreditDescription.paymentMethod:id,name',
            'purchase:id,invoice_id,supplier_account_id,purchase_status,total_purchase_amount,date,report_date',
            'purchase.supplier:id,name,phone',
        ])->where('purchase_id', '!=', null)
            ->leftJoin('accounting_voucher_descriptions', 'voucher_description_references.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id');

        $this->filter(request: $request, query: $query);

        return $query->select(
            'voucher_description_references.id',
            'voucher_description_references.voucher_description_id',
            'voucher_description_references.purchase_id',
            'voucher_description_references.amount',
            'accounting_voucher_descriptions.id as accounting_voucher_description_id',
            'accounting_voucher_descriptions.accounting_voucher_id as avdid',
            'accounting_voucher_descriptions.account_id as supplier_account_id',
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

        if ($request->supplier_account_id) {

            $query->where('accounting_voucher_descriptions.account_id', $request->supplier_account_id);
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
