<?php

namespace App\Http\Controllers\Sales\Reports;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Http\Requests\Sales\Reports\SalesReportIndexRequest;
use App\Http\Requests\Sales\Reports\SalesReportPrintRequest;
use App\Services\Sales\Reports\ReceivedAgainstSalesReportService;

class ReceivedAgainstSalesReportController extends Controller
{
    public function __construct(
        private ReceivedAgainstSalesReportService $receivedAgainstSalesReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(SalesReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->receivedAgainstSalesReportService->receivedAgainstSalesReportTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.reports.received_against_sales_report.index', compact('branches', 'customerAccounts'));
    }

    public function print(SalesReportPrintRequest $request)
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

        $receivedAgainstSales = $this->receivedAgainstSalesReportService->query(request: $request)->get();

        return view('sales.reports.received_against_sales_report.ajax_view.print', compact('receivedAgainstSales', 'ownOrParentBranch', 'filteredBranchName', 'filteredCustomerName', 'fromDate', 'toDate'));
    }
}
