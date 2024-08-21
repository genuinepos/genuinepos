<?php

namespace App\Services\Products;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Products\StockIssue;

class StockIssueService
{
    public function stockIssuesTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $stockIssues = '';

        $query = DB::table('stock_issues')
            ->leftJoin('hrm_departments', 'stock_issues.department_id', 'hrm_departments.id')
            ->leftJoin('users as reported_by', 'stock_issues.reported_by_id', 'reported_by.id')
            ->leftJoin('branches', 'stock_issues.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users as created_by', 'stock_issues.created_by_id', 'created_by.id');

        $this->filteredQuery(request: $request, query: $query);

        $stockIssues = $query->select(
            'stock_issues.id',
            'stock_issues.branch_id',
            'stock_issues.voucher_no',
            'stock_issues.date',
            'stock_issues.date_ts',
            'stock_issues.total_item',
            'stock_issues.total_qty',
            'stock_issues.net_total_amount',
            'hrm_departments.name as department_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'reported_by.prefix as reported_by_prefix',
            'reported_by.name as reported_by_name',
            'reported_by.last_name as reported_by_last_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('stock_issues.date_ts', 'desc');

        return DataTables::of($stockIssues)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $html .= '<a href="' . route('stock.issues.show', [$row->id]) . '" class="dropdown-item" id="detailsBtn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    $html .= '<a class="dropdown-item" href="' . route('stock.issues.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    $html .= '<a href="' . route('stock.issues.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = $generalSettings['business_or_shop__date_format'];

                return date($__date_format, strtotime($row->date));
            })

            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('stock.issues.show', [$row->id]) . '" id="detailsBtn">' . $row->voucher_no . '</a>';
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

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="' . $row->total_item . '">' . \App\Utils\Converter::format_in_bdt($row->total_item) . '</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="' . $row->total_qty . '">' . \App\Utils\Converter::format_in_bdt($row->total_qty) . '</span>')

            ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . \App\Utils\Converter::format_in_bdt($row->net_total_amount) . '</span>')

            ->editColumn('reported_by', function ($row) {

                return $row->reported_by_prefix . ' ' . $row->reported_by_name . ' ' . $row->reported_by_last_name;
            })

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'total_item', 'total_qty', 'net_total_amount', 'voucher_no', 'branch', 'reported_by', 'created_by'])
            ->make(true);
    }

    public function addStockIssue(object $request, object $codeGenerator, ?string $voucherPrefix)
    {
        $__voucherPrefix = $voucherPrefix ? $voucherPrefix : 'STI';
        $voucherNo = $codeGenerator->generateMonthWise(table: 'stock_issues', column: 'voucher_no', prefix: $__voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addStockIssue = new StockIssue();
        $addStockIssue->voucher_no = $voucherNo;
        $addStockIssue->date = $request->date;
        $addStockIssue->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addStockIssue->department_id = $request->department_id;
        $addStockIssue->reported_by_id = $request->reported_by_id;
        $addStockIssue->total_item = $request->total_item;
        $addStockIssue->total_qty = $request->total_qty;
        $addStockIssue->net_total_amount = $request->net_total_amount;
        $addStockIssue->remarks = $request->remarks;
        $addStockIssue->created_by_id = auth()->user()->id;
        $addStockIssue->save();

        return $addStockIssue;
    }

    public function updateStockIssue(object $request, int $id)
    {
        $updateStockIssue = $this->singleStockIssue(id: $id, with: ['stockIssuedProducts']);
        foreach ($updateStockIssue->stockIssuedProducts as $stockIssuedProduct) {

            $stockIssuedProduct->is_delete_in_update = BooleanType::True->value;
            $stockIssuedProduct->save();
        }

        $updateStockIssue->date = $request->date;
        $time = date(' H:i:s', strtotime($updateStockIssue->date_ts));
        $updateStockIssue->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateStockIssue->department_id = $request->department_id;
        $updateStockIssue->reported_by_id = $request->reported_by_id;
        $updateStockIssue->total_item = $request->total_item;
        $updateStockIssue->total_qty = $request->total_qty;
        $updateStockIssue->net_total_amount = $request->net_total_amount;
        $updateStockIssue->remarks = $request->remarks;
        $updateStockIssue->save();

        return $updateStockIssue;
    }

    public function deleteStockIssue(int $id): object
    {

        $deleteStockIssue = $this->singleStockIssue(id: $id, with: [
            'stockIssuedProducts',
            'stockIssuedProducts.product',
            'stockIssuedProducts.variant',
            'stockIssuedProducts.stockChains',
            'stockIssuedProducts.stockChains.purchaseProduct',
        ]);

        if (isset($deleteStockIssue)) {

            $deleteStockIssue->delete();
        }

        return $deleteStockIssue;
    }

    public function singleStockIssue(int $id, ?array $with = null): ?object
    {
        $query = StockIssue::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function restrictions(object $request): array
    {
        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __('Stock issue product table must not be empty.')];
        }

        return ['pass' => true];
    }

    private function filteredQuery(object $request, object $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('stock_issues.branch_id', null);
            } else {

                $query->where('stock_issues.branch_id', $request->branch_id);
            }
        }

        if ($request->department_id) {

            $query->where('stock_issues.department_id', $request->department_id);
        }

        if ($request->reported_by_id) {

            $query->where('stock_issues.reported_by_id', $request->reported_by_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('stock_issues.date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
            $query->where('stock_issues.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
