<?php

namespace App\Services\Products;

use App\Models\Products\ProductAccessBranch;

class ProductAccessBranchService
{
    public function addProductAccessBranches(object $request, int $productId)
    {
        $addProductAccessBranch = new ProductAccessBranch();
        $addProductAccessBranch->product_id = $productId;
        $addProductAccessBranch->save();

        if (isset($request->branch_count) && $request->branch_ids) {

            foreach ($request->branch_ids as $branch_id) {

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
}
