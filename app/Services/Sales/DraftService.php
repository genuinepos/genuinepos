<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Models\Sales\Sale;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DraftService
{
    public function draftListTable($request)
    {
        $generalSettings = config('generalSettings');
        $quotations = '';

        $query = DB::table('sales')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users as created_by', 'sales.created_by_id', 'created_by.id')
            ->where('sales.draft_status', 1);

        $this->filteredQuery($request, $query);

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sales.created_by_id', auth()->user()->id);
            }

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        $quotations = $query->select(
            'sales.id',
            'sales.branch_id',
            'sales.draft_id',
            'sales.date',
            'sales.total_item',
            'sales.total_qty',
            'sales.total_invoice_amount',
            'sales.order_status',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'customers.name as customer_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('sales.draft_date_ts', 'desc');

        return DataTables::of($quotations)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('sale.drafts.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __("View") . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('sale_draft')) {

                        $html .= '<a class="dropdown-item" href="' . route('sale.drafts.edit', [$row->id]) . '">' . __("Edit") . '</a>';
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('sale_draft')) {

                        $html .= '<a href="' . route('sale.drafts.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __("Delete") . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business__date_format']);

                return date($__date_format, strtotime($row->date));
            })
            ->editColumn('draft_id', function ($row) {

                return '<a href="' . route('sale.drafts.show', [$row->id]) . '" id="details_btn">' . $row->draft_id . '</a>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business__shop_name'];
                }
            })
            ->editColumn('customer', fn ($row) => $row->customer_name ? $row->customer_name : 'Walk-In-Customer')

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('total_invoice_amount', fn ($row) => '<span class="total_invoice_amount" data-value="' . $row->total_invoice_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_invoice_amount) . '</span>')

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'total_invoice_amount', 'draft_id', 'branch', 'customer', 'created_by'])
            ->make(true);
    }

    private function filteredQuery(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', null);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('sales.created_by_id', $request->created_by_id);
        }

        if ($request->customer_account_id) {

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_account_id', null);
            } else {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.draft_date_ts', $date_range); // Final
        }

        return $query;
    }

    public function singleDraft(int $id, ?array $with = null): ?object
    {
        $query = Sale::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}