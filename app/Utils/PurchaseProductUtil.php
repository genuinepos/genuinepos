<?php

namespace App\Utils;

use App\Models\PurchaseProduct;

class PurchaseProductUtil
{
    public function addPurchaseProduct($request, $isEditProductPrice, $purchaseId, $index)
    {
        $addPurchaseProduct = new PurchaseProduct();
        $addPurchaseProduct->purchase_id = $purchaseId;
        $addPurchaseProduct->product_id = $request->product_ids[$index];
        $addPurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
        $addPurchaseProduct->description = $request->descriptions[$index];
        $addPurchaseProduct->quantity = $request->quantities[$index];
        $addPurchaseProduct->left_qty = $request->quantities[$index];
        $addPurchaseProduct->unit = $request->units[$index];
        $addPurchaseProduct->unit_cost = $request->unit_costs_exc_tax[$index];
        $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
        $addPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
        $addPurchaseProduct->subtotal = $request->subtotals[$index];
        $addPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addPurchaseProduct->tax_type = $request->tax_types[$index];
        $addPurchaseProduct->unit_tax = $request->unit_tax_amounts[$index];
        $addPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
        $addPurchaseProduct->line_total = $request->linetotals[$index];
        $addPurchaseProduct->branch_id = auth()->user()->branch_id;

        if ($isEditProductPrice == '1') {

            $addPurchaseProduct->profit_margin = $request->profits[$index];
            $addPurchaseProduct->selling_price = $request->selling_prices[$index];
        }

        if (isset($request->lot_numbers)) {

            $addPurchaseProduct->lot_no = $request->lot_numbers[$index];
        }

        $addPurchaseProduct->batch_number = $request->batch_numbers[$index];
        $addPurchaseProduct->expire_date = isset($request->expire_dates[$index]) ? date('Y-m-d', strtotime($request->expire_dates[$index])) : NULL;
        $addPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchaseProduct->save();

        return $addPurchaseProduct;
    }

    public function updatePurchaseProduct($request, $isEditProductPrice, $purchaseId, $index, $purchaseUtil)
    {
        $filterVariantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $updateOrAddPurchaseProduct = '';
        $purchaseProduct = PurchaseProduct::where('purchase_id', $purchaseId)->where('id', $request->purchase_product_ids[$index])->first();

        if ($purchaseProduct) {

            $updateOrAddPurchaseProduct = $purchaseProduct;
        } else {

            $updateOrAddPurchaseProduct = new PurchaseProduct();
        }

        $updateOrAddPurchaseProduct->purchase_id = $purchaseId;
        $updateOrAddPurchaseProduct->product_id = $request->product_ids[$index];
        $updateOrAddPurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $updateOrAddPurchaseProduct->description = $request->descriptions[$index];
        $updateOrAddPurchaseProduct->quantity = $request->quantities[$index];
        $updateOrAddPurchaseProduct->left_qty = $request->quantities[$index];
        $updateOrAddPurchaseProduct->unit = $request->units[$index];
        $updateOrAddPurchaseProduct->unit_cost = $request->unit_costs_exc_tax[$index];
        $updateOrAddPurchaseProduct->unit_discount = $request->unit_discounts[$index];
        $updateOrAddPurchaseProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $updateOrAddPurchaseProduct->unit_discount_type = $request->unit_discount_types[$index];
        $updateOrAddPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
        $updateOrAddPurchaseProduct->subtotal = $request->subtotals[$index];
        $updateOrAddPurchaseProduct->tax_type = $request->tax_types[$index];
        $updateOrAddPurchaseProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $updateOrAddPurchaseProduct->unit_tax = $request->unit_tax_amounts[$index];
        $updateOrAddPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
        $updateOrAddPurchaseProduct->line_total = $request->linetotals[$index];

        if ($isEditProductPrice == '1') {

            $updateOrAddPurchaseProduct->profit_margin = $request->profits[$index];
            $updateOrAddPurchaseProduct->selling_price = $request->selling_prices[$index];
        }

        if (isset($request->lot_numbers)) {

            $updateOrAddPurchaseProduct->lot_no = $request->lot_numbers[$index];
        }

        $updateOrAddPurchaseProduct->batch_number = $request->batch_numbers[$index];
        $updateOrAddPurchaseProduct->expire_date = isset($request->expire_dates[$index]) ? date('Y-m-d', strtotime($request->expire_dates[$index])) : NULL;
        $updateOrAddPurchaseProduct->delete_in_update = 0;
        $updateOrAddPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updateOrAddPurchaseProduct->save();

        $purchaseUtil->adjustPurchaseLeftQty($updateOrAddPurchaseProduct);

        return $updateOrAddPurchaseProduct;
    }
}
