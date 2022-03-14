<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Models\DiscountProduct;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $brands = DB::table('brands')->select('id', 'name')->get();
        $categories = DB::table('categories')->where('parent_category_id', NULL)->select('id', 'name')->get();

        $products = DB::table('product_branches')
        ->where('product_branches.branch_id', auth()->user()->branch_id)
        ->leftJoin('products', 'product_branches.product_id', 'products.id')
        ->select('products.id', 'products.name', 'products.product_code')->get();

        $price_groups = DB::table('price_groups')
                    ->select('id', 'name')->get();

        return view('sales.discounts.index', compact('brands', 'categories', 'products', 'price_groups'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'priority' => 'required',
            "start_at"    => "required",
            "end_at"  => "required",
            "discount_type"  => "required",
            "discount_amount"  => "required",
        ]);

        $addDiscount = new Discount();
        $addDiscount->name = $request->name;
        $addDiscount->priority = $request->priority;
        $addDiscount->start_at = date('Y-m-d', strtotime($request->start_at));
        $addDiscount->end_at = date('Y-m-d', strtotime($request->end_at));
        $addDiscount->discount_type = $request->discount_type;
        $addDiscount->discount_amount = $request->discount_amount;
        $addDiscount->price_group_id = $request->price_group_id;
        $addDiscount->save();
        
        if (count($request->product_ids) > 0) {
            
            foreach ($request->product_ids as $product_id) {
                
                $addDiscountProduct = new DiscountProduct();
                $addDiscountProduct->discount_id = $addDiscount->id;
                $addDiscountProduct->product_id = $product_id;
                $addDiscountProduct->save();
            }
        }else {

            $addDiscount->brand_id = $request->brand_id;
            $addDiscount->category_id = $request->category_id;
            $addDiscount->save();
        }

        return response()->json('Discount created successfully');
    }
}
