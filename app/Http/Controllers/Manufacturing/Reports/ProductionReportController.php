<?php

namespace App\Http\Controllers\Manufacturing\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Manufacturing\Reports\ProductionReportService;
use App\Http\Requests\Manufacturing\Reports\ProductionReportIndexRequest;
use App\Http\Requests\Manufacturing\Reports\ProductionReportPrintRequest;

class ProductionReportController extends Controller
{
    public function __construct(private ProductionReportService $productionReportService, private BranchService $branchService)
    {
    }

    public function index(ProductionReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->productionReportService->productionReportTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('manufacturing.reports.production_report.index', compact('branches', 'ownBranchIdOrParentBranchId'));
    }

    public function print(ProductionReportPrintRequest $request)
    {
        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredStatusName = $request->status_name;
        $filteredProductName = $request->search_product;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $productions = $this->productionReportService->query(request: $request)->get();

        return view('manufacturing.reports.production_report.ajax_view.print', compact('productions', 'fromDate', 'toDate', 'filteredBranchName', 'filteredStatusName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
