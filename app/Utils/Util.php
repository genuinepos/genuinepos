<?php
namespace App\Utils;

use App\Models\Product;
use App\Models\ProductBranch;
use App\Models\ProductOpeningStock;

class Util
{
    public function addQuickProductFromAddSale($request){
        $addProduct = new Product();
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $request->validate(
            $request,
            [
                'name' => 'required',
                'product_code' => 'required',
                'unit_id' => 'required',
                'product_price' => 'required',
                'product_cost' => 'required',
                'product_cost_with_tax' => 'required',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        $addProduct->type = 1;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->product_code;
        $addProduct->category_id = $request->category_id;
        $addProduct->parent_category_id = $request->child_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->product_cost = $request->product_cost;
        $addProduct->profit = $request->profit ? $request->profit : 0.00;
        $addProduct->product_cost_with_tax = $request->product_cost_with_tax;
        $addProduct->product_price = $request->product_price;
        $addProduct->alert_quantity = $request->alert_quantity;
        $addProduct->tax_id = $tax_id;
        $addProduct->tax_type = 1;
        $addProduct->product_details = $request->product_details;
        $addProduct->is_purchased = 1;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->is_purchased = 1;
        $addProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $addProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $addProduct->mb_stock = !$request->branch_id ? $request->quantity : 0;
        $addProduct->save();

        //Add opening stock
        if ($request->branch_id) {
            //Add opening stock
            $addOpeningStock = new ProductOpeningStock();
            $addOpeningStock->branch_id = $request->branch_id;
            $addOpeningStock->product_id  = $addProduct->id;
            $addOpeningStock->unit_cost_exc_tax = $request->unit_cost_exc_tax;
            $addOpeningStock->quantity = $request->quantity;
            $addOpeningStock->subtotal = $request->subtotal;
            $addOpeningStock->save();

            // Add product Branch
            $addProductBranch = new ProductBranch();
            $addProductBranch->branch_id = $request->branch_id;
            $addProductBranch->product_id = $addProduct->id;
            $addProductBranch->product_quantity = $request->quantity;
            $addProductBranch->save();
        }
        return response()->json($addProduct);
    }
}