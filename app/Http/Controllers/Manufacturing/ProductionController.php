<?php

namespace App\Http\Controllers\Manufacturing;

use App\Http\Controllers\Controller;
use App\Interfaces\Manufacturing\ProductionControllerMethodContainersInterface;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\CodeGenerationService;
use App\Services\Manufacturing\ManufacturingSettingService;
use App\Services\Manufacturing\ProcessService;
use App\Services\Manufacturing\ProductionIngredientService;
use App\Services\Manufacturing\ProductionService;
use App\Services\Products\ProductLedgerService;
use App\Services\Products\ProductService;
use App\Services\Products\ProductStockService;
use App\Services\Purchases\PurchaseProductService;
use App\Services\Setups\BranchService;
use App\Services\Setups\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        private DayBookService $dayBookService,
        private BranchService $branchService,
        private WarehouseService $warehouseService
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('production_view')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->productionService->productionsTable($request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('manufacturing.production.index', compact('branches'));
    }

    public function show($id, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        if (! auth()->user()->can('production_view')) {

            return response()->json('Access Denied');
        }

        $showMethodContainer = $productionControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            productionService: $this->productionService
        );

        extract($showMethodContainer);

        return view('manufacturing.production.ajax_view.show', compact('production'));
    }

    public function create(ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        if (! auth()->user()->can('production_add')) {

            abort(403, 'Access Forbidden.');
        }

        $createMethodContainer = $productionControllerMethodContainersInterface->createMethodContainer(
            warehouseService: $this->warehouseService,
            accountService: $this->accountService,
            processService: $this->processService,
        );
        extract($createMethodContainer);

        return view('manufacturing.production.create', compact('warehouses', 'processes', 'taxAccounts'));
    }

    public function store(
        Request $request,
        CodeGenerationService $codeGenerator,
        ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface
    ) {
        if (! auth()->user()->can('production_add')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'process_id' => 'required',
            'date' => 'required|date',
            'total_output_quantity' => 'required',
            'total_final_output_quantity' => 'required',
            'net_cost' => 'required',
        ], ['process_id.required' => 'Please select the product']);

        if ($request->store_warehouse_count > 0) {

            $this->validate($request, ['store_warehouse_id' => 'required']);
        }

        try {

            DB::beginTransaction();

            $storeMethodContainer = $productionControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                productionService: $this->productionService,
                productionIngredientService: $this->productionIngredientService,
                manufacturingSettingService: $this->manufacturingSettingService,
                productService: $this->productService,
                productLedgerService: $this->productLedgerService,
                productStockService: $this->productStockService,
                purchaseProductService: $this->purchaseProductService,
                dayBookService: $this->dayBookService,
                codeGenerator: $codeGenerator,
            );

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action_type == 'save_and_print') {

            return view('manufacturing.production.save_and_print_template.print', compact('production'));
        } else {

            return response()->json(['successMsg' => __('Production is added Successfully')]);
        }
    }

    public function edit($id, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        if (! auth()->user()->can('production_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $editMethodContainer = $productionControllerMethodContainersInterface->editMethodContainer(
            id: $id,
            productionService: $this->productionService,
            warehouseService: $this->warehouseService,
            accountService: $this->accountService,
            processService: $this->processService,
        );

        extract($editMethodContainer);

        return view('manufacturing.production.edit', compact('warehouses', 'production', 'processes', 'taxAccounts'));
    }

    public function update($id, Request $request, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        if (! auth()->user()->can('production_edit')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'process_id' => 'required',
            'date' => 'required',
            'total_output_quantity' => 'required',
            'total_final_output_quantity' => 'required',
            'net_cost' => 'required',
        ], ['process_id.required' => 'Please select the product']);

        if ($request->store_warehouse_count == 1) {

            $this->validate($request, ['store_warehouse_id' => 'required']);
        }

        try {
            DB::beginTransaction();

            $updateMethodContainer = $productionControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                productionService: $this->productionService,
                productionIngredientService: $this->productionIngredientService,
                manufacturingSettingService: $this->manufacturingSettingService,
                productService: $this->productService,
                productLedgerService: $this->productLedgerService,
                productStockService: $this->productStockService,
                purchaseProductService: $this->purchaseProductService,
                dayBookService: $this->dayBookService,
            );

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Production is updated successfully.'));
    }

    public function delete($id, Request $request, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        if (! auth()->user()->can('production_delete')) {

            return response()->json('Access Denied');
        }

        try {
            DB::beginTransaction();

            $deleteMethodContainer = $productionControllerMethodContainersInterface->deleteMethodContainer(
                id: $id,
                productionService: $this->productionService,
                productStockService: $this->productStockService
            );

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Production is deleted successfully'));
    }
}
