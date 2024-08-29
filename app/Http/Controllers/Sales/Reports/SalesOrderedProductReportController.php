<?php

namespace App\Http\Controllers\Sales\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Sales\Reports\SalesOrderedProductReportService;
use App\Http\Requests\Sales\Reports\SalesOrderedProductIndexReport;
use App\Http\Requests\Sales\Reports\SalesOrderedProductPrintReport;

class SalesOrderedProductReportController extends Controller
{
    public function __construct(
        private SalesOrderedProductReportService $salesOrderedProductReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {}

    public function index(SalesOrderedProductIndexReport $request)
    {
        if ($request->ajax()) {

            return $this->salesOrderedProductReportService->salesOrderedProductReportTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.reports.sales_ordered_products_report.index', compact('branches', 'customerAccounts', 'ownBranchIdOrParentBranchId'));
    }

    public function print(SalesOrderedProductPrintReport $request)
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
        $filteredCustomerName = $request->customer_name;
        $filteredProductName = $request->search_product;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $orderProducts = $this->salesOrderedProductReportService->query(request: $request)->get();

        return view('sales.reports.sales_ordered_products_report.ajax_view.print', compact('orderProducts', 'fromDate', 'toDate', 'filteredBranchName', 'filteredCustomerName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
