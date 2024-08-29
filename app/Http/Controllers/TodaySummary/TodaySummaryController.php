<?php

namespace App\Http\Controllers\TodaySummary;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use App\Services\TodaySummary\TodaySummaryService;

class TodaySummaryController extends Controller
{
    public function __construct(private TodaySummaryService $todaySummaryService, private BranchService $branchService) {}

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('today_summery'), 403);
        $branchId = $request->branch_id;

        $todaySummaries = $this->todaySummaryService->prepare(request: $request);

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('today_summary.index', compact('todaySummaries', 'branches', 'branchId'));
    }

    public function print(Request $request)
    {
        abort_if(!auth()->user()->can('today_summery'), 403);

        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;

        $todaySummaries = $this->todaySummaryService->prepare(request: $request);

        return view('today_summary.print', compact('todaySummaries', 'ownOrParentBranch', 'filteredBranchName'));
    }
}
