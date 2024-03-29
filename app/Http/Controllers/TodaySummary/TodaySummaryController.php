<?php

namespace App\Http\Controllers\TodaySummary;

use App\Enums\RoleType;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Accounts\Reports\ProfitLossService;

class TodaySummaryController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private ProfitLossService $profitLossService
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('today_summery'), 403);
        $branchId = $request->branch_id;

        $todaySummaries = $this->prepare($request);

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

        $todaySummaries = $this->prepare($request);

        return view('today_summary.print', compact('todaySummaries', 'ownOrParentBranch', 'filteredBranchName'));
    }

    private function prepare($request)
    {
        $purchaseQuery = DB::table('purchases')->select(
            DB::raw('sum(total_purchase_amount) as total_purchase'),
            DB::raw('sum(shipment_charge) as total_shipment_charge'),
            DB::raw('sum(due) as total_due')
        )->where('purchase_status', PurchaseStatus::Purchase->value);

        $paymentQuery = DB::table('accounting_vouchers')
            ->where('voucher_type', AccountingVoucherType::Payment->value)
            ->select(DB::raw('sum(total_amount) as total_paid'));

        $purchaseReturnQuery = DB::table('purchase_returns')->select(
            DB::raw('sum(total_return_amount) as total_return')
        );

        $saleQuery = DB::table('sales')->select(
            DB::raw('sum(total_invoice_amount) as total_sale'),
            DB::raw('sum(order_discount) as total_discount'),
            DB::raw('sum(shipment_charge) as total_shipment_charge'),
            DB::raw('sum(due) as total_due'),
        )->where('sales.status', SaleStatus::Final->value);

        $receiptQuery = DB::table('accounting_vouchers')
            ->where('voucher_type', AccountingVoucherType::Payment->value)
            ->select(DB::raw('sum(total_amount) as total_received'));

        $saleReturnQuery = DB::table('sale_returns')
            ->select(DB::raw('sum(total_return_amount) as total_return'));

        $expenseQuery = DB::table('accounting_vouchers')
            ->where('voucher_type', AccountingVoucherType::Expense->value)
            ->select(DB::raw('sum(total_amount) as total_expense'));

        $adjustmentQuery = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjustment'),
            DB::raw('sum(recovered_amount) as total_recovered')
        );

        $payrollQuery = DB::table('accounting_vouchers')
            ->where('voucher_type', AccountingVoucherType::PayrollPayment->value)
            ->select(DB::raw('sum(total_amount) as total_payroll_payment'));

        $branchId = null;
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {
                $branchId = 'NULL';
                $purchaseQuery->where('branch_id', null);
                $paymentQuery->where('branch_id', null);
                $purchaseReturnQuery->where('branch_id', null);
                $saleQuery->where('branch_id', null);
                $receiptQuery->where('branch_id', null);
                $saleReturnQuery->where('branch_id', null);
                $expenseQuery->where('branch_id', null);
                $adjustmentQuery->where('branch_id', null);
                $payrollQuery->where('branch_id', null);
            } else {

                $branchId = $request->branch_id;
                $purchaseQuery->where('branch_id', $request->branch_id);
                $paymentQuery->where('branch_id', $request->branch_id);
                $purchaseReturnQuery->where('branch_id', $request->branch_id);
                $saleQuery->where('branch_id', $request->branch_id);
                $receiptQuery->where('branch_id', $request->branch_id);
                $saleReturnQuery->where('branch_id', $request->branch_id);
                $expenseQuery->where('branch_id', $request->branch_id);
                $adjustmentQuery->where('branch_id', $request->branch_id);
                $payrollQuery->where('branch_id', $request->branch_id);
            }
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $branchId = auth()->user()->branch_id;
            $purchaseQuery->where('branch_id', auth()->user()->branch_id);
            $paymentQuery->where('branch_id', auth()->user()->branch_id);
            $purchaseReturnQuery->where('branch_id', auth()->user()->branch_id);
            $saleQuery->where('branch_id', auth()->user()->branch_id);
            $receiptQuery->where('branch_id', auth()->user()->branch_id);
            $saleReturnQuery->where('branch_id', auth()->user()->branch_id);
            $expenseQuery->where('branch_id', auth()->user()->branch_id);
            $adjustmentQuery->where('branch_id', auth()->user()->branch_id);
            $payrollQuery->where('branch_id', auth()->user()->branch_id);
        }

        $purchases = $purchaseQuery->whereDate('report_date', date('Y-m-d'))->get();
        $payments = $paymentQuery->whereDate('date_ts', date('Y-m-d'))->get();
        $purchaseReturns = $purchaseReturnQuery->whereDate('date_ts', date('Y-m-d'))->get();
        $sales = $saleQuery->whereDate('sale_date_ts', date('Y-m-d'))->get();
        $receipts = $receiptQuery->whereDate('date_ts', date('Y-m-d'))->get();
        $salesReturns = $saleReturnQuery->whereDate('date_ts', date('Y-m-d'))->get();
        $expenses = $expenseQuery->whereDate('date_ts', date('Y-m-d'))->get();
        $stockAdjustments = $adjustmentQuery->whereDate('date_ts', date('Y-m-d'))->get();
        $payrollPayments = $payrollQuery->whereDate('date_ts', date('Y-m-d'))->get();

        $profitLoss = $this->profitLossService->profitLossAmounts(branchId: $branchId, fromDate: date('Y-m-d'), toDate: date('Y-m-d'), getParentBranchData: false);

        return [
            'totalPurchase' => $purchases->sum('total_purchase'),
            'totalPurchaseShipmentCharge' => $purchases->sum('total_shipment_charge'),
            'totalPurchaseReturn' =>  $purchaseReturns->sum('total_return'),
            'totalPurchaseAfterReturn' => $purchases->sum('total_purchase') - $purchaseReturns->sum('total_return'),
            'totalPurchaseDue' => $purchases->sum('total_due'),
            'totalPayment' => $payments->sum('total_paid'),
            'totalStockAdjustment' => $stockAdjustments->sum('total_adjustment'),
            'totalStockAdjustmentRecovered' => $stockAdjustments->sum('total_recovered'),
            'totalSales' => $sales->sum('total_sale'),
            'totalSaleDiscount' => $sales->sum('total_discount'),
            'totalSaleShipmentCharge' => $sales->sum('total_shipment_charge'),
            'totalSalesReturn' => $salesReturns->sum('total_return'),
            'totalSalesAfterReturn' => $sales->sum('total_sale') - $salesReturns->sum('total_return'),
            'totalReceived' => $receipts->sum('total_received'),
            'totalSalesDue' => $sales->sum('total_due'),
            'totalExpense' => $expenses->sum('total_expense'),
            'totalPayrollPayment' => $payrollPayments->sum('total_payroll_payment'),
            'netProfit' => $profitLoss['netProfit'],
        ];
    }
}
