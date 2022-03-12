<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonAjaxCallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function branchAuthenticatedUsers($branchId)
    {
        $branch_id = $branchId != 'NULL' ? $branchId : NULL;
        return DB::table('admin_and_users')
            ->where('branch_id', $branch_id)
            ->where('allow_login', 1)->get();
    }

    public function categorySubcategories($categoryId)
    {
        return DB::table('categories')->where('parent_category_id', $categoryId)->select('id', 'name')->get();
    }

    public function onlySearchProductForReports($product_name)
    {
        $products = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->where('name', 'like', "%{$product_name}%")
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->select(
                'products.id as product_id',
                'products.name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )->get();

        if (count($products) > 0) {

            return view('reports.product_purchase_report.ajax_view.search_result', compact('products'));
        } else {
            
            return response()->json(['noResult' => 'no result']);
        }
    }

    public function searchFinalSaleInvoices($invoiceId)
    {
        $invoices = DB::table('sales')
            ->where('branch_id', auth()->user()->branch_id)
            ->where('status', 1)->where('invoice_id', 'like', "%{$invoiceId}%")
            ->select('id', 'invoice_id')->get();

        if (count($invoices) > 0) {

            return view('common_ajax_view.invoice_search_list', compact('invoices'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }
}
