<?php

namespace App\Http\Controllers;

use App\Models\BulkVariant;
use Illuminate\Http\Request;
use App\Models\BulkVariantChild;
use App\Utils\UserActivityLogUtil;

class BulkVariantController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        
    }

    public function index()
    {
        if (!auth()->user()->can('variant')) {

            abort(403, 'Access Forbidden.');
        }

        return view('product.bulk_variants.index_v2');
    }

    public function getAllVariant()
    {
        $variants = BulkVariant::with(['bulk_variant_child'])->get();
        return view('product.bulk_variants.ajax_view.variant_list', compact('variants'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('variant')) {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'variant_name' => 'required',
        ]);

        $addVariant = new BulkVariant();
        $addVariant->bulk_variant_name = $request->variant_name;
        $addVariant->save();

        foreach ($request->variant_child as $variant_child) {
            $addVariantChild = new BulkVariantChild();
            $addVariantChild->bulk_variant_id = $addVariant->id;
            $addVariantChild->child_name = $variant_child;
            $addVariantChild->save();
        }

        if ($addVariant) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 24, data_obj: $addVariant);
        }

        return response()->json('Variant created Successfully');
    }
    
    public function update(Request $request)
    {
        if (!auth()->user()->can('variant')) {
            return response()->json('Access Denied');
        }

        $updateVariant =  BulkVariant::with(['bulk_variant_child'])->where('id', $request->id)->first();
        $updateVariant->bulk_variant_name = $request->variant_name;
        $updateVariant->save();

        $variant_child_ids = $request->variant_child_ids;
        $variant_child = $request->variant_child;

        foreach ($updateVariant->bulk_variant_child as $variantChild) {
            
            $variantChild->delete_in_update = 1;
            $variantChild->save();
        }

        $index = 0;
        foreach ($variant_child_ids as $variant_child_id) {

            $variant_child_id = $variant_child_id == 'noid' ? NULL : $variant_child_id; 
            $updateBulkVariantChild = BulkVariantChild::where('id', $variant_child_id)->where('bulk_variant_id', $updateVariant->id)->first();
            if ($updateBulkVariantChild) {

                $updateBulkVariantChild->child_name = $variant_child[$index];
                $updateBulkVariantChild->delete_in_update = 0;
                $updateBulkVariantChild->save();
            }else {

                $addVariantChild = new BulkVariantChild();
                $addVariantChild->bulk_variant_id = $updateVariant->id;
                $addVariantChild->child_name = $variant_child[$index];
                $addVariantChild->save();
            }
            $index++;
        }

        $deleteBulkVariantChild = BulkVariantChild::where('bulk_variant_id', $updateVariant->id)->where('delete_in_update', 1)->get();
        if ($deleteBulkVariantChild->count() > 0) {

            foreach ($deleteBulkVariantChild as $deleteBulkVariantChild) {

                $deleteBulkVariantChild->delete();
            }
        }

        if ($updateVariant) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 24, data_obj: $updateVariant);
        }

        return response()->json('Variant is updated successfully');
    }

    public function delete(Request $request, $variantId)
    {
        if (!auth()->user()->can('variant')) {

            return response()->json('Access Denied');
        }
        
        $deleteVariant = BulkVariant::where('id', $variantId)->first();
        if (!is_null($deleteVariant)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 24, data_obj: $deleteVariant);
        
            $deleteVariant->delete();
            return response()->json('Variant deleted successfully');
        }
    }
}
