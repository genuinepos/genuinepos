<?php

namespace App\Http\Controllers\Manufacturing;

use App\Http\Controllers\Controller;
use App\Services\Manufacturing\ProcessIngredientService;
use App\Services\Setups\BranchService;
use App\Services\Setups\WarehouseService;

class ProcessIngredientController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private ProcessIngredientService $processIngredientService,
        private WarehouseService $warehouseService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function ingredientsForProduction($processId, $warehouseId = null)
    {
        $ingredients = $this->processIngredientService->ingredientsForProduction(processId: $processId, warehouseId: $warehouseId);

        if (isset($ingredients['pass']) && $ingredients['pass'] == false) {

            return response()->json(['errorMsg' => $ingredients['msg']]);
        }

        $branchName = $this->branchService->branchName();

        $warehouse = $this->warehouseService->singleWarehouse(id: $warehouseId);

        return view('manufacturing.process.process_ingredients.ingredient_list', compact('ingredients', 'warehouse', 'branchName'));
    }
}
