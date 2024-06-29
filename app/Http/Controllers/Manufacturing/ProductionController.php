<?php

namespace App\Http\Controllers\Manufacturing;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CodeGenerationService;
use App\Http\Requests\Manufacturing\ProductionStoreRequest;
use App\Http\Requests\Manufacturing\ProductionDeleteRequest;
use App\Http\Requests\Manufacturing\ProductionUpdateRequest;
use App\Interfaces\Manufacturing\ProductionControllerMethodContainersInterface;

class ProductionController extends Controller
{
    public function index(Request $request, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('production_view') || config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::False->value, 403);

        $indexMethodContainer = $productionControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('manufacturing.production.index', compact('branches'));
    }

    public function show($id, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('production_view') || config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::False->value, 403);

        $showMethodContainer = $productionControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('manufacturing.production.ajax_view.show', compact('production'));
    }

    public function print($id, Request $request, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        $printMethodContainer = $productionControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('manufacturing.print_templates.print_production', compact('production', 'printPageSize'));
    }

    public function create(ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('production_add') || config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::False->value, 403);

        $createMethodContainer = $productionControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('manufacturing.production.create', compact('warehouses', 'processes', 'taxAccounts'));
    }

    public function store(ProductionStoreRequest $request, CodeGenerationService $codeGenerator, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $productionControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action_type == 'save_and_print') {

            return view('manufacturing.print_templates.print_production', compact('production', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Production is added Successfully')]);
        }
    }

    public function edit($id, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('production_edit') || config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::False->value, 403);

        $editMethodContainer = $productionControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('manufacturing.production.edit', compact('warehouses', 'production', 'processes', 'taxAccounts'));
    }

    public function update($id, ProductionUpdateRequest $request, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $productionControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Production is updated successfully.'));
    }

    public function delete($id, ProductionDeleteRequest $request, ProductionControllerMethodContainersInterface $productionControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $productionControllerMethodContainersInterface->deleteMethodContainer(id: $id);

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
