<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Editor\Fields\DateTime;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }
    
    // Admin dashboard
    public function index()
    { 
        $thisWeek = Carbon::now()->startOfWeek().'~'.Carbon::now()->endOfWeek();
        $thisYear = Carbon::now()->startOfYear().'~'.Carbon::now()->endOfYear();
        $thisMonth = Carbon::now()->startOfMonth().'~'.Carbon::now()->endOfMonth();
        $toDay = Carbon::now().'~'.Carbon::now();
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('dashboard.dashboard_1', compact('branches', 'thisWeek', 'thisYear', 'thisMonth', 'toDay'));
    }

    // Get dashboard card data
    public function cardData(Request $request)
    {
        $totalSales = 0;
        $totalSaleDue = 0;
        $totalPurchase = 0;
        $totalPurchaseDue = 0;
        $totalExpense = 0;

        $purchases = '';
        $sales = '';
        $expenses = '';
        $products = '';
        $users = '';
        $adjustments = '';

        $userQuery = DB::table('admin_and_users');
        $productQuery = DB::table('product_branches');
        $purchaseQuery = DB::table('purchases')->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            DB::raw('sum(due) as total_due'),
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(due) as total_due')
        );

        $expenseQuery = DB::table('expanses')->select(
            DB::raw('sum(net_total_amount) as total_expense'),
        );

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
        );

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $purchaseQuery->where('purchases.branch_id', NULL);
                $saleQuery->where('sales.branch_id', NULL);
                $expenseQuery->where('expanses.branch_id', NULL);
                $userQuery->where('admin_and_users.branch_id', NULL);
                $adjustmentQuery->where('stock_adjustments.branch_id', NULL);
            } else {
                $purchaseQuery->where('purchases.branch_id', $request->branch_id);
                $saleQuery->where('sales.branch_id', $request->branch_id);
                $expenseQuery->where('expanses.branch_id', $request->branch_id);
                $userQuery->where('admin_and_users.branch_id', $request->branch_id);
                $adjustmentQuery->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if ($request->date_range) {
            $date_range = explode('~', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $saleQuery->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
            $purchaseQuery->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $expenseQuery->whereBetween('expanses.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            $adjustmentQuery->whereBetween('stock_adjustments.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        $sales = $saleQuery->groupBy('sales.id')->get();
        $totalSales = $sales->sum('total_sale');
        $totalSaleDue = $sales->sum('total_due');

        $purchases = $purchaseQuery->groupBy('purchases.id')->get();
        $totalPurchase = $purchases->sum('total_purchase');
        $totalPurchaseDue = $purchases->sum('total_due');

        $expenses = $expenseQuery->groupBy('expanses.id')->get();
        $totalExpense = $expenses->sum('total_expense');

        $products = $productQuery->count();
        $users = $userQuery->count();
        $adjustments = $adjustmentQuery->groupBy('stock_adjustments.id')->get();
        $total_adjustment = $adjustments->sum('total_adjustment');

        return response()->json([
            'total_sale' => $totalSales,
            'totalSaleDue' => $totalSaleDue,
            'totalPurchase' => $totalPurchase,
            'totalPurchaseDue' => $totalPurchaseDue,
            'totalExpense' => $totalExpense,
            'users' => $users,
            'products' => $products,
            'total_adjustment' => $total_adjustment,
        ]);

    }
    
    public function changeLang($lang)
    {
        session(['lang' => $lang]);
        return redirect()->back();
    }
}
