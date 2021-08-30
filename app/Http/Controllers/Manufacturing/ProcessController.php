<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Manufacturing\Process;
use App\Models\Manufacturing\ProcessIngredient;
use App\Utils\Manufacturing\ProcessUtil;

class ProcessController extends Controller
{
    public $processUtil;
    public function __construct(ProcessUtil $processUtil)
    {
        $this->processUtil = $processUtil;
        $this->middleware('auth:admin_and_user');
    }

    // Process index view method
    public function index(Request $request)
    {
        $products = DB::table('products')
            ->where('status', 1)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.id as v_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )
            ->get();

        if ($request->ajax()) {
            return $this->processUtil->processTable($request);
        }

        return view('manufacturing.process.index', compact('products'));
    }

    public function show($processId)
    {
        $process = Process::with([
            'product',
            'variant',
            'unit',
            'ingredients',
            'ingredients.product',
            'ingredients.unit',
            'ingredients.variant',
        ])->where('id', $processId)->first();

        return view('manufacturing.process.ajax_view.show', compact('process'));
    }

    // Process index view method
    public function create(Request $request)
    {
        $product = $this->processUtil->getProcessableProductForCreate($request);
        return view('manufacturing.process.create', compact('product'));
    }

    // Store process
    public function store(Request $request)
    {
        $this->validate($request, [
            'total_cost' => 'required',
        ]);

        $addProcess = new Process();
        $addProcess->product_id = $request->product_id;
        $addProcess->variant_id = $request->variant_id != 'noid' ? $request->variant_id : NULL;
        $addProcess->total_ingredient_cost = $request->total_ingredient_cost;
        $addProcess->wastage_percent = $request->wastage_percent;
        $addProcess->total_output_qty = $request->total_output_qty;
        $addProcess->unit_id = $request->unit_id;
        $addProcess->production_cost = $request->production_cost;
        $addProcess->total_cost = $request->total_cost;
        $addProcess->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $ingredient_wastage_percents = $request->ingredient_wastage_percents;
        $final_quantities = $request->final_quantities;
        $unit_ids = $request->unit_ids;
        $prices = $request->prices;

        if (count($request->product_ids) > 0) {
            $index = 0;
            foreach ($product_ids as $product_id) {
                $addProcessIngredient = new ProcessIngredient();
                $addProcessIngredient->process_id = $addProcess->id;
                $addProcessIngredient->product_id = $product_id;
                $addProcessIngredient->variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $addProcessIngredient->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                $addProcessIngredient->wastage_percent = $ingredient_wastage_percents[$index];
                $addProcessIngredient->final_qty = $final_quantities[$index];
                $addProcessIngredient->unit_id = $unit_ids[$index];
                $addProcessIngredient->subtotal = $prices[$index];
                $addProcessIngredient->save();
                $index++;
            }
        }

        return response()->json('Manufacturing Process created successfully');
    }



    // Edit process view with data
    public function edit($processId)
    {
        $process = DB::table('processes')->where('processes.id', $processId)
            ->leftJoin('products', 'processes.product_id', 'products.id')
            ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
            ->select(
                'processes.*',
                'products.id as p_id',
                'products.name as p_name',
                'products.product_code as p_code',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_code as v_code',
            )
            ->first();

        $units = DB::table('units')->select('id', 'name')->get();
        $processIngredients = DB::table('process_ingredients')
            ->leftJoin('products', 'process_ingredients.product_id', 'products.id')
            ->leftJoin('product_variants', 'process_ingredients.variant_id', 'product_variants.id')
            ->where('process_id', $processId)
            ->select(
                'process_ingredients.*',
                'products.id as p_id',
                'products.name as p_name',
                'products.product_code as p_code',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_code as v_code',
            )
            ->get();
        return view('manufacturing.process.edit', compact('process', 'units', 'processIngredients'));
    }

    public function update(Request $request, $processId)
    {
        $this->validate($request, [
            'total_cost' => 'required',
        ]);

        $updateProcess = Process::where('id', $processId)->first();
        $updateProcess->product_id = $request->product_id;
        $updateProcess->variant_id = $request->variant_id != 'noid' ? $request->variant_id : NULL;
        $updateProcess->total_ingredient_cost = $request->total_ingredient_cost;
        $updateProcess->wastage_percent = $request->wastage_percent;
        $updateProcess->total_output_qty = $request->total_output_qty;
        $updateProcess->unit_id = $request->unit_id;
        $updateProcess->production_cost = $request->production_cost;
        $updateProcess->total_cost = $request->total_cost;
        $updateProcess->save();

        $existIngredients = ProcessIngredient::where('process_id', $processId)->get();
        foreach ($existIngredients as $existIngredient) {
            $existIngredient->is_delete_in_update = 1;
            $existIngredient->save();
        }

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $ingredient_wastage_percents = $request->ingredient_wastage_percents;
        $final_quantities = $request->final_quantities;
        $unit_ids = $request->unit_ids;
        $prices = $request->prices;

        if (count($request->product_ids) > 0) {
            $index = 0;
            foreach ($product_ids as $product_id) {
                $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $updateIngredient = ProcessIngredient::where('process_id', $updateProcess->id)
                    ->where('product_id', $product_id)
                    ->where('variant_id', $variant_id)->first();
                if ($updateIngredient) {
                    $updateIngredient->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                    $updateIngredient->wastage_percent = $ingredient_wastage_percents[$index];
                    $updateIngredient->final_qty = $final_quantities[$index];
                    $updateIngredient->unit_id = $unit_ids[$index];
                    $updateIngredient->subtotal = $prices[$index];
                    $updateIngredient->is_delete_in_update = 0;
                    $updateIngredient->save();
                } else {
                    $addProcessIngredient = new ProcessIngredient();
                    $addProcessIngredient->process_id = $updateProcess->id;
                    $addProcessIngredient->product_id = $product_id;
                    $addProcessIngredient->variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                    $addProcessIngredient->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                    $addProcessIngredient->wastage_percent = $ingredient_wastage_percents[$index];
                    $addProcessIngredient->final_qty = $final_quantities[$index];
                    $addProcessIngredient->unit_id = $unit_ids[$index];
                    $addProcessIngredient->subtotal = $prices[$index];
                    $addProcessIngredient->save();
                }
                $index++;
            }
        }

        $unusedIngredients = ProcessIngredient::where('process_id', $processId)
            ->where('is_delete_in_update', 1)->get();
        foreach ($unusedIngredients as $unusedIngredient) {
            $unusedIngredient->delete();
        }

        return response()->json('Manufacturing Process updated successfully');
    }

    public function delete($processId)
    {
        $process = Process::where('id', $processId)->first();
        if (!is_null($process)) {
            $process->delete();
            return response()->json('Manufacturing Process deleted successfully');
        }
    }
}
