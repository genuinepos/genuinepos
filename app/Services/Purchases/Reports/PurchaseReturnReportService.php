<?php

namespace App\Services\Purchases\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnReportService
{
    public function PurchaseReturnReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $returns = $this->query(request: $request);

        return DataTables::of($returns)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('purchase.returns.show', $row->id) . '" id="details_btn">' . $row->voucher_no . '</a>';
            })
            ->editColumn('parent_invoice_id', function ($row) {

                if ($row->purchase_id) {

                    return '<a href="' . route('purchases.show', [$row->purchase_id]) . '" id="details_btn">' . $row->parent_invoice_id . '</a>';
                }
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')
            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')
            ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . \App\Utils\Converter::format_in_bdt($row->net_total_amount) . '</span>')
            ->editColumn('return_discount', fn ($row) => '<span class="return_discount" data-value="' . $row->return_discount . '">' . \App\Utils\Converter::format_in_bdt($row->return_discount) . '</span>')
            ->editColumn('return_tax_amount', fn ($row) => '<span class="return_tax_amount" data-value="' . $row->return_tax_amount . '">' . \App\Utils\Converter::format_in_bdt($row->return_tax_amount) . '</span>')
            ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount" data-value="' . $row->total_return_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_return_amount) . '</span>')
            ->editColumn('received_amount', fn ($row) => '<span class="received_amount" data-value="' . $row->received_amount . '">' . \App\Utils\Converter::format_in_bdt($row->received_amount) . '</span>')
            ->editColumn('due', fn ($row) => '<span class="due" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span>')
            ->editColumn('createdBy', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'voucher_no', 'parent_invoice_id', 'branch', 'total_item', 'total_qty', 'net_total_amount', 'return_discount', 'return_tax_amount', 'total_return_amount', 'received_amount', 'due', 'createdBy'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = DB::table('purchase_returns')
            ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('accounts as suppliers', 'purchase_returns.supplier_account_id', 'suppliers.id')
            ->leftJoin('users as createdBy', 'purchase_returns.created_by_id', 'createdBy.id');

        $this->filter(request: $request, query: $query);

        return $query->select(
            'purchase_returns.*',
            'purchases.invoice_id as parent_invoice_id',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'suppliers.name as supplier_name',
            'createdBy.prefix as created_prefix',
            'createdBy.name as created_name',
            'createdBy.last_name as created_last_name',
        )->orderBy('purchase_returns.date_ts', 'desc');
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchase_returns.branch_id', null);
            } else {

                $query->where('purchase_returns.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_account_id) {

            $query->where('purchase_returns.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchase_returns.date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('purchase_returns.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
