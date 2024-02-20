<?php

namespace App\Services\Accounts\Reports;

use App\Enums\AccountingVoucherType;
use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;

class ProfitLossService
{
    public function profitLossAmounts(mixed $branchId = null, mixed $childBranchId = null, ?string $fromDate = null, ?string $toDate = null, bool $getParentBranchData = true): array
    {
        $stockAdjustments = '';
        $sales = '';
        $saleReturns = '';
        $expanses = '';
        $saleProducts = '';
        $payrollPayments = '';

        $saleProductQuery = DB::table('purchase_sale_product_chains')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('products', 'purchase_sale_product_chains.sale_product_id', 'products.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        $adjustmentQuery = DB::table('stock_adjustments')
            ->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        $saleQuery = DB::table('sales')->where('sales.status', SaleStatus::Final->value)
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        $saleReturnQuery = DB::table('sale_returns')
            ->leftJoin('branches', 'sale_returns.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        $expenseQuery = DB::table('accounting_vouchers')->where('accounting_vouchers.voucher_type', AccountingVoucherType::Expense->value)
            ->leftJoin('branches', 'accounting_vouchers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        $payrollPaymentQuery = DB::table('accounting_vouchers')->where('accounting_vouchers.voucher_type', AccountingVoucherType::PayrollPayment->value)
            ->leftJoin('branches', 'accounting_vouchers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        if ($fromDate) {

            $fromDateYmd = date('Y-m-d', strtotime($fromDate));
            $toDateYmd = $toDate ? date('Y-m-d', strtotime($toDate)) : $fromDateYmd;
            $dateRange = [Carbon::parse($fromDateYmd), Carbon::parse($toDateYmd)->endOfDay()];

            $adjustmentQuery->whereBetween('stock_adjustments.date_ts', $dateRange);
            $saleQuery->whereBetween('sales.sale_date_ts', $dateRange);
            $saleReturnQuery->whereBetween('sale_returns.date_ts', $dateRange);
            $expenseQuery->whereBetween('accounting_vouchers.date_ts', $dateRange);
            $payrollPaymentQuery->whereBetween('accounting_vouchers.date_ts', $dateRange);
            $saleProductQuery->whereBetween('sales.sale_date_ts', $dateRange);
        }

        if ($branchId && !isset($childBranchId)) {

            $__branchId = $branchId == 'NULL' ? null : $branchId;

            if ($getParentBranchData == true) {

                $adjustmentQuery->where(function ($subQuery) use ($__branchId) {
                    $subQuery->where('stock_adjustments.branch_id', $__branchId)
                        ->orWhere('parentBranch.id', $__branchId);
                });
            } else {

                $adjustmentQuery->where('stock_adjustments.branch_id', $__branchId);
            }

            if ($getParentBranchData == true) {

                $saleQuery->where(function ($subQuery) use ($__branchId) {
                    $subQuery->where('sales.branch_id', $__branchId)
                        ->orWhere('parentBranch.id', $__branchId);
                });
            } else {

                $saleQuery->where('sales.branch_id', $__branchId);
            }

            if ($getParentBranchData == true) {

                $saleReturnQuery->where(function ($subQuery) use ($__branchId) {
                    $subQuery->where('sale_returns.branch_id', $__branchId)
                        ->orWhere('parentBranch.id', $__branchId);
                });
            } else {

                $saleReturnQuery->where('sale_returns.branch_id', $__branchId);
            }

            if ($getParentBranchData == true) {

                $expenseQuery->where(function ($subQuery) use ($__branchId) {
                    $subQuery->where('accounting_vouchers.branch_id', $__branchId)
                        ->orWhere('parentBranch.id', $__branchId);
                });
            } else {

                $expenseQuery->where('accounting_vouchers.branch_id', $__branchId);
            }

            if ($getParentBranchData == true) {

                $payrollPaymentQuery->where(function ($subQuery) use ($__branchId) {
                    $subQuery->where('accounting_vouchers.branch_id', $__branchId)
                        ->orWhere('parentBranch.id', $__branchId);
                });
            } else {

                $payrollPaymentQuery->where('accounting_vouchers.branch_id', $__branchId);
            }

            if ($getParentBranchData == true) {

                $saleProductQuery->where(function ($subQuery) use ($__branchId) {
                    $subQuery->where('sales.branch_id', $__branchId)
                        ->orWhere('parentBranch.id', $__branchId);
                });
            } else {

                $saleProductQuery->where('sales.branch_id', $__branchId);
            }
        }

        if ($childBranchId) {

            $adjustmentQuery->where('stock_adjustments.branch_id', $childBranchId);
            $saleQuery->where('sales.branch_id', $childBranchId);
            $saleReturnQuery->where('sale_returns.branch_id', $childBranchId);
            $expenseQuery->where('accounting_vouchers.branch_id', $childBranchId);
            $payrollPaymentQuery->where('accounting_vouchers.branch_id', $childBranchId);
            $saleProductQuery->where('sales.branch_id', $childBranchId);
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $adjustmentQuery->where('stock_adjustments.branch_id', auth()->user()->branch_id);
            $saleQuery->where('sales.branch_id', auth()->user()->branch_id);
            $saleReturnQuery->where('sale_returns.branch_id', auth()->user()->branch_id);
            $expenseQuery->where('accounting_vouchers.branch_id', auth()->user()->branch_id);
            $payrollPaymentQuery->where('accounting_vouchers.branch_id', auth()->user()->branch_id);
            $saleProductQuery->where('sales.branch_id', auth()->user()->branch_id);
        }

        $saleProducts = $saleProductQuery->select(
            DB::raw('SUM(purchase_products.net_unit_cost * purchase_sale_product_chains.sold_qty) as total_unit_cost'),
            DB::raw('SUM(sale_products.unit_tax_amount * purchase_sale_product_chains.sold_qty) as total_unit_tax_amount'),
        )->get();

        $sales = $saleQuery->select(
            DB::raw('sum(sales.total_invoice_amount) as total_sale'),
            DB::raw('sum(sales.order_tax_amount) as total_order_tax'),
        )->get();

        $stockAdjustments = $adjustmentQuery->select(
            DB::raw('sum(stock_adjustments.net_total_amount) as total_adjustment'),
            DB::raw('sum(stock_adjustments.recovered_amount) as total_recovered')
        )->get();

        $saleReturns = $saleReturnQuery->select(DB::raw('sum(sale_returns.total_return_amount) as total_sale_return'))->get();
        $expense = $expenseQuery->select(DB::raw('sum(accounting_vouchers.total_amount) as total_expense'))->get();
        $payrollPayments = $payrollPaymentQuery->select(DB::raw('sum(accounting_vouchers.total_amount) as total_payroll_payment'))->get();

        $totalSale = $sales->sum('total_sale');
        $totalUnitTax = $saleProducts->sum('total_unit_tax_amount');
        $totalOrderTax = $sales->sum('total_order_tax');
        $totalUnitCost = $saleProducts->sum('total_unit_cost');

        $grossProfit = $totalSale - $totalUnitTax - $totalOrderTax - $totalUnitCost;

        $totalStockAdjustmentAmount = $stockAdjustments->sum('total_adjustment');
        $totalStockAdjustmentRecovered = $stockAdjustments->sum('total_recovered');
        $totalSaleReturn = $saleReturns->sum('total_sale_return');
        $totalExpense = $expense->sum('total_expense');
        $totalPayrollPayment = $payrollPayments->sum('total_payroll_payment');

        $netProfit = $grossProfit - $totalStockAdjustmentAmount + $totalStockAdjustmentRecovered - $totalSaleReturn - $totalExpense - $totalPayrollPayment;

        return [
            'totalSale' => $totalSale,
            'totalUnitTax' => $totalUnitTax,
            'totalOrderTax' => $totalOrderTax,
            'totalUnitCost' => $totalUnitCost,
            'grossProfit' => $grossProfit,
            'totalStockAdjustmentAmount' => $totalStockAdjustmentAmount,
            'totalStockAdjustmentRecovered' => $totalStockAdjustmentRecovered,
            'totalSaleReturn' => $totalSaleReturn,
            'totalExpense' => $totalExpense,
            'totalPayrollPayment' => $totalPayrollPayment,
            'netProfit' => $netProfit,
        ];
    }
}
