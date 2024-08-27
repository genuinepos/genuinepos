<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\WarehouseService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Purchases\Reports\PurchaseOrderReportService;
use App\Http\Requests\Purchases\Reports\PurchaseOrderReportIndexRequest;
use App\Http\Requests\Purchases\Reports\PurchaseOrderReportPrintRequest;

class PurchaseOrderReportController extends Controller
{
    public function __construct(
        private PurchaseOrderReportService $purchaseOrderReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
    ) {}

    public function index(PurchaseOrderReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->purchaseOrderReportService->purchaseOrderProductReportTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts(ownBranchIdOrParentBranchId: $ownBranchIdOrParentBranchId, sortingByGroupNumber: 'desc');

        return view('purchase.reports.purchase_order_report.index', compact('branches', 'supplierAccounts'));
    }

    public function print(PurchaseOrderReportPrintRequest $request)
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

        $orders = $this->purchaseOrderReportService->query(request: $request)->get();

        return view('purchase.reports.purchase_order_report.ajax_view.print', compact('orders', 'ownOrParentBranch', 'filteredBranchName', 'filteredSupplierName', 'fromDate', 'toDate'));
    }
}
