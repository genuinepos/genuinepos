<?php

namespace App\Http\Controllers\Sales\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Sales\Reports\SalesReturnedProductReportService;
use App\Http\Requests\Sales\Reports\SalesReturnedProductReportIndexRequest;
use App\Http\Requests\Sales\Reports\SalesReturnedProductReportPrintRequest;

class SalesReturnedProductReportController extends Controller
{
    public function __construct(
        private SalesReturnedProductReportService $salesReturnedProductReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {}

    public function index(SalesReturnedProductReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->salesReturnedProductReportService->salesReturnedProductReportTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.reports.sales_returned_products_report.index', compact('branches', 'customerAccounts', 'ownBranchIdOrParentBranchId'));
    }

    public function print(SalesReturnedProductReportPrintRequest $request)
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
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $salesReturnProducts = $this->salesReturnedProductReportService->query(request: $request)->get();

        return view('sales.reports.sales_returned_products_report.ajax_view.print', compact('salesReturnProducts', 'ownOrParentBranch', 'filteredBranchName', 'filteredCustomerName', 'fromDate', 'toDate'));
    }
}
