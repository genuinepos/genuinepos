<?php

namespace App\Http\Controllers\Manufacturing\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Manufacturing\Reports\IngredientReportService;
use App\Http\Requests\Manufacturing\Reports\IngredientReportIndexRequest;
use App\Http\Requests\Manufacturing\Reports\IngredientReportPrintRequest;

class IngredientReportController extends Controller
{
    public function __construct(private IngredientReportService $ingredientReportService, private BranchService $branchService)
    {
    }

    public function index(IngredientReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->ingredientReportService->ingredientReportTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('manufacturing.reports.ingredients_report.index', compact('branches', 'ownBranchIdOrParentBranchId'));
    }

    public function print(IngredientReportPrintRequest $request)
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

        $ingredients = $this->ingredientReportService->query(request: $request)->get();

        return view('manufacturing.reports.ingredients_report.ajax_view.print', compact('ingredients', 'fromDate', 'toDate', 'filteredBranchName', 'filteredStatusName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
