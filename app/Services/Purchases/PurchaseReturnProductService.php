<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Purchases\PurchaseReturnProduct;

class PurchaseReturnProductService
{
    public function addPurchaseReturnProduct(object $request, int $purchaseReturnId, int $index): object
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $addPurchaseReturnProduct = new PurchaseReturnProduct();
        $addPurchaseReturnProduct->purchase_return_id = $purchaseReturnId;
        $addPurchaseReturnProduct->purchase_product_id = $request->purchase_product_ids[$index];
        $addPurchaseReturnProduct->product_id = $request->product_ids[$index];
        $addPurchaseReturnProduct->variant_id = $variantId;
        $addPurchaseReturnProduct->warehouse_id = $request->warehouse_ids[$index];
        $addPurchaseReturnProduct->return_qty = $request->return_quantities[$index];
        $addPurchaseReturnProduct->purchased_qty = $request->purchased_quantities[$index];
        $addPurchaseReturnProduct->unit_id = $request->unit_ids[$index];
        $addPurchaseReturnProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addPurchaseReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addPurchaseReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addPurchaseReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addPurchaseReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addPurchaseReturnProduct->unit_tax_type = $request->tax_types[$index];
        $addPurchaseReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addPurchaseReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addPurchaseReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addPurchaseReturnProduct->return_subtotal = $request->subtotals[$index];
        $addPurchaseReturnProduct->save();

        return $addPurchaseReturnProduct;
    }

    public function updatePurchaseReturnProduct(object $request, int $purchaseReturnId, int $index): object
    {
        $returnProduct = PurchaseReturnProduct::where('id', $request->purchase_return_product_ids[$index])->first();
        $currentUnitTaxAcId = $returnProduct ? $returnProduct->tax_ac_id : null;
        $currentWarehouseId = $returnProduct ? $returnProduct->warehouse_id : null;
        $addOrEditPurchaseReturnProduct = '';
        if($returnProduct){

            $addOrEditPurchaseReturnProduct = $returnProduct;
        }else {

            $addOrEditPurchaseReturnProduct = new PurchaseReturnProduct();
        }

        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addOrEditPurchaseReturnProduct->purchase_return_id = $purchaseReturnId;
        $addOrEditPurchaseReturnProduct->purchase_product_id = $request->purchase_product_ids[$index];
        $addOrEditPurchaseReturnProduct->product_id = $request->product_ids[$index];
        $addOrEditPurchaseReturnProduct->variant_id = $variantId;
        $addOrEditPurchaseReturnProduct->warehouse_id = $request->warehouse_ids[$index];
        $addOrEditPurchaseReturnProduct->return_qty = $request->return_quantities[$index];
        $addOrEditPurchaseReturnProduct->purchased_qty = $request->purchased_quantities[$index];
        $addOrEditPurchaseReturnProduct->unit_id = $request->unit_ids[$index];
        $addOrEditPurchaseReturnProduct->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addOrEditPurchaseReturnProduct->unit_discount = $request->unit_discounts[$index];
        $addOrEditPurchaseReturnProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrEditPurchaseReturnProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrEditPurchaseReturnProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrEditPurchaseReturnProduct->unit_tax_type = $request->tax_types[$index];
        $addOrEditPurchaseReturnProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrEditPurchaseReturnProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrEditPurchaseReturnProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrEditPurchaseReturnProduct->return_subtotal = $request->subtotals[$index];
        $addOrEditPurchaseReturnProduct->save();

        $addOrEditPurchaseReturnProduct->current_tax_ac_id = $currentUnitTaxAcId;
        $addOrEditPurchaseReturnProduct->current_warehouse_id = $currentWarehouseId;

        return $addOrEditPurchaseReturnProduct;
    }

    function purchaseReturnProducts(?array $with = null): ?object
    {
        $query = PurchaseReturnProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
