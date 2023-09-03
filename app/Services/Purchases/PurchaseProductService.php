<?php

namespace App\Services\Purchases;

use Yajra\DataTables\Facades\DataTables;
use App\Models\Purchases\PurchaseProduct;

class PurchaseProductService
{
    public function addPurchaseProduct($request, $isEditProductPrice, $purchaseId, $index)
    {
        $warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : null;

        $addPurchaseProduct = new PurchaseProduct();
        $addPurchaseProduct->purchase_id = $purchaseId;
        $addPurchaseProduct->product_id = $productId;
        $addPurchaseProduct->product_variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addPurchaseProduct->description = $request->descriptions[$index];
        $addPurchaseProduct->quantity = $request->quantities[$index];
        $addPurchaseProduct->left_qty = $request->quantities[$index];
        $addPurchaseProduct->unit = $request->unit_names[$index];
        $addPurchaseProduct->unit_cost = $request->unit_costs[$index];
        $addPurchaseProduct->unit_discount = $request->unit_discounts[$index];
        $addPurchaseProduct->unit_cost_with_discount = $request->unit_costs_with_discount[$index];
        $addPurchaseProduct->subtotal = $request->subtotals[$index];
        $addPurchaseProduct->unit_tax_ac_id = $request->unit_tax_ac_ids[$index];
        $addPurchaseProduct->unit_tax_percent = $request->tax_percents[$index];
        $addPurchaseProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addPurchaseProduct->net_unit_cost = $request->net_unit_costs[$index];
        $addPurchaseProduct->line_total = $request->linetotals[$index];
        $addPurchaseProduct->branch_id = auth()->user()->branch_id;

        if ($isEditProductPrice == '1') {

            $addPurchaseProduct->profit_margin = $request->profits[$index];
            $addPurchaseProduct->selling_price = $request->selling_prices[$index];
        }

        if (isset($request->lot_number)) {

            $addPurchaseProduct->lot_no = $request->lot_number[$index];
        }

        $addPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));

        $addPurchaseProduct->save();

        return $addPurchaseProduct;
    }
}
