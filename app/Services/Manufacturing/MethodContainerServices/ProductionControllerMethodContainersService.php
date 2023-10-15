<?php

namespace App\Services\Manufacturing\MethodContainerServices;

use App\Enums\BooleanType;
use App\Enums\IsDeleteInUpdate;
use App\Enums\ProductionStatus;
use App\Enums\DayBookVoucherType;
use App\Enums\ProductLedgerVoucherType;
use App\Interfaces\Manufacturing\ProductionControllerMethodContainersInterface;

class ProductionControllerMethodContainersService implements ProductionControllerMethodContainersInterface
{
    public function showMethodContainer(
        int $id,
        object $productionService,
    ): ?array {

        $data['production'] = $productionService->singleProduction(with: [
            'branch',
            'branch.parentBranch',
            'storeWarehouse:id,warehouse_name,warehouse_code',
            'stockWarehouse:id,warehouse_name,warehouse_code',
            'unit:id,code_name',
            'product:id,name,product_code',
            'variant:id,variant_name,variant_code',
            'ingredients',
            'ingredients.product:id,name,product_code',
            'ingredients.variant:id,variant_name,variant_code',
            'ingredients.unit:id,code_name',
        ])->where('id', $id)->first();

        return $data;
    }

    public function createMethodContainer(
        object $warehouseService,
        object $accountService,
        object $processService,
    ): ?array {

        $data = [];
        $data['warehouses'] = $warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['processes'] = $processService->processes();

        return $data;
    }

    public function storeMethodContainer(
        object $request,
        object $productionService,
        object $productionIngredientService,
        object $manufacturingSettingService,
        object $productService,
        object $productLedgerService,
        object $productStockService,
        object $purchaseProductService,
        object $dayBookService,
        object $codeGenerator,
    ): ?array {

        $restrictions = $productionService->restrictions($request);
        if ($restrictions['pass'] = false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $manufacturingSetting = $manufacturingSettingService->manufacturingSetting()->where('branch_id', auth()->user()->branch_id)->first();
        $voucherPrefix = $manufacturingSetting?->production_voucher_prefix ? $manufacturingSetting?->production_voucher_prefix : 'MF';
        $isUpdateProductCostAndPrice = $manufacturingSetting ? $manufacturingSetting?->is_update_product_cost_and_price_in_production : BooleanType::True->value;

        $addProduction = $productionService->addProduction(request: $request, codeGenerator: $codeGenerator, voucherPrefix: $voucherPrefix);

        // Add Day Book entry for Production
        $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Production->value, date: $addProduction->date, accountId: null, transId: $addProduction->id, amount: $addProduction->net_cost, amountType: 'debit');

        if ($addProduction->status == ProductionStatus::Final->value) {

            $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $addProduction->date, productId: $addProduction->product_id, transId: $addProduction->id, rate: $addProduction->per_unit_cost_inc_tax, quantityType: 'in', quantity: $addProduction->total_final_output_quantity, subtotal: $addProduction->net_cost, variantId: $addProduction->variant_id, warehouseId: $addProduction->store_warehouse_id);
        }

        if (isset($request->product_ids)) {

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $addProductionIngredient = $productionIngredientService->addProductionIngredient(request: $request, productionId: $addProduction->id, index: $index);

                if ($addProduction->status == ProductionStatus::Final->value) {

                    $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $addProduction->date, productId: $addProductionIngredient->product_id, transId: $addProduction->id, rate: $addProductionIngredient->unit_cost_inc_tax, quantityType: 'out', quantity: $addProductionIngredient->final_qty, subtotal: $addProductionIngredient->subtotal, variantId: $addProductionIngredient->variant_id, optionalColName: 'production_ingredient_id', optionalColValue: $addProductionIngredient->id, warehouseId: $addProduction->stock_warehouse_id);

                    $productStockService->adjustMainProductAndVariantStock($addProductionIngredient->product_id, $addProductionIngredient->variant_id);

                    if (isset($addProduction->stock_warehouse_id)) {

                        $productStockService->adjustWarehouseStock($addProductionIngredient->product_id, $addProductionIngredient->variant_id, $addProduction->stock_warehouse_id);
                    } else {

                        $productStockService->adjustBranchStock($addProductionIngredient->product_id, $addProductionIngredient->variant_id, auth()->user()->branch_id);
                    }
                }

