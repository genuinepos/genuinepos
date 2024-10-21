<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Sales\QuotationProductService;

class ServiceQuotationProductController extends Controller
{
    public function __construct(private QuotationProductService $quotationProductService) {}

    public function quotationProductsForJobCard($quotationId)
    {
        $quotationProducts = $this->quotationProductService->quotationProducts(with: [
            'sale:id',
            'product:id,name,product_code,unit_id,is_manage_stock',
            'product.unit:id,name,code_name',
            'product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'variant:id,variant_name,variant_code',
            'unit:id,name,base_unit_id,base_unit_multiplier',
            'unit.baseUnit:id,name,base_unit_id',
        ])->where('sale_id', $quotationId)->get();

        $itemUnitsArray = [];
        foreach ($quotationProducts as $quotationProduct) {

            if (isset($quotationProduct->product_id)) {

                $itemUnitsArray[$quotationProduct->product_id][] = [
                    'unit_id' => $quotationProduct->product->unit->id,
                    'unit_name' => $quotationProduct->product->unit->name,
                    'unit_code_name' => $quotationProduct->product->unit->code_name,
                    'base_unit_multiplier' => 1,
                    'multiplier_details' => '',
                    'is_base_unit' => 1,
                ];
            }

            if (count($quotationProduct?->product?->unit?->childUnits) > 0) {

                foreach ($quotationProduct?->product?->unit?->childUnits as $unit) {

                    $multiplierDetails = '(1 ' . $unit->name . ' = ' . $unit->base_unit_multiplier . '/' . $quotationProduct?->product?->unit?->name . ')';

                    array_push($itemUnitsArray[$quotationProduct->product_id], [
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

        $view = view('services.quotations.quotation_products.ajax_view.service_quotation_products_for_job_card', ['quotationProducts' => $quotationProducts])->render();

        return [
            'view' => $view,
            'units' => json_encode($itemUnitsArray),
        ];
    }
}
