<?php

namespace App\Http\Controllers\StockAdjustments\Reports;

use App\Enums\StockAdjustmentType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentReportController extends Controller
{
    public function __construct(private BranchService $branchService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('stock_adjustment_report'), 403);

        if ($request->ajax()) {

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

                ->rawColumns(['date', 'voucher_no', 'business_location', 'adjustment_location', 'type', 'net_total_amount', 'recovered_amount', 'created_by'])
                ->make(true);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('stock_adjustments.reports.stock_adjustment_report.index', compact('branches'));
    }

    public function print(Request $request)
    {
        $ownOrParentBranch = '';
        $branchName = $this->branchService->branchName();

        $filteredBranchName = $request->branch_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

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
        )->orderBy('stock_adjustments.date_ts', 'desc')->get();

        return view('stock_adjustments.reports.stock_adjustment_report.ajax_view.print', compact('adjustments', 'ownOrParentBranch', 'filteredBranchName', 'fromDate', 'toDate'));
    }

    public function allAmounts(Request $request)
    {
        $query = DB::table('stock_adjustments');

        $this->filter(request: $request, query: $query);

        return $query->select(
            DB::raw("SUM(IF(type = '1', net_total_amount, 0)) as total_normal"),
            DB::raw("SUM(IF(type = '2', net_total_amount, 0)) as total_abnormal"),
            DB::raw('sum(net_total_amount) as total_net_amount'),
            DB::raw('sum(recovered_amount) as total_recovered_amount'),
        )->groupBy('stock_adjustments.id')->get();
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

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('stock_adjustments.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
