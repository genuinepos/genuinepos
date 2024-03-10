<?php

namespace App\Services\StockAdjustments;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\StockAdjustmentType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\StockAdjustments\StockAdjustment;

class StockAdjustmentService
{
    public function stockAdjustmentsTable(?object $request): object
    {
        $generalSettings = config('generalSettings');
        $adjustments = '';
        $query = DB::table('stock_adjustments')
            ->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('accounts', 'stock_adjustments.expense_account_id', 'accounts.id')
            ->leftJoin('users', 'stock_adjustments.created_by_id', 'users.id');

        $this->filter(request: $request, query: $query);

        $adjustments = $query->select(
            'stock_adjustments.*',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'accounts.name as expense_ledger',
            'users.prefix as created_prefix',
            'users.name as created_name',
            'users.last_name as created_last_name',
        )->orderBy('stock_adjustments.date_ts', 'desc');

        return DataTables::of($adjustments)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="' . route('stock.adjustments.show', [$row->id]) . '">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('stock_adjustment_delete')) {

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('stock.adjustments.delete', $row->id) . '">' . __('Delete') . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                return date($generalSettings['business_or_shop__date_format'], strtotime($row->date));
            })
            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('stock.adjustments.show', [$row->id]) . '" id="details_btn">' . $row->voucher_no . '</a>';
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

            ->editColumn('type', function ($row) {

                return '<span class="fw-bold">' . StockAdjustmentType::tryFrom($row->type)->name . '</span>';
            })

            ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . \App\Utils\Converter::format_in_bdt($row->net_total_amount) . '</span>')

            ->editColumn('recovered_amount', fn ($row) => '<span class="recovered_amount" data-value="' . $row->recovered_amount . '">' . \App\Utils\Converter::format_in_bdt($row->recovered_amount) . '</span>')

            ->editColumn('created_by', fn ($row) => $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name)

            ->rawColumns(['action', 'date', 'voucher_no', 'business_location', 'adjustment_location', 'type', 'net_total_amount', 'recovered_amount', 'created_by'])
            ->make(true);
    }

    public function addStockAdjustment(object $request, object $codeGenerator, string $voucherPrefix): object
    {
        $voucherNo = $codeGenerator->generateMonthWise(table: 'stock_adjustments', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addStockAdjustment = new StockAdjustment();
        $addStockAdjustment->branch_id = auth()->user()->branch_id;
        $addStockAdjustment->voucher_no = $voucherNo;
        $addStockAdjustment->expense_account_id = $request->expense_account_id;
        $addStockAdjustment->type = $request->type;
        $addStockAdjustment->total_item = $request->total_item;
        $addStockAdjustment->total_qty = $request->total_qty;
        $addStockAdjustment->net_total_amount = $request->net_total_amount;
        $addStockAdjustment->recovered_amount = $request->recovered_amount ? $request->recovered_amount : 0;
        $addStockAdjustment->date = $request->date;
        $addStockAdjustment->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addStockAdjustment->created_by_id = auth()->user()->id;
        $addStockAdjustment->reason = $request->reason;
        $addStockAdjustment->save();

        return $addStockAdjustment;
    }

    public function deleteStockAdjustment(int $id): object|array
    {
        $deleteAdjustment = $this->singleStockAdjustment(id: $id, with: [
            'references',
            'adjustmentProducts',
            'adjustmentProducts.product',
            'adjustmentProducts.variant',
        ]);

        if (count($deleteAdjustment->references) > 0) {

            return ['pass' => false, 'msg' => __('Stock Adjustment can not be deleted. There is one or more receipt which is against this voucher.')];
        }

        if (!is_null($deleteAdjustment)) {

            $deleteAdjustment->delete();
        }

        return $deleteAdjustment;
    }

    public function singleStockAdjustment(int $id, array $with = null): ?object
    {
        $query = StockAdjustment::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function restrictions(object $request): ?array
    {
        if ($request->product_ids == null) {

            return ['pass' => false, 'msg' => __('Product table is empty.')];
        }

        return ['pass' => true];
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('stock_adjustments.branch_id', null);
            } else {

                $query->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if ($request->type) {

            $query->where('stock_adjustments.type', $request->type);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('stock_adjustments.date_ts', $date_range); // Final
        }

         // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('stock_adjustments.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
