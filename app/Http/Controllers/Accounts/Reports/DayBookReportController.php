<?php

namespace App\Http\Controllers\Accounts\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Accounts\Reports\DayBook\DayBookReportService;
use App\Services\Setups\BranchService;

class DayBookReportController extends Controller
{
    public function __construct(private DayBookReportService $dayBookReportService, private BranchService $branchService)
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('day_book'), 403);

        if ($request->ajax()) {

            return $this->dayBookReportService->daybookTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('accounting.reports.day_book.index', compact('branches'));
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
        $filteredVoucherName = $request->voucher_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $entries = $this->dayBookReportService->daybookEntriesQuery(request: $request)->get();

        return view('accounting.reports.day_book.ajax_view.print', compact('entries', 'request', 'fromDate', 'toDate', 'filteredBranchName', 'filteredVoucherName'));
    }
}
