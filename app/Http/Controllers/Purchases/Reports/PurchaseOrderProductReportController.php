<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\WarehouseService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Purchases\Reports\PurchaseOrderProductReportService;
use App\Http\Requests\Purchases\Reports\PurchaseOrderProductReportIndexRequest;
use App\Http\Requests\Purchases\Reports\PurchaseOrderProductReportPrintRequest;

class PurchaseOrderProductReportController extends Controller
{
    public function __construct(
        private PurchaseOrderProductReportService $purchaseOrderProductReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
    ) {}

    public function index(PurchaseOrderProductReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->purchaseOrderProductReportService->purchaseOrderProductReportTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return view('purchase.reports.purchase_ordered_products_report.index', compact('branches', 'supplierAccounts', 'ownBranchIdOrParentBranchId'));
    }

    public function print(PurchaseOrderProductReportPrintRequest $request)
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

        $purchaseOrderProducts = $this->purchaseOrderProductReportService->query(request: $request)->get();

        return view('purchase.reports.purchase_ordered_products_report.ajax_view.print', compact('purchaseOrderProducts', 'fromDate', 'toDate', 'filteredBranchName', 'filteredSupplierName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
