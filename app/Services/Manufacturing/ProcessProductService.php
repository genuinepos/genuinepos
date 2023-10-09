<?php

namespace App\Services\Manufacturing;

use App\Models\Manufacturing\ProcessIngredient;

class ProcessProductService
{
    public function addProcessProducts(object $request, int $processId): void
    {
        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $addProcessIngredient = new ProcessIngredient();
            $addProcessIngredient->process_id = $processId;
            $addProcessIngredient->product_id = $product_id;
            $addProcessIngredient->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
            $addProcessIngredient->final_qty = $request->final_quantities[$index];
            $addProcessIngredient->unit_id = $request->unit_ids[$index];
            $addProcessIngredient->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
            $addProcessIngredient->tax_ac_id = $request->tax_ac_ids[$index];
            $addProcessIngredient->unit_tax_type = $request->unit_tax_types[$index];
            $addProcessIngredient->unit_tax_percent = $request->unit_tax_percents[$index];
            $addProcessIngredient->unit_tax_amount = $request->unit_tax_amounts[$index];
            $addProcessIngredient->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
            $addProcessIngredient->subtotal = $request->subtotals[$index];
            $addProcessIngredient->save();
            
            $index++;
        }
    }
}
