<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Purchases\PurchaseOrderProductService;

class PurchaseOrderProductController extends Controller
{
    public function __construct(private PurchaseOrderProductService $purchaseOrderProductService)
    {
    }

    public function purchaseOrderProductsForPoToInvoice($purchaseOrderId)
    {
        $purchaseOrderProducts = $this->purchaseOrderProductService->purchaseOrderProducts(with: [
            'product:id,name,product_code,unit_id',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'variant:id,variant_name,variant_code',
            'unit:id,name,base_unit_id,base_unit_multiplier',
            'unit.baseUnit:id,name,base_unit_id',
        ])->where('purchase_id', $purchaseOrderId)->get();

        $itemUnitsArray = [];
        foreach ($purchaseOrderProducts as $purchaseOrderProduct) {

            if (isset($purchaseOrderProduct->product_id)) {

                $itemUnitsArray[$purchaseOrderProduct->product_id][] = [
                    'unit_id' => $purchaseOrderProduct->product->unit->id,
                    'unit_name' => $purchaseOrderProduct->product->unit->name,
                    'unit_code_name' => $purchaseOrderProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($purchaseOrderProduct?->product?->unit?->childUnits) > 0) {

                foreach ($purchaseOrderProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '( ' . __('1') . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $purchaseOrderProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$purchaseOrderProduct->product_id], [
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'unit_code_name' => $unit->code_name,
                        'base_unit_multiplier' => $unit->base_unit_multiplier,
                        'multiplier_details' => $multiplierDetails,
                        'is_base_unit' => 0,
                    ]);
                }
            }
        }

        $view = view('purchase.order_products.purchase_order_products_for_po_to_invoice', ['purchaseOrderProducts' => $purchaseOrderProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }
}
