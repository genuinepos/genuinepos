<?php

namespace App\Http\Controllers\report;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FinancialReportControllerReport extends Controller
{
    public function index()
    {
        $accounts = DB::table('accounts')->select(
            DB::raw('sum(opening_balance) as total_op_balance'),
            DB::raw('sum(balance) as total_balance'),
        )->get();

        $assets = DB::table('assets')->select(DB::raw('sum(total_value) as total_asset_value'))->get();

        $suppliers = DB::table('suppliers')->select(
            DB::raw('sum(total_purchase) as total_purchase'),
            DB::raw('sum(total_paid) as total_paid'),
            DB::raw('sum(total_purchase_due) as total_due'),
            DB::raw('sum(total_return) as total_return'),
            DB::raw('sum(total_purchase_return_due) as total_return_due'),
        )->get();

        $sales = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(paid) as total_paid'),
            DB::raw('sum(case when due > 0 then due end) as total_due'),
            DB::raw('sum(sale_return_amount) as total_return'),
            DB::raw('sum(sale_return_due) as total_return_due'),
        )->where('sales.status', 1)->get();

        $singleProducts = DB::table('products')->select(
            DB::raw('sum(quantity * product_price) as total_price'),
        )->where('is_combo', 0)->where('products.is_variant', 0)->get();

        $variantProducts = DB::table('product_variants')->select(
            DB::raw('sum(variant_quantity * variant_price) as total_price'),
        )->get();

        $totalStockValue = bcadd($singleProducts->sum('total_price') + $variantProducts->sum('total_price'), 0, 2);

        $adjustments = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjust_amount'),
            DB::raw('sum(recovered_amount) as total_recovered'),
        )->get();

        $expenses = DB::table('expanses')->select(
            DB::raw('sum(total_amount) as total_amount'),
            DB::raw('sum(paid) as total_paid'),
            DB::raw('sum(due) as due'),
        )->get();

        $loans = DB::table('loans')->select(
            DB::raw("sum(IF(type = '1', loan_amount, 0)) as total_pay_loan"),
            DB::raw("sum(IF(type = '1', total_paid, 0)) as total_pay_loan_paid"),
            DB::raw("sum(IF(type = '1', due, 0)) as total_pay_loan_due"),
            DB::raw("sum(IF(type = '2', loan_amount, 0)) as total_receive_loan"),
            DB::raw("sum(IF(type = '2', total_paid, 0)) as total_receive_loan_paid"),
            DB::raw("sum(IF(type = '2', due, 0)) as total_receive_loan_due"),
        )->get();

        return view(
            'reports.financial_report.index',
            compact(
                'accounts',
                'assets',
                'suppliers',
                'sales',
                'totalStockValue',
                'adjustments',
                'expenses',
                'loans',
            )
        );
    }

    public function print()
    {
        $accounts = DB::table('accounts')->select(
            DB::raw('sum(opening_balance) as total_op_balance'),
            DB::raw('sum(balance) as total_balance'),
        )->get();

        $assets = DB::table('assets')->select(DB::raw('sum(total_value) as total_asset_value'))->get();

        $suppliers = DB::table('suppliers')->select(
            DB::raw('sum(total_purchase) as total_purchase'),
            DB::raw('sum(total_paid) as total_paid'),
            DB::raw('sum(total_purchase_due) as total_due'),
            DB::raw('sum(total_return) as total_return'),
            DB::raw('sum(total_purchase_return_due) as total_return_due'),
        )->get();

        $sales = DB::table('sales')->select(
            DB::raw('sum(total_payable_amount) as total_sale'),
            DB::raw('sum(paid) as total_paid'),
            DB::raw('sum(case when due > 0 then due end) as total_due'),
            DB::raw('sum(sale_return_amount) as total_return'),
            DB::raw('sum(sale_return_due) as total_return_due'),
        )->where('sales.status', 1)->get();

        $singleProducts = DB::table('products')->select(
            DB::raw('sum(quantity * product_price) as total_price'),
        )->where('is_combo', 0)->where('products.is_variant', 0)->get();

        $variantProducts = DB::table('product_variants')->select(
            DB::raw('sum(variant_quantity * variant_price) as total_price'),
        )->get();

        $totalStockValue = bcadd($singleProducts->sum('total_price') + $variantProducts->sum('total_price'), 0, 2);

        $adjustments = DB::table('stock_adjustments')->select(
            DB::raw('sum(net_total_amount) as total_adjust_amount'),
            DB::raw('sum(recovered_amount) as total_recovered'),
        )->get();

        $expenses = DB::table('expanses')->select(
            DB::raw('sum(total_amount) as total_amount'),
            DB::raw('sum(paid) as total_paid'),
            DB::raw('sum(due) as due'),
        )->get();

        $loans = DB::table('loans')->select(
            DB::raw("sum(IF(type = '1', loan_amount, 0)) as total_pay_loan"),
            DB::raw("sum(IF(type = '1', total_paid, 0)) as total_pay_loan_paid"),
            DB::raw("sum(IF(type = '1', due, 0)) as total_pay_loan_due"),
            DB::raw("sum(IF(type = '2', loan_amount, 0)) as total_receive_loan"),
            DB::raw("sum(IF(type = '2', total_paid, 0)) as total_receive_loan_paid"),
            DB::raw("sum(IF(type = '2', due, 0)) as total_receive_loan_due"),
        )->get();
        
        return view(
            'reports.financial_report.print',
            compact(
                'accounts',
                'assets',
                'suppliers',
                'sales',
                'totalStockValue',
                'adjustments',
                'expenses',
                'loans',
            )
        );
    }
}
