<?php

namespace App\Http\Controllers\Sales\Reports;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Sales\Reports\SalesOrderReportService;
use App\Http\Requests\Sales\Reports\SalesOrderReportIndexRequest;
use App\Http\Requests\Sales\Reports\SalesOrderReportPrintRequest;

class SalesOrderReportController extends Controller
{
    public function __construct(
        private SalesOrderReportService $salesOrderReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(SalesOrderReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->salesOrderReportService->salesOrderReportTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.reports.sales_order_report.index', compact('branches', 'customerAccounts'));
    }

    public function print(SalesOrderReportPrintRequest $request)
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

        $orders = $this->salesOrderReportService->query(request: $request)->get();

        return view('sales.reports.sales_order_report.ajax_view.print', compact('orders', 'ownOrParentBranch', 'filteredBranchName', 'filteredCustomerName', 'fromDate', 'toDate'));
    }
}
