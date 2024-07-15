<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Dashboard\DashboardService;

// define('TODAY_DATE', Carbon::today());

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService, private BranchService $branchService)
    {
    }

    public function index()
    {
        $thisWeek = Carbon::now()->startOfWeek()->format('Y-m-d') . '~' . Carbon::now()->endOfWeek()->format('Y-m-d');
        $thisYear = Carbon::now()->startOfYear()->format('Y-m-d') . '~' . Carbon::now()->endOfYear()->format('Y-m-d');
        $thisMonth = Carbon::now()->startOfMonth()->format('Y-m-d') . '~' . Carbon::now()->endOfMonth()->format('Y-m-d');
        $toDay = Carbon::now()->format('Y-m-d') . '~' . Carbon::now()->endOfDay()->format('Y-m-d');

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ?
            auth()->user()?->branch?->parent_branch_id :
            auth()->user()->branch_id;

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('dashboard.dashboard_1', compact('branches', 'thisWeek', 'thisYear', 'thisMonth', 'toDay'));
    }

    public function cardData(Request $request)
    {
        return $this->dashboardService->dashboardCardData($request);
    }

    public function stockAlert(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->stockAlertProductsTable($request);
        }
    }

    public function salesOrder(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->salesOrderTable(request: $request);
        }
    }

    public function salesDueInvoices(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->salesDueInvoicesTable(request: $request);
        }
    }

    public function purchaseDueInvoices(Request $request)
    {
        if ($request->ajax()) {

            return $this->dashboardService->purchaseDueInvoicesTable(request: $request);
        }
    }

    public function changeLang($lang)
    {
        session(['lang' => $lang]);
        return redirect()->back();
    }
}
