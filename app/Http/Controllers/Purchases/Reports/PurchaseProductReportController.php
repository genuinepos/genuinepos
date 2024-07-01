<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\WarehouseService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Purchases\Reports\PurchaseProductReportService;
use App\Http\Requests\Purchases\Reports\PurchaseProductReportIndexRequest;
use App\Http\Requests\Purchases\Reports\PurchaseProductReportPrintRequest;

class PurchaseProductReportController extends Controller
{
    public function __construct(
        private PurchaseProductReportService $purchaseProductReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
    ) {
    }

    public function index(PurchaseProductReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->purchaseProductReportService->purchaseProductReportTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.reports.purchased_product_report.index', compact('branches', 'supplierAccounts', 'ownBranchIdOrParentBranchId'));
    }

    public function print(PurchaseProductReportPrintRequest $request)
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
        $filteredSupplierName = $request->supplier_name;
        $filteredProductName = $request->search_product;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $purchaseProducts = $this->purchaseProductReportService->query(request: $request)->get();

        return view('purchase.reports.purchased_product_report.ajax_view.print', compact('purchaseProducts', 'fromDate', 'toDate', 'filteredBranchName', 'filteredSupplierName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
