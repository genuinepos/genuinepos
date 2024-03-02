<?php

namespace App\Http\Controllers\Manufacturing;

use App\Http\Controllers\Controller;
use App\Interfaces\Manufacturing\ProductionControllerMethodContainersInterface;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\DayBookService;
use App\Services\CodeGenerationService;
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
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('production_view') || config('generalSettings')['subscription']->features['manufacturing'] == 0, 403);

        if ($request->ajax()) {

            return $this->productionService->productionsTable($request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('manufacturing.production.index', compact('branches'));
    }

    public function show($id, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('production_view') || config('generalSettings')['subscription']->features['manufacturing'] == 0, 403);

        $showMethodContainer = $productionControllerMethodContainersInterface->showMethodContainer(
            id: $id,
            productionService: $this->productionService
        );

        extract($showMethodContainer);

        return view('manufacturing.production.ajax_view.show', compact('production'));
    }

    public function create(ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('production_add') || config('generalSettings')['subscription']->features['manufacturing'] == 0, 403);

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
        abort_if(!auth()->user()->can('production_add') || config('generalSettings')['subscription']->features['manufacturing'] == 0, 403);

        $this->productionService->productionValidation(request: $request);

        try {

            DB::beginTransaction();

            $storeMethodContainer = $productionControllerMethodContainersInterface->storeMethodContainer(
                request: $request,
                productionService: $this->productionService,
                productionIngredientService: $this->productionIngredientService,
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
        abort_if(!auth()->user()->can('production_edit') || config('generalSettings')['subscription']->features['manufacturing'] == 0, 403);

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
        abort_if(!auth()->user()->can('production_edit') || config('generalSettings')['subscription']->features['manufacturing'] == 0, 403);

        $this->productionService->productionValidation(request: $request);

        try {
            DB::beginTransaction();

            $updateMethodContainer = $productionControllerMethodContainersInterface->updateMethodContainer(
                id: $id,
                request: $request,
                productionService: $this->productionService,
                productionIngredientService: $this->productionIngredientService,
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
        abort_if(!auth()->user()->can('production_delete') || config('generalSettings')['subscription']->features['manufacturing'] == 0, 403);

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
