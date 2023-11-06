<?php

namespace App\Http\Controllers\Manufacturing;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountService;
use App\Services\Manufacturing\ProcessIngredientService;
use App\Services\Manufacturing\ProcessService;
use App\Services\Products\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessController extends Controller
{
    public function __construct(
        private ProcessService $processService,
        private ProcessIngredientService $processIngredientService,
        private AccountService $accountService,
        private ProductService $productService,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('process_view')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->processService->processTable($request);
        }

        return view('manufacturing.process.index');
    }

    public function show($id)
    {
        if (! auth()->user()->can('process_view')) {

            return response()->json('Access Denied');
        }

        $process = $this->processService->process(with: [
            'branch',
            'branch.parentBranch',
            'product',
            'variant',
            'unit',
            'ingredients',
            'ingredients.product',
            'ingredients.unit',
            'ingredients.variant',
        ])->where('id', $id)->first();

        return view('manufacturing.process.ajax_view.show', compact('process'));
    }

    public function selectProductModal()
    {
        $products = $this->productService->branchProducts(branchId: auth()->user()->branch_id, withVariant: true);

        return view('manufacturing.process.ajax_view.process_select_product_modal', compact('products'));
    }

    public function create(Request $request)
    {
        if (! auth()->user()->can('process_add')) {

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

    public function store(Request $request)
    {
        if (! auth()->user()->can('process_add')) {

            return response()->json('Access Denied.');
        }

        $this->validate($request, [
            'total_output_qty' => 'required',
            'unit_id' => 'required',
            'net_cost' => 'required',
        ]);

        try {

            DB::beginTransaction();
            $addProcess = $this->processService->addProcess(request: $request);

            if (isset($request->product_ids)) {

                $this->processIngredientService->addProcessIngredients(request: $request, processId: $addProcess->id);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Manufacturing Process created successfully'));
    }

    public function edit($id)
    {
        if (! auth()->user()->can('process_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $process = $this->processService->process(with: [
            'product',
            'variant',
            'unit',
            'ingredients',
            'ingredients.product',
            'ingredients.variant',
            'ingredients.unit',
            'ingredients.unit.baseUnit:id,name,code_name,base_unit_id',
        ])->where('id', $id)->first();

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        return view('manufacturing.process.edit', compact('process', 'taxAccounts'));
    }

    public function update($id, Request $request)
    {
        if (! auth()->user()->can('process_edit')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'total_output_qty' => 'required',
            'unit_id' => 'required',
            'net_cost' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $updateProcess = $this->processService->updateProcess(request: $request, id: $id);
            $this->processIngredientService->updateProcessIngredients(request: $request, process: $updateProcess);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Manufacturing Process updated successfully'));
    }

    public function delete($id)
    {
        if (! auth()->user()->can('process_delete')) {

            return response()->json('Access Denied');
        }

        $this->processService->deleteProcess(id: $id);

        return response()->json(__('Manufacturing Process deleted successfully'));
    }
}
