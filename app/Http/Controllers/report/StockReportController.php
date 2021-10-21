<?php

namespace App\Http\Controllers\report;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StockReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Stock report
    public function index()
    {
        $brands = DB::table('brands')->get(['id', 'name']);
        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
        $taxes = DB::table('taxes')->get(['id', 'tax_name']);
        $units = DB::table('units')->get(['id', 'name']);
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('reports.stock_report.index', compact('branches', 'brands', 'taxes', 'units', 'categories'));
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

        // $productStock = DB::table('products')
        //     ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
        //     ->leftJoin('product_branches', 'products.id', 'product_branches.product_id')
        //     ->leftJoin('product_branch_variants', 'products.id', 'product_branch_variants.product_id')
        //     ->leftJoin('branches', 'product_branches.branch_id', 'branches.id')
        //     ->leftJoin('product_warehouses', 'products.id', 'product_warehouses.product_id')
        //     ->leftJoin('warehouses', 'product_warehouses.warehouse_id', 'warehouses.id')
        //     ->leftJoin('branches as warehouse_branch', 'warehouse_branch.id', 'warehouses.branch_id')
        //     ->leftJoin('product_warehouse_variants', 'products.id', 'product_warehouse_variants.product_id')
        //     ->select(
        //         'products.name',
        //         'products.product_code',
        //         'products.product_price',
        //         'products.product_cost_with_tax',
        //         'products.mb_stock',
        //         'products.mb_total_sale',
        //         'product_variants.variant_name',
        //         'product_variants.variant_code',
        //         'product_variants.mb_stock as v_mb_stock',
        //         'product_variants.mb_total_sale as v_mb_total_sale',
        //         'branches.name as branch_name',
        //         'branches.branch_code',
        //         'product_branches.product_quantity as b_product_quantity',
        //         'product_branches.total_sale as b_total_sale',
        //         'product_branch_variants.variant_quantity as b_variant_quantity',
        //         'product_branch_variants.total_sale as bv_total_sale',
        //         'warehouses.warehouse_name',
        //         'warehouses.warehouse_code',
        //         'warehouse_branch.name as wb_name',
        //         'warehouse_branch.branch_code as wb_code',
        //         'product_warehouses.product_quantity as w_product_quantity',
        //         'product_warehouse_variants.variant_quantity as w_variant_quantity',
        //     )->get();

        $mb_stocks = DB::table('products')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->select(
                'units.code_name',
                'products.id',
                'products.name',
                'products.product_code',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.mb_stock',
                'products.mb_total_sale',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_variants.mb_stock as v_mb_stock',
                'product_variants.mb_total_sale as v_mb_total_sale',
            )->get();

        $branch_stock = DB::table('product_branches')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->leftJoin('branches', 'product_branches.branch_id', 'branches.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->select(
                'units.code_name',
                'branches.name as b_name',
                'branches.branch_code',
                'products.name',
                'products.product_code',
                'products.product_cost_with_tax',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_cost_with_tax',
                'product_variants.variant_price',
                'product_branches.product_quantity',
                'product_branches.total_sale',
                'product_branch_variants.variant_quantity',
                'product_branch_variants.total_sale as v_total_sale',
            )->get();

        return view('reports.stock_report.ajax_view.all_branch_stock', compact('mb_stocks', 'branch_stock'));
    }

    // Get all parent Category
    public function allParentCategories()
    {
        return Category::where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
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
