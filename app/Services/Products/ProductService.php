<?php

namespace App\Services\Products;

use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\Products\Product;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ProductService
{
    public function createProductListOfProducts()
    {
        $products = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->select('products.id', 'products.name', 'products.product_cost', 'products.product_price')
            ->where('product_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('products.id', 'desc');

        return DataTables::of($products)
            ->addColumn('action', fn ($row) => '<a href="' . route('products.edit', [$row->id]) . '" class="action-btn c-edit" title="Edit"><span class="fas fa-edit"></span></a>')
            ->editColumn('name', fn ($row) => '<span title="' . $row->name . '">' . Str::limit($row->name, 25) . '</span>')
            ->rawColumns(['action', 'name'])->make(true);
    }

    function addProduct($request)
    {
        $addProduct = new Product();
        $addProduct->type = $request->type;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->code ? $request->code : $request->auto_generated_code;
        $addProduct->category_id = $request->category_id;
        $addProduct->sub_category_id = $request->sub_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->alert_quantity = $request->alert_quantity;
        // $addProduct->tax_ac_id = $request->tax_ac_id;
        $addProduct->tax_type = $request->tax_type;
        $addProduct->product_condition = $request->product_condition;
        $addProduct->is_show_in_ecom = $request->is_show_in_ecom;
        $addProduct->is_for_sale = $request->is_for_sale;
        $addProduct->is_show_emi_on_pos = $request->is_show_emi_on_pos;
        $addProduct->is_manage_stock = $request->is_manage_stock;
        $addProduct->product_details = isset($request->product_details) ? $request->product_details : null;
        $addProduct->is_purchased = 0;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->weight = isset($request->weight) ? $request->weight : null;

        if ($request->type == 1) {

            $addProduct->is_variant = $request->is_variant ? 1 : 0;
            $addProduct->product_cost = $request->product_cost ? $request->product_cost : 0;
            $addProduct->profit = $request->profit ? $request->profit : 0;
            $addProduct->product_cost_with_tax = $request->product_cost_with_tax ? $request->product_cost_with_tax : 0;
            $addProduct->product_price = $request->product_price ? $request->product_price : 0;
        }

        if ($request->file('photo')) {

            $productThumbnailPhoto = $request->file('photo');
            $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();

            $path = public_path('uploads/product/thumbnail');

            if (!file_exists($path)) {

                mkdir($path);
            }

            Image::make($productThumbnailPhoto)->resize(600, 600)->save($path . '/' . $productThumbnailName);
            $addProduct->thumbnail_photo = $productThumbnailName;
        }

        $addProduct->save();

        if ($request->file('image')) {

            if (count($request->file('image')) > 0) {

                foreach ($request->file('image') as $image) {

                    $productImage = $image;
                    $productImageName = uniqid() . '.' . $productImage->getClientOriginalExtension();
                    Image::make($productImage)->resize(600, 600)->save('uploads/product/' . $productImageName);
                    $addProductImage = new ProductImage();
                    $addProductImage->product_id = $addProduct->id;
                    $addProductImage->image = $productImageName;
                    $addProductImage->save();
                }
            }
        }

        // if ($request->type == 2) {

        //     $addProduct->is_combo = 1;
        //     $addProduct->profit = $request->profit ? $request->profit : 0.00;
        //     $addProduct->combo_price = $request->combo_price;
        //     $addProduct->product_price = $request->combo_price;
        //     $addProduct->save();

        //     $productIds = $request->product_ids;
        //     $combo_quantities = $request->combo_quantities;
        //     $productVariantIds = $request->variant_ids;
        //     $index = 0;

        //     foreach ($productIds as $id) {

        //         $addComboProducts = new ComboProduct();
        //         $addComboProducts->product_id = $addProduct->id;
        //         $addComboProducts->combo_product_id = $id;
        //         $addComboProducts->quantity = $combo_quantities[$index];
        //         $addComboProducts->product_variant_id = $productVariantIds[$index] !== 'noid' ? $productVariantIds[$index] : null;
        //         $addComboProducts->save();

        //         $index++;
        //     }
        // }

        return $addProduct;
    }

    public function restrictions($request)
    {
        if ($request->is_variant == 1) {

            if ($request->variant_combinations == null) {

                return ['pass' => false, 'msg' => 'You have selected Has variant? = Yes but there is no variant at all.'];
            }
        }

        if ($request->type == 2) {

            if ($request->product_ids == null) {

                return ['pass' => false, 'msg' => 'You have selected combo item but there is no item at all.'];
            }
        }

        return ['pass' => true];
    }
}
