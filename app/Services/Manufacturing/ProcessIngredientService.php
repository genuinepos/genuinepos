<?php

namespace App\Services\Manufacturing;

use App\Enums\IsDeleteInUpdate;
use App\Models\Manufacturing\ProcessIngredient;

class ProcessIngredientService
{
    public function addProcessIngredients(object $request, int $processId): void
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

    public function updateProcessIngredients(object $request, object $process): void
    {
        if (count($request->product_ids) > 0) {

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

                $ingredient = $this->processIngredient()->where('process_id', $process->id)
                    ->where('product_id', $product_id)
                    ->where('variant_id', $variantId)->first();

                $addOrUpdateProcessIngredient = '';
                if ($ingredient) {

                    $addOrUpdateProcessIngredient = $ingredient;
                } else {

                    $addOrUpdateProcessIngredient = new ProcessIngredient();
                }

                $addOrUpdateProcessIngredient->process_id = $process->id;
                $addOrUpdateProcessIngredient->product_id = $product_id;
                $addOrUpdateProcessIngredient->variant_id = $variantId;
                $addOrUpdateProcessIngredient->final_qty = $request->final_quantities[$index];
                $addOrUpdateProcessIngredient->unit_id = $request->unit_ids[$index];
                $addOrUpdateProcessIngredient->unit_cost_exc_tax = $request->unit_costs_exc_tax[$index];
                $addOrUpdateProcessIngredient->tax_ac_id = $request->tax_ac_ids[$index];
                $addOrUpdateProcessIngredient->unit_tax_type = $request->unit_tax_types[$index];
                $addOrUpdateProcessIngredient->unit_tax_percent = $request->unit_tax_percents[$index];
                $addOrUpdateProcessIngredient->unit_tax_amount = $request->unit_tax_amounts[$index];
                $addOrUpdateProcessIngredient->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $addOrUpdateProcessIngredient->subtotal = $request->subtotals[$index];
                $addOrUpdateProcessIngredient->is_delete_in_update = IsDeleteInUpdate::No->value;
                $addOrUpdateProcessIngredient->save();

                $index++;
            }
        }

        $unusedIngredients = $this->processIngredient()->where('process_id', $process->id)
            ->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

        foreach ($unusedIngredients as $unusedIngredient) {

            $unusedIngredient->delete();
        }
    }

    public function processIngredient(array $with = null): ?object
    {
        $query = ProcessIngredient::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
