<?php

namespace App\Http\Controllers\Manufacturing;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manufacturing\ProcessStoreRequest;
use App\Http\Requests\Manufacturing\ProcessDeleteRequest;
use App\Http\Requests\Manufacturing\ProcessUpdateRequest;
use App\Interfaces\Manufacturing\ProcessControllerMethodContainersInterface;

class ProcessController extends Controller
{
    public function index(Request $request, ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('process_view') || config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::False->value, 403);

        $indexMethodContainer = $processControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        return view('manufacturing.process.index');
    }

    public function show($id, ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('process_view') || config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::False->value, 403);

        $showMethodContainer = $processControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('manufacturing.process.ajax_view.show', compact('process'));
    }

    public function print($id, Request $request, ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        $printMethodContainer = $processControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('manufacturing.print_templates.print_process', compact('process', 'printPageSize'));
    }

    public function selectProductModal(ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        $selectProductModalMethodContainer = $processControllerMethodContainersInterface->selectProductModalMethodContainer();

        extract($selectProductModalMethodContainer);

        return view('manufacturing.process.ajax_view.process_select_product_modal', compact('products'));
    }

    public function create(Request $request, ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('process_add') || config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::False->value, 403);

        $createMethodContainer = $processControllerMethodContainersInterface->createMethodContainer(request: $request);

        if(gettype($createMethodContainer) == 'object'){

           return $createMethodContainer;
        }

        extract($createMethodContainer);

        return view('manufacturing.process.create', compact('product', 'taxAccounts'));
    }

    public function store(ProcessStoreRequest $request, ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $processControllerMethodContainersInterface->storeMethodContainer(request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Manufacturing Process created successfully'));
    }

    public function edit($id, ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('process_edit') || config('generalSettings')['subscription']->features['manufacturing'] == BooleanType::False->value, 403);

        $editMethodContainer = $processControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('manufacturing.process.edit', compact('process', 'taxAccounts'));
    }

    public function update($id, ProcessUpdateRequest $request, ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $processControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Manufacturing Process updated successfully'));
    }

    public function delete($id, ProcessDeleteRequest $request, ProcessControllerMethodContainersInterface $processControllerMethodContainersInterface)
    {
        $processControllerMethodContainersInterface->deleteMethodContainer(id: $id);

        return response()->json(__('Manufacturing Process deleted successfully'));
    }
}
