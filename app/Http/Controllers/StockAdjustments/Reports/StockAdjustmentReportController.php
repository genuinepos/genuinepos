<?php

namespace App\Http\Controllers\StockAdjustments\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\StockAdjustments\Reports\StockAdjustmentReportService;
use App\Http\Requests\StockAdjustments\Reports\StockAdjustmentReportIndexRequest;
use App\Http\Requests\StockAdjustments\Reports\StockAdjustmentReportPrintRequest;
use App\Http\Requests\StockAdjustments\Reports\StockAdjustmentReportAllAmountRequest;

class StockAdjustmentReportController extends Controller
{
    public function __construct(private StockAdjustmentReportService $stockAdjustmentReportService, private BranchService $branchService) {}

    public function index(StockAdjustmentReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->stockAdjustmentReportService->stockAdjustmentReportTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('stock_adjustments.reports.stock_adjustment_report.index', compact('branches'));
    }

    public function print(StockAdjustmentReportPrintRequest $request)
    {
        $ownOrParentBranch = '';
        $branchName = $this->branchService->branchName();

        $filteredBranchName = $request->branch_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $adjustments = $this->stockAdjustmentReportService->query(request: $request)->get();

        return view('stock_adjustments.reports.stock_adjustment_report.ajax_view.print', compact('adjustments', 'ownOrParentBranch', 'filteredBranchName', 'fromDate', 'toDate'));
    }

    public function allAmounts(StockAdjustmentReportAllAmountRequest $request)
    {
        return $this->stockAdjustmentReportService->stockAdjustmentAmounts(request: $request);
    }
}
