<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockInOutReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        $purchaseSaleChain = DB::table('purchase_sale_product_chains')
        ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
        ->leftJoin('products', 'sale_products.product_id', 'products.id')
        ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
        ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
        ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
        ->leftJoin('productions', 'purchase_products.production_id', 'productions.id')
        ->leftJoin('product_opening_stocks', 'purchase_products.product_opening_stock_id', 'product_opening_stocks.id')
        ->select(
            'sales.id as sale_id',
            'sales.date',
            'sales.invoice_id',
            'products.name',
            'purchase_sale_product_chains.sold_qty',
            'purchases.id as purchase_id',
            'purchases.invoice_id as purchase_inv',
            'purchases.date as purchase_date',
            'productions.reference_no as production_voucher_no',
            'productions.date as production_date',
            'product_opening_stocks.id as pos_id',
            'product_opening_stocks.create_at as pos_date',
            'purchase_products.net_unit_cost',
        )->get();
    }
}
