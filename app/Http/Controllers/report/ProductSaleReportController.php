<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductSaleReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of supplier report
    public function index()
    {
        return view('reports.product_sale_report.index');
    }

    public function getProductSaleReport(Request $request)
    {
        $saleProducts = '';
        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('customers', 'sales.customer_id', 'customers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id');
            // ->where('purchases.branch_id', $request->branch_id)
            // ->where('purchases.supplier_id', $request->supplier_id)
            // ->where('purchases.warehouse_id', $request->warehouse_id)
            // ->select('purchases.*', 'products.name', 'product_variants.variant_name', 'suppliers.name as sup_name')
            // ->get();

        // if ($request->product_id) {
        //     $query->where('product_id', $request->product_id);
        // }

        if ($request->product_id) {
            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('sale_products.product_variant_id', $request->variant_id);
        }

        if ($request->branch_id) {
            $query->where('sales.branch_id', $request->branch_id);
        }

        if ($request->customer_id) {
            $query->where('sales.customer_id', $request->customer_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $query->whereBetween('sales.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        } else {
            $query->where('sales.year', date('Y'));
        }

        $saleProducts = $query
            ->select(
                'sale_products.sale_id',
                'sale_products.product_id',
                'sale_products.product_variant_id',
                'sale_products.unit_price_inc_tax',
                'sale_products.quantity',
                'units.code_name as unit_code',
                'sale_products.subtotal',
                'sales.*',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'customers.name as customer_name'
            )
            ->get();

        return view('reports.product_sale_report.ajax_view.sale_product_list', compact('saleProducts'));
    }

    // Search product 
    public function searchProduct($product_name)
    {
        $products = DB::table('products')
            ->where('name', 'like', "%{$product_name}%")
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )
            ->get();

        if (count($products) > 0) {
            return view('reports.product_sale_report.ajax_view.search_result', compact('products'));
        } else {
            return response()->json(['noResult' => 'no result']);
        }
    }
}