                $index++;
            }
        }

        if ($addProduction->status == ProductionStatus::Final->value) {

            if ($isUpdateProductCostAndPrice == BooleanType::True->value) {

                $productService->updateProductAndVariantPrice(productId: $addProduction->product_id, variantId: $addProduction->variant_id, unitCostWithDiscount: $addProduction->per_unit_cost_exc_tax, unitCostIncTax: $addProduction->per_unit_cost_inc_tax, profit: $addProduction->profit_margin, sellingPrice: $addProduction->per_unit_price_exc_tax, isEditProductPrice: BooleanType::True->value, isLastEntry: BooleanType::True->value);
            }

            $productStockService->adjustMainProductAndVariantStock($addProduction->product_id, $addProduction->variant_id);

            if (isset($addProduction->store_warehouse_id)) {

                $productStockService->addWarehouseProduct($addProduction->product_id, $addProduction->variant_id, $addProduction->store_warehouse_id);

                $productStockService->adjustWarehouseStock($addProduction->product_id, $addProduction->variant_id, $addProduction->store_warehouse_id);
            } else {

                $productStockService->addBranchProduct($addProduction->product_id, $addProduction->variant_id, auth()->user()->branch_id);
                $productStockService->adjustBranchStock($addProduction->product_id, $addProduction->variant_id, auth()->user()->branch_id);
            }

            $purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'production_id', transId: $addProduction->id, branchId: auth()->user()->branch_id, productId: $addProduction->product_id, variantId: $addProduction->variant_id, quantity: $addProduction->total_final_output_quantity, unitCostIncTax: $addProduction->per_unit_cost_inc_tax, sellingPrice: $addProduction->per_unit_price_exc_tax, subTotal: $addProduction->net_cost, createdAt: $addProduction->date_ts);
        }

        $production = $productionService->singleProduction(with: [
            'branch',
            'branch.parentBranch',
            'storeWarehouse:id,warehouse_name,warehouse_code',
            'stockWarehouse:id,warehouse_name,warehouse_code',
            'unit:id,code_name',
            'product:id,name,product_code',
            'variant:id,variant_name,variant_code',
            'ingredients',
            'ingredients.product:id,name,product_code',
            'ingredients.variant:id,variant_name,variant_code',
            'ingredients.unit:id,code_name',
        ])->where('id', $addProduction->id)->first();

        return ['production' => $production];
    }

    public function editMethodContainer(
        int $id,
        object $productionService,
        object $warehouseService,
        object $accountService,
        object $processService,
    ): ?array {
        $data = [];

        $data['production'] = $productionService->singleProduction(with: [
            'branch',
            'branch.parentBranch:id,name,branch_code',
            'stockWarehouse:id,warehouse_name,warehouse_code',
            'unit:id,name',
            'product:id,name,product_code',
            'variant:id,variant_name,variant_code',
            'ingredients',
            'ingredients.product:id,name,product_code',
            'ingredients.variant:id,variant_name,variant_code',
            'ingredients.unit:id,name',
        ])->where('id', $id)->first();

        $data['warehouses'] = $warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $data['taxAccounts'] = $accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $data['processes'] = $processService->processes();

        return $data;
    }

    public function updateMethodContainer(
        int $id,
        object $request,
        object $productionService,
        object $productionIngredientService,
        object $manufacturingSettingService,
        object $productService,
        object $productLedgerService,
        object $productStockService,
        object $purchaseProductService,
        object $dayBookService,
    ): ?array {

        $manufacturingSetting = $manufacturingSettingService->manufacturingSetting()->where('branch_id', auth()->user()->branch_id)->first();
        $isUpdateProductCostAndPrice = $manufacturingSetting ? $manufacturingSetting?->is_update_product_cost_and_price_in_production : BooleanType::True->value;

        $restrictions = $productionService->restrictions($request);
        if ($restrictions['pass'] = false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $updateProduction = $productionService->updateProduction(request: $request, productionId: $id);

        // Add Day Book entry for Production
        $dayBookService->updateDayBook(voucherTypeId: DayBookVoucherType::Production->value, date: $updateProduction->date, accountId: null, transId: $updateProduction->id, amount: $updateProduction->net_cost, amountType: 'debit');

        if ($updateProduction->status == ProductionStatus::Final->value) {

            $productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $updateProduction->date, productId: $updateProduction->product_id, transId: $updateProduction->id, rate: $updateProduction->per_unit_cost_inc_tax, quantityType: 'in', quantity: $updateProduction->total_final_output_quantity, subtotal: $updateProduction->net_cost, variantId: $updateProduction->variant_id, warehouseId: $updateProduction->store_warehouse_id);

            if ($updateProduction->product_id != $updateProduction->previous_product_id && $updateProduction->variant_id != $updateProduction->previous_variant_id) {

                $productLedgerService->deleteUnusedProductLedgerEntry(transColName: 'production_id', transId: $updateProduction->id, productId: $updateProduction->previous_product_id, variantId: $updateProduction->previous_variant_id);
            }
        }

        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $updateProductionIngredient = $productionIngredientService->updateProductionIngredient(request: $request, productionId: $updateProduction->id, index: $index);

            if ($updateProduction->status == ProductionStatus::Final->value) {

                $productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $updateProduction->date, productId: $updateProductionIngredient->product_id, transId: $updateProduction->id, rate: $updateProductionIngredient->unit_cost_inc_tax, quantityType: 'out', quantity: $updateProductionIngredient->final_qty, subtotal: $updateProductionIngredient->subtotal, variantId: $updateProductionIngredient->variant_id, optionalColName: 'production_ingredient_id', optionalColValue: $updateProductionIngredient->id, warehouseId: $updateProduction->stock_warehouse_id);

                $productStockService->adjustMainProductAndVariantStock($updateProductionIngredient->product_id, $updateProductionIngredient->variant_id);

                if (isset($updateProduction->stock_warehouse_id)) {

                    $productStockService->adjustWarehouseStock($updateProductionIngredient->product_id, $updateProductionIngredient->variant_id, $updateProduction->stock_warehouse_id);
                } else {

                    $productStockService->adjustBranchStock($updateProductionIngredient->product_id, $updateProductionIngredient->variant_id, $updateProduction->branch_id);
                }
            }

            $index++;
        }

        if ($updateProduction->status == ProductionStatus::Final->value) {

            if ($isUpdateProductCostAndPrice == BooleanType::True->value) {

                $productService->updateProductAndVariantPrice(productId: $updateProduction->product_id, variantId: $updateProduction->variant_id, unitCostWithDiscount: $updateProduction->per_unit_cost_exc_tax, unitCostIncTax: $updateProduction->per_unit_cost_inc_tax, profit: $updateProduction->profit_margin, sellingPrice: $updateProduction->per_unit_price_exc_tax, isEditProductPrice: BooleanType::True->value, isLastEntry: BooleanType::True->value);
            }

            $productStockService->adjustMainProductAndVariantStock($updateProduction->product_id, $updateProduction->variant_id);

            if (isset($updateProduction->store_warehouse_id)) {

                $productStockService->addWarehouseProduct($updateProduction->product_id, $updateProduction->variant_id, $updateProduction->store_warehouse_id);

                $productStockService->adjustWarehouseStock($updateProduction->product_id, $updateProduction->variant_id, $updateProduction->store_warehouse_id);
            } else {

                $productStockService->addBranchProduct($updateProduction->product_id, $updateProduction->variant_id, auth()->user()->branch_id);

                $productStockService->adjustBranchStock($updateProduction->product_id, $updateProduction->variant_id, auth()->user()->branch_id);
            }

            $purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'production_id', transId: $updateProduction->id, branchId: auth()->user()->branch_id, productId: $updateProduction->product_id, variantId: $updateProduction->variant_id, quantity: $updateProduction->total_final_output_quantity, unitCostIncTax: $updateProduction->per_unit_cost_inc_tax, sellingPrice: $updateProduction->per_unit_price_exc_tax, subTotal: $updateProduction->net_cost, createdAt: $updateProduction->date_ts);

            if ($updateProduction->product_id != $updateProduction->previous_product_id && $updateProduction->variant_id != $updateProduction->previous_variant_id) {

                $purchaseProductService->deleteUnusedPurchaseProduct(transColName: 'production_id', transColValue: $updateProduction->id, productId: $updateProduction->previous_product_id, variantId: $updateProduction->previous_variant_id);
            }
        }

        $deleteUnusedProductionIngredients = $productionIngredientService->productionIngredients()->where('production_id', $updateProduction->id)->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

        foreach ($deleteUnusedProductionIngredients as $deleteUnusedProductionIngredient) {

            $deleteUnusedProductionIngredient->delete();

            if (isset($updateProduction->previous_stock_warehouse_id)) {

                $productStockService->adjustWarehouseStock($deleteUnusedProductionIngredient->product_id, $deleteUnusedProductionIngredient->variant_id, $updateProduction->previous_stock_warehouse_id);
            } else {

                $productStockService->adjustBranchStock($deleteUnusedProductionIngredient->product_id, $deleteUnusedProductionIngredient->variant_id, $updateProduction->branch_id);
            }
        }

        if ($updateProduction->product_id != $updateProduction->previous_product_id && $updateProduction->variant_id != $updateProduction->previous_variant_id) {

            if (isset($updateProduction->previous_store_warehouse_id)) {

                $productStockService->adjustWarehouseStock($updateProduction->previous_product_id, $updateProduction->previous_variant_id, $updateProduction->previous_store_warehouse_id);
            } else {

                $productStockService->adjustBranchStock($updateProduction->previous_product_id, $updateProduction->previous_variant_id, $updateProduction->branch_id);
            }
        }

        if ($updateProduction->store_warehouse_id != $updateProduction->previous_store_warehouse_id) {

            $productStockService->adjustWarehouseStock($updateProduction->product_id, $updateProduction->variant_id, $updateProduction->previous_store_warehouse_id);
        }

        if ($updateProduction->stock_warehouse_id != $updateProduction->previous_stock_warehouse_id) {

            $productionIngredients = $productionIngredientService->productionIngredients()
                ->where('production_id', $updateProduction->id)->get();

            foreach ($productionIngredients as $productionIngredient) {

                $productStockService->adjustWarehouseStock($productionIngredient->product_id, $productionIngredient->variant_id, $updateProduction->previous_stock_warehouse_id);
            }
        }

        return null;
    }

    public function deleteMethodContainer(
        int $id,
        object $productionService,
        object $productStockService,
    ): ?array {
        $deleteProduction = $productionService->deleteProduction(id: $id);

        if (isset($deleteProduction['pass']) && $deleteProduction['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        if ($deleteProduction->status == ProductionStatus::Final->value) {

            if (isset($deleteProduction->store_warehouse_id)) {

                $productStockService->adjustWarehouseStock($deleteProduction->product_id, $deleteProduction->variant_id, $deleteProduction->store_warehouse_id);
            } else {

                $productStockService->adjustBranchStock($deleteProduction->product_id, $deleteProduction->variant_id, $deleteProduction->branch_id);
            }

            foreach ($deleteProduction->ingredients as $ingredient) {

                if (isset($deleteProduction->stock_warehouse_id)) {

                    $productStockService->adjustWarehouseStock($ingredient->product_id, $ingredient->variant_id, $deleteProduction->stock_warehouse_id);
                } else {

                    $productStockService->adjustBranchStock($ingredient->product_id, $ingredient->variant_id, $deleteProduction->branch_id);
                }
            }
        }

        return null;
    }
}
