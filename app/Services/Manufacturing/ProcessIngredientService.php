<?php

namespace App\Services\Manufacturing;

use App\Enums\IsDeleteInUpdate;
use App\Models\Manufacturing\ProcessIngredient;
use Illuminate\Support\Facades\DB;

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

    public function ingredientsForProduction(int $processId, ?int $warehouseId): array|object
    {
        $ingredients = DB::table('process_ingredients')
            ->leftJoin('products', 'process_ingredients.product_id', 'products.id')
            ->leftJoin('product_variants', 'process_ingredients.variant_id', 'product_variants.id')
            ->leftJoin('units', 'process_ingredients.unit_id', 'units.id')
            ->where('process_ingredients.process_id', $processId)
            ->select(
                'process_ingredients.*',
                'products.id as product_id',
                'products.name as product_name',
                'products.product_code',
                'product_variants.id as variant_id',
                'product_variants.variant_name as variant_name',
                'product_variants.variant_code as variant_code',
                'units.id as unit_id',
                'units.name as unit_name',
            )->get();

        foreach ($ingredients as $ingredient) {

            $productName = $ingredient->product_name . ($ingredient->variant_name ? $ingredient->variant_name : '');

            if (isset($warehouseId)) {

                $productStock = DB::table('product_stocks')
                    ->where('product_id', $ingredient->product_id)->where('variant_id', $ingredient->variant_id)
                    ->where('warehouse_id', $warehouseId)->first(['stock']);

                if (!$productStock) {

                    return ['pass' => false, 'msg' => __('Ingredient Name') . ': ' . $productName . ' ' . __('stock is not available in selected warehouse.')];
                }

                if ($productStock->stock < $ingredient->final_qty) {

                    return ['pass' => false, 'msg' => __('Ingredient Name') . ': ' . $productName . ', ' . __('insufficient stock in selected warehouse. Current Stock') . ': ' . $productStock->stock];
                } else {

                    $ingredient->stock = $productStock->stock;
                }
            } else {

                $productStock = DB::table('product_stocks')
                    ->where('product_id', $ingredient->product_id)->where('variant_id', $ingredient->variant_id)
                    ->where('branch_id', auth()->user()->branch_id)
                    ->where('warehouse_id', null)
                    ->first(['stock']);

                if (!$productStock) {

                    return ['pass' => false, 'msg' => __('Ingredient Name') . ': ' . $productName . ' ' . __('stock is not available in store/company.')];
                }

                if ($productStock->stock < $ingredient->final_qty) {

                    return ['pass' => false, 'msg' => __('Ingredient Name') . ': ' . $productName . ' ' . __('stock is insufficient in the store/company.')];
                } else {

                    $ingredient->stock = $productStock->stock;
                }
            }
        }

        return $ingredients;
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
