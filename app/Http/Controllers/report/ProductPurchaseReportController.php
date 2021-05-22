<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductPurchaseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of supplier report
    public function index()
    {
        return view('reports.product_purchase_report.index');
    }

    public function getProductPurchaseReport(Request $request)
    {
        $purchaseProducts = '';
        $query = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
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
            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('purchase_products.product_variant_id', $request->variant_id);
        }

        if ($request->branch_id) {
            $query->where('purchases.branch_id', $request->branch_id);
        }

        if ($request->warehouse_id) {
            $query->where('purchases.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_id) {
            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $query->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        } else {
            $query->where('purchases.year', date('Y'));
        }

        $purchaseProducts = $query
            ->select(
                'purchase_products.purchase_id',
                'purchase_products.product_id',
                'purchase_products.product_variant_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity',
                'units.code_name as unit_code',
                'purchase_products.subtotal',
                'purchases.*',
                'products.name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'suppliers.name as supplier_name'
            )
            ->get();

        return view('reports.product_purchase_report.ajax_view.purchase_product_list', compact('purchaseProducts'));
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
            return view('reports.product_purchase_report.ajax_view.search_result', compact('products'));
        } else {
            return response()->json(['noResult' => 'no result']);
        }
    }
}
