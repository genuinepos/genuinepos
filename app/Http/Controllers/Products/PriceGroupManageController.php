<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\ProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\ManagePriceGroupService;

class PriceGroupManageController extends Controller
{
    public function __construct(
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private ProductService $productService,
    ) {
    }

    public function index($productId, $type)
    {
        $priceGroups = $this->priceGroupService->priceGroups()->where('status', 'Active')->get();
        $product = $this->productService->singleProduct(
            id: $productId,
            with: [
                'variants:id,product_id,variant_name,variant_price',
                'tax:id,name,tax_percent'
            ],
            firstWithSelect: [
                'products.id',
                'products.product_code',
                'products.is_variant',
                'products.name',
                'products.product_price',
                'products.tax_ac_id',
            ]
        );

        return view('product.price_group.manage.index', compact('type', 'priceGroups', 'product'));
    }

    public function storeOrUpdate(Request $request)
    {
        try {

            DB::beginTransaction();

            $this->managePriceGroupService->addOrUpdateManagePriceGroups(request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action_type == 'save') {

            return response()->json(['saveMsg' => __("Product price group updated Successfully")]);
        } else {

            return response()->json(['saveAndAnotherMsg' => __("Product price group updated Successfully")]);
        }
    }
}
