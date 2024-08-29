<?php

namespace App\Http\Controllers\Sales\Reports;

use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Sales\Reports\SoldProductReportService;
use App\Http\Requests\Sales\Reports\SoldProductReportIndexRequest;
use App\Http\Requests\Sales\Reports\SoldProductReportPrintRequest;

class SoldProductReportController extends Controller
{
    public function __construct(
        private SoldProductReportService $soldProductReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {}

    public function index(SoldProductReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->soldProductReportService->soldProductReportTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.reports.sold_products_report.index', compact('branches', 'customerAccounts', 'ownBranchIdOrParentBranchId'));
    }

    public function print(SoldProductReportPrintRequest $request)
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

        $saleProducts = $this->soldProductReportService->query(request: $request)->get();

        return view('sales.reports.sold_products_report.ajax_view.print', compact('saleProducts', 'fromDate', 'toDate', 'filteredBranchName', 'filteredCustomerName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
