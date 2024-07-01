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

            ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . \App\Utils\Converter::format_in_bdt($row->net_total_amount) . '</span>')

            ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="' . $row->order_discount_amount . '">' . \App\Utils\Converter::format_in_bdt($row->order_discount_amount) . '</span>')

            ->editColumn('shipment_charge', fn ($row) => '<span class="shipment_charge" data-value="' . $row->shipment_charge . '">' . \App\Utils\Converter::format_in_bdt($row->shipment_charge) . '</span>')

            ->editColumn('order_tax_amount', fn ($row) => '<span class="order_tax_amount" data-value="' . $row->order_tax_amount . '">' . '(' . $row->order_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($row->order_tax_amount) . '</span>')

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . $row->total_invoice_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_invoice_amount) . '</span>')

            ->editColumn('received_amount', fn ($row) => '<span class="received_amount text-success" data-value="' . $row->received_amount . '">' . \App\Utils\Converter::format_in_bdt($row->received_amount) . '</span>')

            ->editColumn('sale_return_amount', fn ($row) => '<span class="sale_return_amount" data-value="' . $row->sale_return_amount . '">' . \App\Utils\Converter::format_in_bdt($row->sale_return_amount) . '</span>')

            ->editColumn('due', fn ($row) => '<span class="text-danger">' . '<span class="due" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span></span>')

            ->rawColumns(['date', 'branch', 'invoice_id', 'total_qty', 'net_total_amount', 'order_discount_amount', 'shipment_charge', 'order_tax_amount', 'total_invoice_amount', 'received_amount', 'sale_return_amount', 'due'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = DB::table('sales')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
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

        $saleScreenTypes = [
            auth()->user()->can('view_add_sale') ? SaleScreenType::AddSale->value : null,
            auth()->user()->can('pos_all') ? SaleScreenType::PosSale->value : null,
            auth()->user()->can('service_invoices_index') && isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::True->value ? SaleScreenType::ServicePosSale->value : null,
        ];

        $query->whereIn('sales.sale_screen', $saleScreenTypes);

        // if (auth()->user()->can('service_invoices_only_own')) {

        //     $query->where(function ($query) {

        //         if ($query->sale_screen == SaleScreenType::ServicePosSale->value) {
        //             $query->where('sales.created_by_id', auth()->user()->id);
        //         }
        //     });
        // }

        // if (auth()->user()->can('view_own_sale')) {

        //     $query->where(function ($query) {

        //         if ($query->sale_screen == SaleScreenType::PosSale->value) {
        //             $query->where('sales.created_by_id', auth()->user()->id);
        //         }
        //     });
        // }

        if ($generalSettings['subscription']->features['sales'] == BooleanType::True->value) {

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.created_by_id', auth()->user()->id);
            }
        } else if ($generalSettings['subscription']->features['sales'] == BooleanType::False->value && auth()->user()->can('service_invoices_index') && isset($generalSettings['subscription']->features['services']) && $generalSettings['subscription']->features['services'] == BooleanType::True->value) {

            if (auth()->user()->can('service_invoices_only_own')) {

                $query->where('sales.created_by_id', auth()->user()->id);
            }
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
