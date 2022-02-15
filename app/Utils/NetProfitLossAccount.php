<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;


class NetProfitLossAccount
{
    public function netLossProfit(array $request = NULL)
    {
        $purchases = DB::table('purchases')
            ->select(
                DB::raw('SUM(total_purchase_amount) as total_purchase'),
                DB::raw('SUM(purchase_tax_amount) as total_pur_order_tax'),
            )
            ->where('purchases.branch_id', auth()->user()->branch_id)->get();

        $sales = DB::table('sales')
            ->select(
                DB::raw('SUM(total_payable_amount) as total_sale'),
                DB::raw('SUM(order_tax_amount) as total_sale_order_tax'),
            )
            ->where('sales.branch_id', auth()->user()->branch_id)->get();

        $individualProductPurchaseTax = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->select(DB::raw('SUM(unit_tax) as total_pur_pro_tax'))
            ->where('purchases.branch_id', auth()->user()->branch_id)
            ->get();

        $individualProductSaleTax = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->select(DB::raw('SUM(unit_tax_amount) as total_sale_pro_tax'))
            ->where('purchases.branch_id', auth()->user()->branch_id)
            ->get();

        $expenses = DB::table('expanses')->select(DB::raw('SUM(net_total_amount) as total_expense'))
            ->where('expanses.branch_id', auth()->user()->branch_id)->get();
    }
}
