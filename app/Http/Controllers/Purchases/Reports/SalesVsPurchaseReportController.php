<?php

namespace App\Http\Controllers\Purchases\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\Purchases\Reports\SalesVsPurchaseReportService;
use App\Http\Requests\Purchases\Reports\SalesVsPurchaseReportIndexRequest;

class SalesVsPurchaseReportController extends Controller
{
    public function __construct(
        private SalesVsPurchaseReportService $purchaseVsSalesReportService,
        private BranchService $branchService,
    ) {}

    public function index(SalesVsPurchaseReportIndexRequest $request)
    {
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('purchase.reports.sales_vs_purchase_report.index', compact('branches'));
    }

    public function purchaseVsSalesAmounts(Request $request)
    {
        $salesVsPurchaseAmounts = $this->purchaseVsSalesReportService->salesVsPurchaseAmounts(request: $request);
        return view('purchase.reports.sales_vs_purchase_report.ajax_view.sales_vs_purchase_amount', compact('salesVsPurchaseAmounts'));
    }

    public function print(Request $request)
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
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $salesVsPurchaseAmounts = $this->purchaseVsSalesReportService->salesVsPurchaseAmounts(request: $request);
        return view('purchase.reports.sales_vs_purchase_report.ajax_view.sales_vs_purchase_print', compact('salesVsPurchaseAmounts', 'ownOrParentBranch', 'filteredBranchName', 'fromDate', 'toDate'));
    }
}
