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
            'date' => 'required',
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

                        $this->productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Production->value, date: $addProduction->date, productId: $addProductionIngredient->product_id, transId: $addProduction->id, rate: $addProductionIngredient->unit_cost_inc_tax, quantityType: 'out', quantity: $addProductionIngredient->final_qty, subtotal: $addProductionIngredient->subtotal, variantId: $addProductionIngredient->variant_id, warehouseId: $addProduction->stock_warehouse_id);

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

                if ($isUpdateProductCostAndPrice == 1) {

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

    public function show($productionId)
    {
        if (!auth()->user()->can('production_view')) {

            return response()->json('Access Denied');
        }

        $production = Production::with([
            'branch',
            'stock_branch:id,name,branch_code',
            'warehouse:id,warehouse_name,warehouse_code',
            'stock_warehouse:id,warehouse_name,warehouse_code',
            'unit:id,code_name',
            'tax:id,tax_name,tax_percent',
            'product:id,name,product_code',
            'variant:id,variant_name,variant_code',
            'ingredients',
            'ingredients.product:id,name,product_code',
            'ingredients.variant:id,variant_name,variant_code',
            'ingredients.unit:id,code_name',
        ])->where('id', $productionId)->first();

        return view('manufacturing.production.ajax_view.show', compact('production'));
    }

    public function edit($productionId)
    {
        if (!auth()->user()->can('production_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $production = Production::with([
            'branch',
            'stock_branch:id,name,branch_code',
            'warehouse:id,warehouse_name,warehouse_code',
            'stock_warehouse:id,warehouse_name,warehouse_code',
            'unit:id,name',
            'tax:id,tax_name,tax_percent',
            'product:id,name,product_code',
            'variant:id,variant_name,variant_code',
            'ingredients',
            'ingredients.product:id,name,product_code',
            'ingredients.variant:id,variant_name,variant_code',
            'ingredients.unit:id,name',
        ])->where('id', $productionId)->first();

        $warehouses = DB::table('warehouse_branches')
            ->where('warehouse_branches.branch_id', auth()->user()->branch_id)
            ->orWhere('warehouse_branches.is_global', 1)
            ->leftJoin('warehouses', 'warehouse_branches.warehouse_id', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->get();

        $taxes = DB::table('taxes')->select('id', 'tax_percent', 'tax_name')->get();

        $productionAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->where('accounts.account_type', 23)
            ->get(['accounts.id', 'accounts.name']);

        return view('manufacturing.production.edit', compact('warehouses', 'production', 'taxes', 'productionAccounts'));
    }

    public function update(Request $request, $productionId)
    {
        if (!auth()->user()->can('production_edit')) {

            return response()->json('Access Denied');
        }

        $tax_id = null;
        if ($request->tax_id) {

            $tax_id = explode('-', $request->tax_id)[0];
        }

        $this->validate($request, [
            'production_account_id' => 'required',
            'date' => 'required',
            'output_quantity' => 'required',
            'final_output_quantity' => 'required',
            'total_cost' => 'required',
        ], [
            'production_account_id.required' => 'Production A/C is required',
        ]);

        if (isset($request->store_warehouse_count) && isset($request->stock_warehouse_count)) {

            $this->validate($request, [
                'store_warehouse_id' => 'required',
            ]);
        }

        if (!isset($request->product_ids)) {

            return response()->json(['errorMsg' => 'Ingredients list must not be empty.']);
        }

        $generalSettings = config('generalSettings');
        $referenceNoPrefix = $generalSettings['mf_settings__production_ref_prefix'];

        $updateProduction = Production::where('id', $productionId)->first();
        $storedWarehouseId = $updateProduction->warehouse_id;

        $updateProduction->reference_no = $request->reference_no ? $request->reference_no : ($referenceNoPrefix != null ? $referenceNoPrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('productions'), 5, '0', STR_PAD_LEFT);
        $updateProduction->production_account_id = $request->production_account_id;
        $updateProduction->branch_id = auth()->user()->branch_id;
        $updateProduction->warehouse_id = isset($request->store_warehouse_id) ? $request->store_warehouse_id : null;
        $updateProduction->date = $request->date;
        $updateProduction->time = date('h:i:s a');
        $updateProduction->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $updateProduction->total_ingredient_cost = $request->total_ingredient_cost;
        $updateProduction->quantity = $request->output_quantity;
        $updateProduction->parameter_quantity = $request->parameter_quantity;
        $updateProduction->wasted_quantity = $request->wasted_quantity;
        $updateProduction->total_final_quantity = $request->final_output_quantity;
        $updateProduction->production_cost = $request->production_cost;
        $updateProduction->total_cost = $request->total_cost;
        $updateProduction->tax_id = $tax_id;
        $updateProduction->tax_type = $request->tax_type;
        $updateProduction->unit_cost_exc_tax = $request->per_unit_cost_exc_tax;
        $updateProduction->unit_cost_inc_tax = $request->per_unit_cost_inc_tax;
        $updateProduction->x_margin = $request->xMargin;
        $updateProduction->price_exc_tax = $request->selling_price;
        $updateProduction->is_final = isset($request->is_final) ? 1 : 0;
        $updateProduction->save();

        if (isset($request->is_final)) {

            // Update Production A/C Ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 22,
                date: $request->date,
                account_id: $request->production_account_id,
                trans_id: $updateProduction->id,
                amount: $request->total_cost,
                balance_type: 'debit'
            );

            $this->purchaseSaleChainUtil->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(
                tranColName: 'production_id',
                transId: $updateProduction->id,
                branchId: auth()->user()->branch_id,
                productId: $request->product_id,
                quantity: $request->final_output_quantity,
                variantId: $request->variant_id,
                unitCostIncTax: $request->per_unit_cost_inc_tax,
                sellingPrice: $request->selling_price,
                subTotal: $request->total_cost,
                createdAt: date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))),
                xMargin: $request->xMargin,
            );
        }

        if (isset($request->product_ids)) {

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;

                $updateProductionIngredient = ProductionIngredient::where('production_id', $updateProduction->id)
                    ->where('product_id', $product_id)->where('variant_id', $variant_id)->first();

                if ($updateProductionIngredient) {

                    $updateProductionIngredient->unit_id = $request->unit_ids[$index];
                    $updateProductionIngredient->input_qty = $request->input_quantities[$index];
                    $updateProductionIngredient->parameter_quantity = $request->parameter_input_quantities[$index];
                    $updateProductionIngredient->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                    $updateProductionIngredient->subtotal = $request->subtotals[$index];
                    $updateProductionIngredient->save();
                }

                if ($generalSettings['mf_settings__enable_editing_ingredient_qty'] == '1') {

                    $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

                    if ($updateProduction->stock_warehouse_id) {

                        $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $updateProduction->stock_warehouse_id);
                    } else {

                        $this->productStockUtil->adjustBranchStock($product_id, $variant_id, auth()->user()->branch_id);
                    }
                }
                $index++;
            }
        }

        if ($generalSettings['mf_settings__enable_updating_product_price'] == '1') {

            if ($updateProduction->is_last_entry == 1) {

                $this->productionUtil->updateProductAndVariantPriceByProduction($updateProduction->product_id, $updateProduction->variant_id, $request->per_unit_cost_exc_tax, $request->per_unit_cost_inc_tax, $request->xMargin, $request->selling_price, $tax_id, $request->tax_type);
            }
        }

        $this->productStockUtil->adjustMainProductAndVariantStock($updateProduction->product_id, $updateProduction->variant_id);

        if (isset($request->store_warehouse_count)) {

            $this->productStockUtil->adjustWarehouseStock($updateProduction->product_id, $updateProduction->variant_id, $request->store_warehouse_id);

            if ($storedWarehouseId != $request->store_warehouse_id) {

                $this->productStockUtil->adjustWarehouseStock($updateProduction->product_id, $updateProduction->variant_id, $storedWarehouseId);
            }
        } else {

            $this->productStockUtil->adjustBranchStock($updateProduction->product_id, $updateProduction->variant_id, $updateProduction->branch_id);
        }

        return response()->json(['successMsg' => 'Successfully production is updated.']);
    }

    public function delete(Request $request, $productionId)
    {
        if (!auth()->user()->can('production_delete')) {

            return response()->json('Access Denied');
        }

        $this->productionUtil->deleteProduction($productionId);

        DB::statement('ALTER TABLE productions AUTO_INCREMENT = 1');

        return response()->json('Successfully production is deleted');
    }

    public function getProcess($processId)
    {
        $process = DB::table('processes')
            ->leftJoin('products', 'processes.product_id', 'products.id')
            ->leftJoin('units', 'processes.unit_id', 'units.id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->select(
                'processes.*',
                'units.name as u_name',
                'taxes.id as tax_id',
                'taxes.tax_percent',
            )->where('processes.id', $processId)->first();

        return response()->json($process);
    }
}
