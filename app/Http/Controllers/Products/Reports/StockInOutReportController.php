<?php

namespace App\Http\Controllers\Products\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Products\Reports\StockInOutReportService;
use App\Http\Requests\Products\Reports\StockInOutReportIndexRequest;
use App\Http\Requests\Products\Reports\StockInOutReportPrintRequest;

class StockInOutReportController extends Controller
{
    public function __construct(
        private StockInOutReportService $stockInOutReportService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(StockInOutReportIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->stockInOutReportService->stockInOutReportTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('product.reports.stock_in_out_report.index', compact('branches', 'customerAccounts', 'ownBranchIdOrParentBranchId'));
    }

    public function print(StockInOutReportPrintRequest $request)
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

        $stockInOuts = $this->stockInOutReportService->stockInOutQuery(request: $request)->get();

        return view(
            'product.reports.stock_in_out_report.ajax_view.print',
            compact(
                'stockInOuts',
                'ownOrParentBranch',
                'filteredBranchName',
                'filteredCustomerName',
                'filteredProductName',
                'fromDate',
                'toDate',
            )
        );
    }
}
