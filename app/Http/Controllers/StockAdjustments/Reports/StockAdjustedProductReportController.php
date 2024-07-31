<?php

namespace App\Http\Controllers\StockAdjustments\Reports;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\StockAdjustments\Reports\StockAdjustedProductReportService;
use App\Http\Requests\StockAdjustments\Reports\StockAdjustedProductReportIndexRequest;
use App\Http\Requests\StockAdjustments\Reports\StockAdjustedProductReportPrintRequest;

class StockAdjustedProductReportController extends Controller
{
    public function __construct(private StockAdjustedProductReportService $stockAdjustedProductReportService, private BranchService $branchService)
    {
    }

    public function index(StockAdjustedProductReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->stockAdjustedProductReportService->stockAdjustedProductReportTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('stock_adjustments.reports.stock_adjusted_products_reports.index', compact('branches'));
    }

    public function print(StockAdjustedProductReportPrintRequest $request)
    {
        $ownOrParentBranch = '';
        $branchName = $this->branchService->branchName();

        $filteredBranchName = $request->branch_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $adjustmentProducts = $this->stockAdjustedProductReportService->query(request: $request)->get();

        return view('stock_adjustments.reports.stock_adjusted_products_reports.ajax_view.print', compact('adjustmentProducts', 'ownOrParentBranch', 'filteredBranchName', 'fromDate', 'toDate'));
    }
}
