<?php

namespace App\Services\Manufacturing;

use App\Enums\IsDeleteInUpdate;
use App\Models\Manufacturing\ProductionIngredient;

class ProductionIngredientService
{
    public function addProductionIngredient(object $request, int $productionId, ?int $index): object
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addProductionIngredient = new ProductionIngredient();
        $addProductionIngredient->production_id = $productionId;
        $addProductionIngredient->product_id = $request->product_ids[$index];
        $addProductionIngredient->variant_id = $variantId;
        $addProductionIngredient->unit_id = $request->unit_ids[$index];
        $addProductionIngredient->final_qty = $request->input_quantities[$index];
        $addProductionIngredient->parameter_quantity = $request->parameter_input_quantities[$index];
        $addProductionIngredient->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addProductionIngredient->tax_ac_id = $request->tax_ac_ids[$index];
        $addProductionIngredient->unit_tax_type = $request->unit_tax_types[$index];
        $addProductionIngredient->unit_tax_percent = $request->unit_tax_percents[$index];
        $addProductionIngredient->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addProductionIngredient->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addProductionIngredient->subtotal = $request->subtotals[$index];
        $addProductionIngredient->save();

        return $addProductionIngredient;
    }

    public function updateProductionIngredient(object $request, int $productionId, int $index): object
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

        $productionIngredient = $this->singleProductionIngredient()->where('id', $request->production_ingredient_ids[$index])
            ->where('product_id', $request->product_ids[$index])->where('variant_id', $variantId)->first();

        $addOrUpdateProductionIngredient = '';

        if ($productionIngredient) {

            $addOrUpdateProductionIngredient = $productionIngredient;
        } else {

            $addOrUpdateProductionIngredient = new ProductionIngredient();
        }

        $addOrUpdateProductionIngredient->production_id = $productionId;
        $addOrUpdateProductionIngredient->product_id = $request->product_ids[$index];
        $addOrUpdateProductionIngredient->variant_id = $variantId;
        $addOrUpdateProductionIngredient->unit_id = $request->unit_ids[$index];
        $addOrUpdateProductionIngredient->final_qty = $request->input_quantities[$index];
        $addOrUpdateProductionIngredient->parameter_quantity = $request->parameter_input_quantities[$index];
        $addOrUpdateProductionIngredient->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
        $addOrUpdateProductionIngredient->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrUpdateProductionIngredient->unit_tax_type = $request->unit_tax_types[$index];
        $addOrUpdateProductionIngredient->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrUpdateProductionIngredient->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrUpdateProductionIngredient->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrUpdateProductionIngredient->subtotal = $request->subtotals[$index];
        $addOrUpdateProductionIngredient->is_delete_in_update = IsDeleteInUpdate::No->value;
        $addOrUpdateProductionIngredient->save();

        return $addOrUpdateProductionIngredient;
    }

    function productionIngredients(?array $with = null): object
    {
        $query = ProductionIngredient::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    function singleProductionIngredient(?array $with = null): object
    {
        $query = ProductionIngredient::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
