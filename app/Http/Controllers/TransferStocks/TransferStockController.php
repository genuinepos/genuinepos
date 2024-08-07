<?php

namespace App\Http\Controllers\TransferStocks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CodeGenerationService;
use App\Http\Requests\TransferStocks\TransferStockEditRequest;
use App\Http\Requests\TransferStocks\TransferStockIndexRequest;
use App\Http\Requests\TransferStocks\TransferStockStoreRequest;
use App\Http\Requests\TransferStocks\TransferStockCreateRequest;
use App\Http\Requests\TransferStocks\TransferStockDeleteRequest;
use App\Http\Requests\TransferStocks\TransferStockUpdateRequest;
use App\Interfaces\TransferStocks\TransferStockControllerMethodContainersInterface;

class TransferStockController extends Controller
{
    public function index(TransferStockIndexRequest $request, TransferStockControllerMethodContainersInterface $transferStockControllerMethodContainersInterface)
    {
        $indexMethodContainer = $transferStockControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('transfer_stocks.index', compact('branches'));
    }

    public function show($id, TransferStockControllerMethodContainersInterface $transferStockControllerMethodContainersInterface)
    {
        $showMethodContainer = $transferStockControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('transfer_stocks.ajax_view.show', compact('transferStock'));
    }

    public function print($id, Request $request, TransferStockControllerMethodContainersInterface $transferStockControllerMethodContainersInterface)
    {
        $printMethodContainer = $transferStockControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('transfer_stocks.print_templates.print_transfer_stock', compact('transferStock', 'printPageSize'));
    }

    public function create(TransferStockCreateRequest $request, TransferStockControllerMethodContainersInterface $transferStockControllerMethodContainersInterface)
    {
        $createMethodContainer = $transferStockControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('transfer_stocks.create', compact('branches', 'warehouses', 'branchName'));
    }

    public function store(TransferStockStoreRequest $request, CodeGenerationService $codeGenerator, TransferStockControllerMethodContainersInterface $transferStockControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $transferStockControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('transfer_stocks.print_templates.print_transfer_stock', compact('transferStock', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Successfully transfer stock is created.')]);
        }
    }

    public function edit($id, TransferStockEditRequest $request, TransferStockControllerMethodContainersInterface $transferStockControllerMethodContainersInterface)
    {
        $editMethodContainer = $transferStockControllerMethodContainersInterface->editMethodContainer(id: $id);
        extract($editMethodContainer);

        return view('transfer_stocks.edit', compact('transferStock', 'branches', 'warehouses', 'selectedBranchWarehouses'));
    }

    public function update($id, TransferStockUpdateRequest $request, TransferStockControllerMethodContainersInterface $transferStockControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $transferStockControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Transferred Stock is updated successfully.'));
    }

    public function delete($id, TransferStockDeleteRequest $request, TransferStockControllerMethodContainersInterface $transferStockControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $transferStockControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Transferred Stock is deleted successfully.'));
    }
}
