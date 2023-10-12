<?php

namespace App\Http\Controllers\Manufacturing;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Enums\ProductionStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\ProductLedgerVoucherType;
use App\Services\CodeGenerationService;
use App\Models\Manufacturing\Production;
use App\Services\Accounts\AccountService;
use App\Services\Products\ProductService;
use App\Services\Setups\WarehouseService;
use App\Services\Manufacturing\ProcessService;
use App\Services\Products\ProductStockService;
use App\Services\Products\ProductLedgerService;
use App\Services\Manufacturing\ProductionService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Manufacturing\ManufacturingSettingService;
use App\Services\Manufacturing\ProductionIngredientService;

class ProductionController extends Controller
{
    public function __construct(
        private ProductionService $productionService,
        private ProductionIngredientService $productionIngredientService,
        private ManufacturingSettingService $manufacturingSettingService,
        private ProductService $productService,
        private ProductStockService $productStockService,
        private ProductLedgerService $productLedgerService,
        private PurchaseProductService $purchaseProductService,
        private ProcessService $processService,
        private AccountService $accountService,
        private BranchService $branchService,
        private WarehouseService $warehouseService
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('production_view')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->productionService->productionsTable($request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('manufacturing.production.index', compact('branches'));
    }

    public function show($id)
    {
        if (!auth()->user()->can('production_view')) {

            return response()->json('Access Denied');
        }

        $production = $this->productionService->singleProduction(with: [
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

        return view('manufacturing.production.ajax_view.show', compact('production'));
    }

    public function create()
    {
        if (!auth()->user()->can('production_add')) {

            abort(403, 'Access Forbidden.');
        }

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', auth()->user()->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $processes = $this->processService->processes();

        return view('manufacturing.production.create', compact('warehouses', 'processes', 'taxAccounts',));
    }

    public function store(Request $request, CodeGenerationService $codeGenerator)
    {
        if (!auth()->user()->can('production_add')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'process_id' => 'required',
            'date' => 'required|date',
            'total_output_quantity' => 'required',
            'total_final_output_quantity' => 'required',
            'net_cost' => 'required',
        ], [
            'process_id.required' => 'Please select the product',
        ]);

        if ($request->store_warehouse_count > 0) {

            $this->validate($request, ['store_warehouse_id' => 'required']);
        }

        try {

            DB::beginTransaction();

            $restrictions = $this->productionService->restrictions($request);
            if ($restrictions['pass'] = false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $manufacturingSetting = $this->manufacturingSettingService->manufacturingSetting()->where('branch_id', auth()->user()->branch_id)->first();
            $voucherPrefix = $manufacturingSetting?->production_voucher_prefix ? $manufacturingSetting?->production_voucher_prefix : 'MF';
            $isUpdateProductCostAndPrice = $manufacturingSetting ? $manufacturingSetting?->is_update_product_cost_and_price_in_production : BooleanType::True->value;

            $addProduction = $this->productionService->addProduction(request: $request, codeGenerator: $codeGenerator, voucherPrefix: $voucherPrefix);

            if ($addProduction->status == ProductionStatus::Final->value) {

                $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $addProduction->date, productId: $addProduction->product_id, transId: $addProduction->id, rate: $addProduction->per_unit_cost_inc_tax, quantityType: 'in', quantity: $addProduction->total_final_output_quantity, subtotal: $addProduction->net_cost, variantId: $addProduction->variant_id, warehouseId: $addProduction->store_warehouse_id);
            }

            if (isset($request->product_ids)) {

                $index = 0;
                foreach ($request->product_ids as $product_id) {

                    $addProductionIngredient = $this->productionIngredientService->addProductionIngredient(request: $request, productionId: $addProduction->id, index: $index);

                    if ($addProduction->status == ProductionStatus::Final->value) {

                        $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $addProduction->date, productId: $addProductionIngredient->product_id, transId: $addProduction->id, rate: $addProductionIngredient->unit_cost_inc_tax, quantityType: 'out', quantity: $addProductionIngredient->final_qty, subtotal: $addProductionIngredient->subtotal, variantId: $addProductionIngredient->variant_id, optionalColName: 'production_ingredient_id', optionalColValue: $addProductionIngredient->id, warehouseId: $addProduction->stock_warehouse_id);

                        $this->productStockService->adjustMainProductAndVariantStock($addProductionIngredient->product_id, $addProductionIngredient->variant_id);

                        if (isset($addProduction->stock_warehouse_id)) {

                            $this->productStockService->adjustWarehouseStock($addProductionIngredient->product_id, $addProductionIngredient->variant_id, $addProduction->stock_warehouse_id);
                        } else {

                            $this->productStockService->adjustBranchStock($addProductionIngredient->product_id, $addProductionIngredient->variant_id, auth()->user()->branch_id);
                        }
                    }

                    $index++;
                }
            }

            if ($addProduction->status == ProductionStatus::Final->value) {

                if ($isUpdateProductCostAndPrice == BooleanType::True->value) {

                    $this->productService->updateProductAndVariantPrice(productId: $addProduction->product_id, variantId: $addProduction->variant_id, unitCostWithDiscount: $addProduction->per_unit_cost_exc_tax, unitCostIncTax: $addProduction->per_unit_cost_inc_tax, profit: $addProduction->profit_margin, sellingPrice: $addProduction->per_unit_price_exc_tax, isEditProductPrice: BooleanType::True->value, isLastEntry: BooleanType::True->value);
                }

                $this->productStockService->adjustMainProductAndVariantStock($addProduction->product_id, $addProduction->variant_id);

                if (isset($addProduction->store_warehouse_id)) {

                    $this->productStockService->addWarehouseProduct($addProduction->product_id, $addProduction->variant_id, $addProduction->stock_warehouse_id);

                    $this->productStockService->adjustWarehouseStock($addProduction->product_id, $addProduction->variant_id, $addProduction->store_warehouse_id);
                } else {

                    $this->productStockService->addBranchProduct($addProduction->product_id, $addProduction->variant_id, auth()->user()->branch_id);

                    $this->productStockService->adjustBranchStock($addProduction->product_id, $addProduction->variant_id, auth()->user()->branch_id);
                }

                $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'production_id', transId: $addProduction->id, branchId: auth()->user()->branch_id, productId: $addProduction->product_id, variantId: $addProduction->variant_id, quantity: $addProduction->total_final_output_quantity, unitCostIncTax: $addProduction->per_unit_cost_inc_tax, sellingPrice: $addProduction->per_unit_price_exc_tax, subTotal: $addProduction->net_cost, createdAt: $addProduction->date_ts);
            }

            $production = $this->productionService->singleProduction(with: [
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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action_type == 'save_and_print') {

            return view('manufacturing.production.save_and_print_template.print', compact('production'));
        } else {

            return response()->json(['successMsg' => __("Production is added Successfully")]);
        }
    }

    public function edit($id)
    {
        if (!auth()->user()->can('production_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $production = $this->productionService->singleProduction(with: [
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

        $warehouses = $this->warehouseService->warehouses()->where('branch_id', $production->branch_id)
            ->orWhere('is_global', 1)->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $processes = $this->processService->processes();

        return view('manufacturing.production.edit', compact('warehouses', 'production', 'processes', 'taxAccounts'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('production_edit')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'date' => 'required',
            'total_output_quantity' => 'required',
            'total_final_output_quantity' => 'required',
            'net_cost' => 'required',
        ]);

        if ($request->store_warehouse_count == 1) {

            $this->validate($request, ['store_warehouse_id' => 'required']);
        }

        try {

            DB::beginTransaction();

            $manufacturingSetting = $this->manufacturingSettingService->manufacturingSetting()->where('branch_id', auth()->user()->branch_id)->first();
            $isUpdateProductCostAndPrice = $manufacturingSetting ? $manufacturingSetting?->is_update_product_cost_and_price_in_production : BooleanType::True->value;

            $restrictions = $this->productionService->restrictions($request);
            if ($restrictions['pass'] = false) {

                return response()->json(['errorMsg' => $restrictions['msg']]);
            }

            $updateProduction = $this->productionService->updateProduction(request: $request, productionId: $id);

            if ($updateProduction->status == ProductionStatus::Final->value) {

                $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $updateProduction->date, productId: $updateProduction->product_id, transId: $updateProduction->id, rate: $updateProduction->per_unit_cost_inc_tax, quantityType: 'in', quantity: $updateProduction->total_final_output_quantity, subtotal: $updateProduction->net_cost, variantId: $updateProduction->variant_id, warehouseId: $updateProduction->store_warehouse_id);

                if ($updateProduction->product_id != $updateProduction->previous_product_id && $updateProduction->variant_id != $updateProduction->previous_variant_id) {

                    $this->productLedgerService->deleteUnusedProductLedgerEntry(transColName: 'production_id', transId: $updateProduction->id, productId: $updateProduction->previous_product_id, variantId: $updateProduction->previous_variant_id);
                }
            }

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $updateProductionIngredient = $this->productionIngredientService->updateProductionIngredient(request: $request, productionId: $updateProduction->id, index: $index);

                if ($updateProduction->status == ProductionStatus::Final->value) {

                    $this->productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $updateProduction->date, productId: $updateProductionIngredient->product_id, transId: $updateProductionIngredient->id, rate: $updateProductionIngredient->unit_cost_inc_tax, quantityType: 'out', quantity: $updateProductionIngredient->final_qty, subtotal: $updateProductionIngredient->subtotal, variantId: $updateProductionIngredient->variant_id, optionalColName: 'production_ingredient_id', optionalColValue: $updateProductionIngredient->id, warehouseId: $updateProduction->stock_warehouse_id);

                    $this->productStockService->adjustMainProductAndVariantStock($updateProductionIngredient->product_id, $updateProductionIngredient->variant_id);

                    if (isset($updateProduction->stock_warehouse_id)) {

                        $this->productStockService->adjustWarehouseStock($updateProductionIngredient->product_id, $updateProductionIngredient->variant_id, $updateProduction->stock_warehouse_id);
                    } else {

                        $this->productStockService->adjustBranchStock($updateProductionIngredient->product_id, $updateProductionIngredient->variant_id, $updateProduction->branch_id);
                    }
                }

                $index++;
            }

            if ($updateProduction->status == ProductionStatus::Final->value) {

                if ($isUpdateProductCostAndPrice == BooleanType::True->value) {

                    $this->productService->updateProductAndVariantPrice(productId: $updateProduction->product_id, variantId: $updateProduction->variant_id, unitCostWithDiscount: $updateProduction->per_unit_cost_exc_tax, unitCostIncTax: $updateProduction->per_unit_cost_inc_tax, profit: $updateProduction->profit_margin, sellingPrice: $updateProduction->per_unit_price_exc_tax, isEditProductPrice: BooleanType::True->value, isLastEntry: BooleanType::True->value);
                }

                $this->productStockService->adjustMainProductAndVariantStock($updateProduction->product_id, $updateProduction->variant_id);

                if (isset($updateProduction->store_warehouse_id)) {

                    $this->productStockService->addWarehouseProduct($updateProduction->product_id, $updateProduction->variant_id, $updateProduction->stock_warehouse_id);

                    $this->productStockService->adjustWarehouseStock($updateProduction->product_id, $updateProduction->variant_id, $updateProduction->store_warehouse_id);
                } else {

                    $this->productStockService->addBranchProduct($updateProduction->product_id, $updateProduction->variant_id, auth()->user()->branch_id);

                    $this->productStockService->adjustBranchStock($updateProduction->product_id, $updateProduction->variant_id, auth()->user()->branch_id);
                }

                $this->purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'production_id', transId: $updateProduction->id, branchId: auth()->user()->branch_id, productId: $updateProduction->product_id, variantId: $updateProduction->variant_id, quantity: $updateProduction->total_final_output_quantity, unitCostIncTax: $updateProduction->per_unit_cost_inc_tax, sellingPrice: $updateProduction->per_unit_price_exc_tax, subTotal: $updateProduction->net_cost, createdAt: $updateProduction->date_ts);

                if ($updateProduction->product_id != $updateProduction->previous_product_id && $updateProduction->variant_id != $updateProduction->previous_variant_id) {

                    $this->purchaseProductService->deleteUnusedPurchaseProduct(transColName: 'production_id', transColValue: $updateProduction->id, productId: $updateProduction->previous_product_id, variantId: $updateProduction->previous_variant_id);
                }
            }

            $deleteUnusedProductionIngredients = $this->productionIngredientService->productionIngredients()->where('production_id', $updateProduction->id)->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();

            foreach ($deleteUnusedProductionIngredients as $deleteUnusedProductionIngredient) {

                $deleteUnusedProductionIngredient->delete();

                if (isset($updateProduction->previous_store_warehouse_id)) {

                    $this->productStockService->adjustWarehouseStock($deleteUnusedProductionIngredient->product_id, $deleteUnusedProductionIngredient->variant_id, $updateProduction->previous_store_warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock($deleteUnusedProductionIngredient->product_id, $deleteUnusedProductionIngredient->variant_id, $updateProduction->branch_id);
                }
            }

            if ($updateProduction->product_id != $updateProduction->previous_product_id && $updateProduction->variant_id != $updateProduction->previous_variant_id) {

                if (isset($updateProduction->previous_store_warehouse_id)) {

                    $this->productStockService->adjustWarehouseStock($updateProduction->previous_variant_id, $updateProduction->previous_variant_id, $updateProduction->previous_store_warehouse_id);
                } else {

                    $this->productStockService->adjustBranchStock($updateProduction->previous_variant_id, $updateProduction->previous_variant_id, $updateProduction->branch_id);
                }
            }

            if ($updateProduction->store_warehouse_id != $updateProduction->previous_store_warehouse_id) {

                $this->productStockService->adjustWarehouseStock($updateProduction->variant_id, $updateProduction->variant_id, $updateProduction->previous_store_warehouse_id);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Production is updated successfully."));
    }

    public function delete(Request $request, $productionId)
    {
        if (!auth()->user()->can('production_delete')) {

            return response()->json('Access Denied');
        }

        $this->productionUtil->deleteProduction($productionId);

        return response()->json(__("Production is deleted successfully"));
    }
}
