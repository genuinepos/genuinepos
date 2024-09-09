<?php

namespace App\Services\Sales\Reports;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use App\Enums\PaymentStatus;
use App\Enums\SaleScreenType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SalesReportService
{
    public function salesReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $sales = $this->query(request: $request);

        return DataTables::of($sales)

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date($generalSettings['business_or_shop__date_format'], strtotime($row->date));
            })

            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })

            ->editColumn('invoice_id', function ($row) {

                return '<a href="' . route('sales.show', $row->id) . '" id="details_btn">' . $row->invoice_id . '</a>';
            })

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . curr_cnv($row->net_total_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->net_total_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="' . curr_cnv($row->order_discount_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->order_discount_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('shipment_charge', fn ($row) => '<span class="shipment_charge" data-value="' . curr_cnv($row->shipment_charge, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->shipment_charge, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('order_tax_amount', fn ($row) => '<span class="order_tax_amount" data-value="' . curr_cnv($row->order_tax_amount, $row->c_rate, $row->branch_id) . '">' . '(' . $row->order_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->order_tax_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->total_invoice_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('received_amount', fn ($row) => '<span class="received_amount text-success" data-value="' . curr_cnv($row->received_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->received_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount" data-value="' . curr_cnv($row->sale_return_amount, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->sale_return_amount, $row->c_rate, $row->branch_id)) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="text-danger">' . '<span class="due" data-value="' . curr_cnv($row->due, $row->c_rate, $row->branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->due, $row->c_rate, $row->branch_id)) . '</span></span>')

            ->rawColumns(['date', 'branch', 'invoice_id', 'total_qty', 'net_total_amount', 'order_discount_amount', 'shipment_charge', 'order_tax_amount', 'total_invoice_amount', 'received_amount', 'sale_return_amount', 'due'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = DB::table('sales')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('currencies', 'branches.currency_id', 'currencies.id')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id');

        $this->filter(request: $request, query: $query);

        return $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.date',
            'sales.invoice_id',
            'sales.total_qty',
            'sales.net_total_amount',
            'sales.order_discount_amount',
            'sales.shipment_charge',
            'sales.order_tax_percent',
            'sales.order_tax_amount',
            'sales.total_invoice_amount',
            'sales.sale_return_amount',
            'sales.due',
            'sales.paid as received_amount',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'currencies.currency_rate as c_rate'
        )->where('sales.status', SaleStatus::Final->value)->orderBy('sales.sale_date_ts', 'desc');
    }

    private function filter(object $request, object $query): object
    {
        $generalSettings = config('generalSettings');

        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', null);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.sale_date_ts', $date_range); // Final
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('sales.due', '=', 0);
            } elseif ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('sales.paid', '>', 0)->where('sales.due', '>', 0);
            } elseif ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('sales.paid', '=', 0);
            }
        }

        if ($request->sale_screen) {

            $query->where('sales.sale_screen', $request->sale_screen);
        }

        if (auth()->user()->can('view_only_won_transactions')) {

            $query->where('sales.created_by_id', auth()->user()->id);
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
