<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountService;
use App\Services\Products\ProductService;
use App\Services\Manufacturing\ProcessService;

class ProcessController extends Controller
{
    public function __construct(
        private ProcessService $processService,
        private AccountService $accountService,
        private ProductService $productService,
    ) {
    }

    // Process index view method
    public function index(Request $request)
    {
        if (!auth()->user()->can('process_view')) {

            abort(403, 'Access Forbidden.');
        }

        // if ($request->ajax()) {

        // return $this->processUtil->processTable($request);
        // }

        return view('manufacturing.process.index');
    }

    function selectProductModal()
    {
        $products = $this->productService->branchProducts(branchId: auth()->user()->branch_id, withVariant: true);
        return view('manufacturing.process.ajax_view.process_select_product_modal', compact('products'));
    }

    // public function show($processId)
    // {
    //     if (! auth()->user()->can('process_view')) {
    //         return response()->json('Access Denied');
    //     }

    //     $process = Process::with([
    //         'product',
    //         'variant',
    //         'unit',
    //         'ingredients',
    //         'ingredients.product',
    //         'ingredients.unit',
    //         'ingredients.variant',
    //     ])->where('id', $processId)->first();

    //     return view('manufacturing.process.ajax_view.show', compact('process'));
    // }

    public function create(Request $request)
    {
        if (!auth()->user()->can('process_add')) {

            abort(403, 'Access Forbidden.');
        }

        $productAndVariantId = explode('-', $request->product_id);
        $product_id = $productAndVariantId[0];
        $variant_id = $productAndVariantId[1] != 'noid' ? $productAndVariantId[1] : null;

        $checkSameItemProcess = $this->processService->process()->where('product_id', $product_id)->where('variant_id', $variant_id)->first();

        if ($checkSameItemProcess) {

            return redirect()->route('manufacturing.process.edit', $checkSameItemProcess->id);
        }

        $product = $this->processService->getProcessableProductForCreate(request: $request);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        return view('manufacturing.process.create', compact('product', 'taxAccounts'));
    }

    // public function store(Request $request)
    // {
    //     if (! auth()->user()->can('process_add')) {
    //         return response()->json('Access Denied.');
    //     }

    //     $this->validate($request, [
    //         'total_output_qty' => 'required',
    //         'unit_id' => 'required',
    //         'total_cost' => 'required',
    //     ]);

    //     $addProcess = new Process();
    //     $addProcess->branch_id = auth()->user()->branch_id;
    //     $addProcess->product_id = $request->product_id;
    //     $addProcess->variant_id = $request->variant_id != 'noid' ? $request->variant_id : null;
    //     $addProcess->total_ingredient_cost = $request->total_ingredient_cost;
    //     $addProcess->total_output_qty = $request->total_output_qty;
    //     $addProcess->unit_id = $request->unit_id;
    //     $addProcess->production_cost = $request->production_cost;
    //     $addProcess->total_cost = $request->total_cost;
    //     $addProcess->save();

    //     if (isset($request->product_ids)) {

    //         $index = 0;
    //         foreach ($request->product_ids as $product_id) {

    //             $addProcessIngredient = new ProcessIngredient();
    //             $addProcessIngredient->process_id = $addProcess->id;
    //             $addProcessIngredient->product_id = $product_id;
    //             $addProcessIngredient->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
    //             $addProcessIngredient->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
    //             $addProcessIngredient->final_qty = $request->final_quantities[$index];
    //             $addProcessIngredient->unit_id = $request->unit_ids[$index];
    //             $addProcessIngredient->subtotal = $request->subtotals[$index];
    //             $addProcessIngredient->save();
    //             $index++;
    //         }
    //     }

    //     return response()->json('Manufacturing Process created successfully');
    // }

    // public function edit($processId)
    // {
    //     if (! auth()->user()->can('process_edit')) {
    //         abort(403, 'Access Forbidden.');
    //     }

    //     $process = DB::table('processes')->where('processes.id', $processId)
    //         ->leftJoin('products', 'processes.product_id', 'products.id')
    //         ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
    //         ->select(
    //             'processes.*',
    //             'products.id as p_id',
    //             'products.name as p_name',
    //             'products.product_code as p_code',
    //             'product_variants.id as v_id',
    //             'product_variants.variant_name as v_name',
    //             'product_variants.variant_code as v_code',
    //         )->first();

