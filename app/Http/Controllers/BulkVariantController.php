<?php

namespace App\Http\Controllers;

use App\Models\BulkVariant;
use App\Models\BulkVariantChild;
use Illuminate\Http\Request;

class BulkVariantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        return view('product.bulk_variants.index_v2');
    }

    public function getAllVariant()
    {
        $variants = BulkVariant::with(['bulk_variant_childs'])->get();
        return view('product.bulk_variants.ajax_view.variant_list', compact('variants'));
    }

    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'variant_name' => 'required',
        ]);

        $addVariant = new BulkVariant();
        $addVariant->bulk_variant_name = $request->variant_name;
        $addVariant->save();

        foreach ($request->variant_childs as $variant_child) {
            $addVariantChild = new BulkVariantChild();
            $addVariantChild->bulk_variant_id = $addVariant->id;
            $addVariantChild->child_name = $variant_child;
            $addVariantChild->save();
        }

        return response()->json('Variant created Successfully');
    }
    
    public function update(Request $request)
    {
        $updateVariant =  BulkVariant::with(['bulk_variant_childs'])->where('id', $request->id)->first();
        $updateVariant->bulk_variant_name = $request->variant_name;
        $updateVariant->save();

        $variant_child_ids = $request->variant_child_ids;
        $variant_childs = $request->variant_childs;

        foreach ($updateVariant->bulk_variant_childs as $variantChild) {
            $variantChild->delete_in_update = 1;
            $variantChild->save();
        }

        $index = 0;
        foreach ($variant_child_ids as $variant_child_id) {
            $variant_child_id = $variant_child_id == 'noid' ? NULL : $variant_child_id; 
            $updateBulkVariantChild = BulkVariantChild::where('id', $variant_child_id)->where('bulk_variant_id', $updateVariant->id)->first();
            if ($updateBulkVariantChild) {
                $updateBulkVariantChild->child_name = $variant_childs[$index];
                $updateBulkVariantChild->delete_in_update = 0;
                $updateBulkVariantChild->save();
            }else {
                $addVariantChild = new BulkVariantChild();
                $addVariantChild->bulk_variant_id = $updateVariant->id;
                $addVariantChild->child_name = $variant_childs[$index];
                $addVariantChild->save();
            }
            $index++;
        }

       $deleteBulkVariantChilds = BulkVariantChild::where('bulk_variant_id', $updateVariant->id)->where('delete_in_update', 1)->get();
        if ($deleteBulkVariantChilds->count() > 0) {
            foreach ($deleteBulkVariantChilds as $deleteBulkVariantChild) {
                $deleteBulkVariantChild->delete();
            }
        }
        return response()->json('Variant is updated successfully');
    }

    public function delete(Request $request, $variantId)
    {
        $deleteVariant = BulkVariant::where('id', $variantId)->first();
        if (!is_null($deleteVariant)) {
            $deleteVariant->delete();
            return response()->json('Variant deleted successfully');
        }
    }
}
