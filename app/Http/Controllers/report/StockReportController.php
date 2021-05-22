<?php

namespace App\Http\Controllers\report;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductBranch;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Stock report
    public function index()
    {
        return view('reports.stock_report.index');
    }

    // Get all product stock **requested by ajax**
    public function allProducts()
    {
        $products = Product::with(
            [
                'product_variants',
                'sale_products',
                'sale_products.sale',
                'product_variants.sale_variants',
                'product_variants.sale_variants.sale',
                'tax',
                'unit'
            ]
        )->get();
        return view('reports.stock_report.ajax_view.product_list', compact('products'));
    }

    // Get all parent Cateogry
    public function allParentCategories()
    {
        $categories = Cache::rememberForever('all-parent-categories', function () {
            return Category::where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
        });

        return response()->json($categories);
    }

    // Filter product stocks
    public function filterStock(Request $request)
    {
        if ($request->branch_id) {
            $branchProducts = ProductBranch::with(
                [
                    'product',
                    'product.tax',
                    'product.unit',
                    'product.sale_products',
                    'product.sale_products.sale',
                    'product_branch_variants',
                    'product_branch_variants.product_variant',
                    'product_branch_variants.product_variant.sale_variants',
                    'product_branch_variants.product_variant.sale_variants.sale',
                ]
            )->where('branch_id', $request->branch_id)->get();
            return view('reports.stock_report.ajax_view.branch_stock_list', compact('branchProducts'));
        }
        
        $products = '';
        $product_query = Product::with(
            [
                'product_variants',
                'sale_products',
                'sale_products.sale',
                'product_variants.sale_variants',
                'product_variants.sale_variants.sale',
                'tax',
                'unit'
            ]
        );

        if ($request->category_id) {
            $product_query->where('category_id', $request->category_id);
        }

        if ($request->brand_id) {
            $product_query->where('brand_id', $request->brand_id);
        }

        if ($request->tax_id) {
            $product_query->where('tax_id', $request->tax_id);
        }

        if ($request->unit_id) {
            $product_query->where('unit_id', $request->unit_id);
        }

        $products = $product_query->get();
        return view('reports.stock_report.ajax_view.product_list', compact('products'));
    }
}