    //     $units = DB::table('units')->select('id', 'name')->get();

    //     $processIngredients = DB::table('process_ingredients')
    //         ->leftJoin('products', 'process_ingredients.product_id', 'products.id')
    //         ->leftJoin('product_variants', 'process_ingredients.variant_id', 'product_variants.id')
    //         ->where('process_id', $processId)
    //         ->select(
    //             'process_ingredients.*',
    //             'products.id as p_id',
    //             'products.name as p_name',
    //             'products.product_code as p_code',
    //             'product_variants.id as v_id',
    //             'product_variants.variant_name as v_name',
    //             'product_variants.variant_code as v_code',
    //         )->get();

    //     return view('manufacturing.process.edit', compact('process', 'units', 'processIngredients'));
    // }

    // public function update(Request $request, $processId)
    // {
    //     if (! auth()->user()->can('process_edit')) {
    //         return response()->json('Access Denied');
    //     }

    //     $this->validate($request, [
    //         'total_output_qty' => 'required',
    //         'unit_id' => 'required',
    //         'total_cost' => 'required',
    //     ]);

    //     $updateProcess = Process::where('id', $processId)->first();
    //     $updateProcess->product_id = $request->product_id;
    //     $updateProcess->variant_id = $request->variant_id != 'noid' ? $request->variant_id : null;
    //     $updateProcess->total_ingredient_cost = $request->total_ingredient_cost;
    //     $updateProcess->total_output_qty = $request->total_output_qty;
    //     $updateProcess->unit_id = $request->unit_id;
    //     $updateProcess->production_cost = $request->production_cost ? $request->production_cost : 0;
    //     $updateProcess->total_cost = $request->total_cost;
    //     $updateProcess->save();

    //     $existIngredients = ProcessIngredient::where('process_id', $processId)->get();
    //     foreach ($existIngredients as $existIngredient) {

    //         $existIngredient->is_delete_in_update = 1;
    //         $existIngredient->save();
    //     }

    //     $product_ids = $request->product_ids;
    //     $variant_ids = $request->variant_ids;
    //     $unit_costs_inc_tax = $request->unit_costs_inc_tax;
    //     $final_quantities = $request->final_quantities;
    //     $unit_ids = $request->unit_ids;
    //     $subtotals = $request->subtotals;

    //     if (count($request->product_ids) > 0) {

    //         $index = 0;
    //         foreach ($product_ids as $product_id) {

    //             $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : null;
    //             $updateIngredient = ProcessIngredient::where('process_id', $updateProcess->id)
    //                 ->where('product_id', $product_id)
    //                 ->where('variant_id', $variant_id)->first();

    //             if ($updateIngredient) {

    //                 $updateIngredient->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
    //                 $updateIngredient->final_qty = $final_quantities[$index];
    //                 $updateIngredient->unit_id = $unit_ids[$index];
    //                 $updateIngredient->subtotal = $subtotals[$index];
    //                 $updateIngredient->is_delete_in_update = 0;
    //                 $updateIngredient->save();
    //             } else {

    //                 $addProcessIngredient = new ProcessIngredient();
    //                 $addProcessIngredient->process_id = $updateProcess->id;
    //                 $addProcessIngredient->product_id = $product_id;
    //                 $addProcessIngredient->variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : null;
    //                 $addProcessIngredient->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
    //                 $addProcessIngredient->final_qty = $final_quantities[$index];
    //                 $addProcessIngredient->unit_id = $unit_ids[$index];
    //                 $addProcessIngredient->subtotal = $subtotals[$index];
    //                 $addProcessIngredient->save();
    //             }

    //             $index++;
    //         }
    //     }

    //     $unusedIngredients = ProcessIngredient::where('process_id', $processId)
    //         ->where('is_delete_in_update', 1)->get();

    //     foreach ($unusedIngredients as $unusedIngredient) {

    //         $unusedIngredient->delete();
    //     }

    //     return response()->json('Manufacturing Process updated successfully');
    // }

    // public function delete($processId)
    // {
    //     if (! auth()->user()->can('process_delete')) {
    //         return response()->json('Access Denied');
    //     }

    //     $process = Process::where('id', $processId)->first();
    //     if (! is_null($process)) {
    //         $process->delete();

    //         return response()->json('Manufacturing Process deleted successfully');
    //     }
    // }
}
