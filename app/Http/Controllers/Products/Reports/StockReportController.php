<?php

namespace App\Http\Controllers\Products\Reports;

use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\BranchService;
use App\Services\Products\BrandService;
use App\Services\Products\CategoryService;
use App\Services\Products\Reports\StockReportService;
use App\Http\Requests\Products\Reports\StockReportIndexRequest;
use App\Http\Requests\Products\Reports\StockReportBranchStockRequest;
use App\Http\Requests\Products\Reports\StockReportWarehouseStockRequest;
use App\Http\Requests\Products\Reports\StockReportBranchStockPrintRequest;

class StockReportController extends Controller
{
    public function __construct(
        private StockReportService $stockReportService,
        private CategoryService $categoryService,
        private BrandService $brandService,
        private UnitService $unitService,
        private BranchService $branchService,
    ) {
    }

    public function index(StockReportIndexRequest $request)
    {
        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get(['id', 'name']);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $units = $this->unitService->units()->get(['id', 'name', 'code_name']);
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('product.reports.stock_report.index', compact('branches', 'brands', 'units', 'categories'));
    }

    public function branchStock(StockReportBranchStockRequest $request)
    {
        if ($request->ajax()) {

            return $this->stockReportService->branchStockTable(request: $request);
        }
    }

    // Get all product stock **requested by ajax**
    public function warehouseStock(StockReportWarehouseStockRequest $request)
    {
        if ($request->ajax()) {

            return $this->stockReportService->warehouseStockTable(request: $request);
        }
    }

    public function branchStockPrint(StockReportBranchStockPrintRequest $request)
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
        $filteredCategoryName = $request->category_name;
        $filteredBrandName = $request->brand_name;
        $filteredUnitName = $request->unit_name;

        $branchStocks = $this->stockReportService->branchStockQuery(request: $request)->get();

        return view('product.reports.stock_report.ajax_view.branch_stock_print', compact('branchStocks', 'ownOrParentBranch', 'filteredBranchName', 'filteredCategoryName', 'filteredBrandName', 'filteredUnitName'));
    }
}
