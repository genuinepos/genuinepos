<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\ProductService;
use App\Services\Products\PriceGroupService;
use App\Services\Products\PriceGroupUnitService;
use App\Services\Products\ManagePriceGroupService;

class PriceGroupManageController extends Controller
{
    public function __construct(
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private PriceGroupUnitService $priceGroupUnitService,
        private ProductService $productService,
    ) {
    }

    public function index($productId, $type)
    {
        abort_if(!auth()->user()->can('manage_price_group'), 403);
        $priceGroups = $this->priceGroupService->priceGroups()->where('status', 'Active')->get();
        $product = $this->productService->singleProduct(
            id: $productId,
            with: [
                'productUnits:id,product_id,assigned_unit_id,unit_price_exc_tax',
                'productUnits.assignedUnit:id,name',
                'variants:id,product_id,variant_name,variant_price',
                'variants.variantUnits:id,product_id,variant_id,assigned_unit_id,unit_price_exc_tax',
                'variants.variantUnits.assignedUnit:id,name',
                'tax:id,name,tax_percent',
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
        abort_if(!auth()->user()->can('manage_price_group'), 403);
        // return $request->all();

        try {
            DB::beginTransaction();

            foreach ($request->product_ids as $index => $productId) {

                foreach ($request->group_prices as $priceGroupId => $prices) {

                    $addOrUpdatePriceGroupProduct = $this->managePriceGroupService->addOrUpdatePriceGroupProduct(request: $request, priceGroupId: $priceGroupId, productId: $productId, variantId: $request->variant_ids[$index], price: $prices[$productId][$request->variant_ids[$index]]);

                    if (isset($request->multiple_unit_assigned_unit_ids[$priceGroupId])) {

                        if ($addOrUpdatePriceGroupProduct->variant_id) {

                            $this->priceGroupUnitService->addOrUpdatePriceGroupUnitsForVariant(request: $request, priceGroupId: $priceGroupId, priceGroupProductId: $addOrUpdatePriceGroupProduct->id, productId: $productId, variantId: $request->variant_ids[$index]);
                        } else {

                            $this->priceGroupUnitService->addOrUpdatePriceGroupUnitsForSingleProduct(request: $request, priceGroupId: $priceGroupId, priceGroupProductId: $addOrUpdatePriceGroupProduct->id, productId: $productId);
                        }
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action_type == 'save') {

            return response()->json(['saveMsg' => __('Product price group updated successfully')]);
        } else {

            return response()->json(['saveAndAnotherMsg' => __('Product price group updated successfully')]);
        }
    }
}
