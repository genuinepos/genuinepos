<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Hrm\DepartmentService;
use App\Services\Products\StockIssueProductService;
use App\Http\Requests\Products\StockIssueProductIndexRequest;

class StockIssueProductController extends Controller
{
    public function __construct(
        private StockIssueProductService $stockIssueProductService,
        private DepartmentService $departmentService,
        private BranchService $branchService,
    ) {
    }

    public function index(StockIssueProductIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->stockIssueProductService->stockIssuedProductsTable(request: $request);
        }

        $departments = $this->departmentService->departments()->select('id', 'name')->get();

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        return view('product.stock_issues.stock_issue_products.index', compact('departments', 'branches', 'ownBranchIdOrParentBranchId'));
    }
}
