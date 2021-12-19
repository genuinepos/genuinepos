<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use App\Utils\ProductStockUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Models\Manufacturing\Production;
use App\Utils\Manufacturing\ProductionUtil;
use App\Models\Manufacturing\ProductionIngredient;
use App\Models\Product;

class ProductionController extends Controller
{
    protected $invoiceVoucherRefIdUtil;
    protected $productStockUtil;
    protected $productionUtil;
    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        ProductStockUtil $productStockUtil,
        ProductionUtil $productionUtil,
    ) {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->productStockUtil = $productStockUtil;
        $this->productionUtil = $productionUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        if ($request->ajax()) {
            return $this->productionUtil->productionList($request);
        }
        return view('manufacturing.production.index', compact('branches'));
    }

    public function create()
    {
        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')
            ->where('branch_id', auth()->user()->branch_id)
            ->get();
        $taxes = DB::table('taxes')->select('id', 'tax_percent', 'tax_name')->get();
        $products =  DB::table('processes')
            ->leftJoin('products', 'processes.product_id', 'products.id')
            ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
            ->select(
                'processes.id',
                'processes.total_output_qty',
                'processes.unit_id',
                'products.id as p_id',
                'products.name as p_name',
                'products.product_code as p_code',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_code as v_code',
            )->get();
        return view('manufacturing.production.create', compact('warehouses', 'products', 'taxes'));
    }

    public function store(Request $request)
    {
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }
        
        $this->validate($request, [
            'date' => 'required',
            'output_quantity' => 'required',
            'final_output_quantity' => 'required',
            'total_cost' => 'required',
        ]);

        if (isset($request->store_warehouse_id) && isset($request->stock_warehouse_id)) {
            $this->validate($request, [
                'store_warehouse_id' => 'required',
                'stock_warehouse_id' => 'required',
            ]);
        }

        if (!isset($request->product_ids)) {
            return response()->json(['errorMsg' => 'Ingredients list must not be empty.']);
        }

        $generalSetting = DB::table('general_settings')->select('mf_settings')->first();
        $referenceNoPrefix = json_decode($generalSetting->mf_settings, true)['production_ref_prefix'];

        $updateLastEntry = Production::where('is_last_entry', 1)->select('id', 'is_last_entry')->first();
        if ($updateLastEntry) {
            $updateLastEntry->is_last_entry = 0;
            $updateLastEntry->save();
        }

        $addProduction = new Production();
        $addProduction->reference_no = $request->reference_no ? $request->reference_no : ($referenceNoPrefix != null ? $referenceNoPrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('productions'), 5, "0", STR_PAD_LEFT);
        $addProduction->branch_id = auth()->user()->branch_id;
        $addProduction->warehouse_id = isset($request->store_warehouse_id) ? $request->store_warehouse_id : NULL;

        if (isset($request->stock_warehouse_id)) {
            $addProduction->stock_warehouse_id = $request->stock_warehouse_id;
        } else {
            $addProduction->stock_branch_id = auth()->user()->branch_id;
        }

        $addProduction->date = $request->date;
        $addProduction->time = date('h:i:s a');
        $addProduction->report_date = date('Y-m-d', strtotime($request->date));
        $addProduction->product_id = $request->product_id;
        $addProduction->variant_id = $request->variant_id;
        $addProduction->unit_id = $request->unit_id;
        $addProduction->total_ingredient_cost = $request->total_ingredient_cost;
        $addProduction->quantity = $request->output_quantity;
        $addProduction->parameter_quantity = $request->parameter_quantity;
        $addProduction->wasted_quantity = $request->wasted_quantity;
        $addProduction->total_final_quantity = $request->final_output_quantity;
        $addProduction->production_cost = $request->production_cost;
        $addProduction->total_cost = $request->total_cost;
        $addProduction->tax_id = $tax_id;
        $addProduction->tax_type = $request->tax_type;
        $addProduction->unit_cost_exc_tax = $request->per_unit_cost_exc_tax;
        $addProduction->unit_cost_inc_tax = $request->per_unit_cost_inc_tax;
        $addProduction->x_margin = $request->xMargin;
        $addProduction->price_exc_tax = $request->selling_price;
        $addProduction->is_final = isset($request->is_final) ? 1 : 0;
        $addProduction->is_last_entry = 1;
        $addProduction->save();

        if (isset($request->product_ids)) {
            $index = 0;
            foreach ($request->product_ids as $product_id) {
                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
                $addProductionIngredient = new ProductionIngredient();
                $addProductionIngredient->production_id = $addProduction->id;
                $addProductionIngredient->product_id = $product_id;
                $addProductionIngredient->variant_id = $variant_id;
                $addProductionIngredient->unit_id = $request->unit_ids[$index];
                $addProductionIngredient->input_qty = $request->input_quantities[$index];
                $addProductionIngredient->parameter_quantity = $request->parameter_input_quantities[$index];
                $addProductionIngredient->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $addProductionIngredient->subtotal = $request->subtotals[$index];
                $addProductionIngredient->save();

                if (json_decode($generalSetting->mf_settings, true)['enable_editing_ingredient_qty'] == '1') {
                    if (isset($request->is_final)) {
                        $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);
                        if (isset($request->stock_warehouse_id)) {
                            $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $request->stock_warehouse_id);
                        } else {
                            $this->productStockUtil->adjustBranchStock($product_id, $variant_id, auth()->user()->branch_id);
                        }
                    }
                }
                $index++;
            }
        }

        if (isset($request->is_final)) {
            if (json_decode($generalSetting->mf_settings, true)['enable_updating_product_price'] == '1') {
                $this->productionUtil->updateProductAndVariantPriceByProduction($request->product_id, $request->variant_id, $request->per_unit_cost_exc_tax, $request->per_unit_cost_inc_tax, $request->xMargin, $request->selling_price, $tax_id, $request->tax_type);
            }

            $this->productStockUtil->adjustMainProductAndVariantStock($request->product_id, $request->variant_id);

            if (isset($request->store_warehouse_id)) {
                $this->productStockUtil->addWarehouseProduct($request->product_id, $request->variant_id, $request->stock_warehouse_id);
                $this->productStockUtil->adjustWarehouseStock($request->product_id, $request->variant_id, $request->store_warehouse_id);
            } else {
                $this->productStockUtil->addBranchProduct($request->product_id, $request->variant_id, auth()->user()->branch_id);
                $this->productStockUtil->adjustBranchStock($request->product_id, $request->variant_id, auth()->user()->branch_id);
            }
        }

        if ($request->action_type == 'save_and_print') {
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
            ])->where('id', $addProduction->id)->first();
            return view('manufacturing.production.save_and_print_template.print', compact('production'));
        } else {
            return response()->json(['successMsg' => 'Successfully production is created.']);
        }
    }

    public function show($productionId)
    {
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

        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')
            ->where('branch_id', auth()->user()->branch_id)
            ->get();
        $taxes = DB::table('taxes')->select('id', 'tax_percent', 'tax_name')->get();
        return view('manufacturing.production.edit', compact('warehouses', 'production', 'taxes'));
    }

    public function update(Request $request, $productionId)
    {
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $this->validate($request, [
            'date' => 'required',
            'output_quantity' => 'required',
            'final_output_quantity' => 'required',
            'total_cost' => 'required',
        ]);

        if (isset($request->store_warehouse_id) && isset($request->stock_warehouse_id)) {
            $this->validate($request, [
                'store_warehouse_id' => 'required',
            ]);
        }

        if (!isset($request->product_ids)) {
            return response()->json(['errorMsg' => 'Ingredients list must not be empty.']);
        }

        $generalSetting = DB::table('general_settings')->select('mf_settings')->first();
        $referenceNoPrefix = json_decode($generalSetting->mf_settings, true)['production_ref_prefix'];

        $updateProduction = Production::where('id', $productionId)->first();
        $storedWarehouseId = $updateProduction->warehouse_id;

        $updateProduction->reference_no = $request->reference_no ? $request->reference_no : ($referenceNoPrefix != null ? $referenceNoPrefix : '') . str_pad($this->invoiceVoucherRefIdUtil->getLastId('productions'), 5, "0", STR_PAD_LEFT);
        $updateProduction->branch_id = auth()->user()->branch_id;
        $updateProduction->warehouse_id = isset($request->store_warehouse_id) ? $request->store_warehouse_id : NULL;
        $updateProduction->date = $request->date;
        $updateProduction->time = date('h:i:s a');
        $updateProduction->report_date = date('Y-m-d', strtotime($request->date));
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

        if (isset($request->product_ids)) {
            $index = 0;
            foreach ($request->product_ids as $product_id) {
                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;
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

                if (json_decode($generalSetting->mf_settings, true)['enable_editing_ingredient_qty'] == '1') {
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

        if (json_decode($generalSetting->mf_settings, true)['enable_updating_product_price'] == '1') {
            if ($updateProduction->is_last_entry == 1) {
                $this->productionUtil->updateProductAndVariantPriceByProduction($updateProduction->product_id, $updateProduction->variant_id, $request->per_unit_cost_exc_tax, $request->per_unit_cost_inc_tax, $request->xMargin, $request->selling_price, $tax_id, $request->tax_type);
            }
        }

        $this->productStockUtil->adjustMainProductAndVariantStock($updateProduction->product_id, $updateProduction->variant_id);
        if (isset($request->store_warehouse_id)) {
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
        $this->productionUtil->deleteProduction($productionId);
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
            )
            ->where('processes.id', $processId)->first();
        return response()->json($process);
    }

    public function getIngredients($processId, $warehouseId)
    {
        $warehouseId = $warehouseId;
        $ingredients = DB::table('process_ingredients')
            ->leftJoin('products', 'process_ingredients.product_id', 'products.id')
            ->leftJoin('product_variants', 'process_ingredients.variant_id', 'product_variants.id')
            ->leftJoin('units', 'process_ingredients.unit_id', 'units.id')
            ->where('process_ingredients.process_id', $processId)
            ->select(
                'process_ingredients.*',
                'products.id as p_id',
                'products.name as p_name',
                'products.product_code as p_code',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_code as v_code',
                'units.id as u_id',
                'units.name as u_name',
            )->get();

        return view('manufacturing.production.ajax_view.ingredient_list', compact('ingredients', 'warehouseId'));
    }
}
