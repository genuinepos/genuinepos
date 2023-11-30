<?php

namespace App\Services\Products;

use App\Enums\BooleanType;
use App\Enums\IsDeleteInUpdate;
use App\Enums\RoleType;
use App\Models\Products\ProductAccessBranch;

class ProductAccessBranchService
{
    public function addProductAccessBranches(object $request, int $productId)
    {
        $addProductAccessBranch = new ProductAccessBranch();
        $addProductAccessBranch->product_id = $productId;
        $addProductAccessBranch->save();

        if (isset($request->access_branch_count) && $request->access_branch_ids) {

            foreach ($request->access_branch_ids as $branch_id) {

                $addProductAccessBranch = new ProductAccessBranch();
                $addProductAccessBranch->product_id = $productId;
                $addProductAccessBranch->branch_id = $branch_id;
                $addProductAccessBranch->save();
            }
        } else {

            if (auth()->user()->branch_id) {

                $addProductAccessBranch = new ProductAccessBranch();
                $addProductAccessBranch->product_id = $productId;
                $addProductAccessBranch->branch_id = auth()->user()->branch_id;
                $addProductAccessBranch->save();
            }
        }
    }

    public function updateProductAccessBranches(object $request, object $product)
    {
        if (
            isset($request->access_branch_count) &&
            auth()->user()->role_type != RoleType::Other->value &&
            auth()->user()->is_belonging_an_area == BooleanType::False->value
        ) {

            foreach ($product->productAccessBranches as $productAccessBranch) {

                if (isset($productAccessBranch->branch_id)) {

                    $productAccessBranch->is_delete_in_update = IsDeleteInUpdate::Yes->value;
                    $productAccessBranch->save();
                }
            }

            if (isset($request->access_branch_ids)) {

                foreach ($request->access_branch_ids as $branch_id) {

                    $productAssetBranch = $this->productAssetBranch()->where('branch_id', $branch_id)
                        ->where('product_id', $product->id)->first();

                    if (! $productAssetBranch) {

                        $addProductAccessBranch = new ProductAccessBranch();
                        $addProductAccessBranch->product_id = $product->id;
                        $addProductAccessBranch->branch_id = $branch_id;
                        $addProductAccessBranch->save();
                    } else {

                        $productAssetBranch->is_delete_in_update = IsDeleteInUpdate::No->value;
                        $productAssetBranch->save();
                    }
                }
            }

            $deleteUnusedProductAccessBranches = $this->productAssetBranch()->where('product_id', $product->id)
                ->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

            foreach ($deleteUnusedProductAccessBranches as $deleteUnusedProductAccessBranch) {

                $deleteUnusedProductAccessBranch->delete();
            }
        }
    }

    public function productAssetBranch(array $with = null): ?object
    {
        $query = ProductAccessBranch::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
