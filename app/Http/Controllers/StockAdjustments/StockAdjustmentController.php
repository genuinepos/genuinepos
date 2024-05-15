<?php

namespace App\Http\Controllers\StockAdjustments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CodeGenerationService;
use App\Http\Requests\StockAdjustments\StockAdjustmentStoreRequest;
use App\Interfaces\StockAdjustments\StockAdjustmentControllerMethodContainersInterface;

class StockAdjustmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request, StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('stock_adjustment_all'), 403);

        $indexMethodContainer = $stockAdjustmentControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('stock_adjustments.index', compact('branches'));
    }

    public function show($id, StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        $showMethodContainer = $stockAdjustmentControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('stock_adjustments.ajax_view.show', compact('adjustment'));
    }

    public function print($id, Request $request, StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        $printMethodContainer = $stockAdjustmentControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('stock_adjustments.print_templates.print_stock_adjustment', compact('adjustment', 'printPageSize'));
    }

    public function create(StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('stock_adjustment_add'), 403);

        $createMethodContainer = $stockAdjustmentControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('stock_adjustments.create', compact('expenseAccounts', 'accounts', 'warehouses', 'methods', 'branchName'));
    }

    public function store(StockAdjustmentStoreRequest $request, StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface, CodeGenerationService $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $stockAdjustmentControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Stock adjustment created successfully'));

        return response()->json(__('Stock adjustment created successfully'));
    }

    public function delete($id, StockAdjustmentControllerMethodContainersInterface $stockAdjustmentControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('stock_adjustment_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteMethodContainer = $stockAdjustmentControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Stock adjustment deleted successfully.'));
    }
}
