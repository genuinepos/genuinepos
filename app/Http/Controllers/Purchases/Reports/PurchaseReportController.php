<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\WarehouseService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Purchases\Reports\PurchaseReportService;
use App\Http\Requests\Purchases\Reports\PurchaseReportIndexRequest;
use App\Http\Requests\Purchases\Reports\PurchaseReportPrintRequest;

class PurchaseReportController extends Controller
{
    public function __construct(
        private PurchaseReportService $purchaseReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
    ) {
    }

    public function index(PurchaseReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->purchaseReportService->purchaseReportTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return view('purchase.reports.purchase_report.index', compact('branches', 'supplierAccounts'));
    }

    public function print(PurchaseReportPrintRequest $request)
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
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $purchases = $this->purchaseReportService->query(request: $request)->get();

        return view('purchase.reports.purchase_report.ajax_view.print', compact('purchases', 'ownOrParentBranch', 'filteredBranchName', 'filteredSupplierName', 'fromDate', 'toDate'));
    }
}
