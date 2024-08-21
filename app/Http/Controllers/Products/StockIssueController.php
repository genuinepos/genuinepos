<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Products\StockIssueEditRequest;
use App\Http\Requests\Products\StockIssueIndexRequest;
use App\Http\Requests\Products\StockIssueStoreRequest;
use App\Http\Requests\Products\StockIssueCreateRequest;
use App\Http\Requests\Products\StockIssueDeleteRequest;
use App\Http\Requests\Products\StockIssueUpdateRequest;
use App\Interfaces\Products\StockIssueControllerMethodContainersInterface;

class StockIssueController extends Controller
{
    public function index(StockIssueIndexRequest $request, StockIssueControllerMethodContainersInterface $stockIssueControllerMethodContainersInterface)
    {
        $indexMethodContainer = $stockIssueControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('product.stock_issues.index', compact('departments', 'users', 'branches'));
    }

    public function show($id, StockIssueControllerMethodContainersInterface $stockIssueControllerMethodContainersInterface)
    {
        $showMethodContainer = $stockIssueControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('product.stock_issues.ajax_view.show', compact('stockIssue'));
    }

    public function print($id, Request $request, StockIssueControllerMethodContainersInterface $stockIssueControllerMethodContainersInterface)
    {
        $printMethodContainer = $stockIssueControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('product.print_templates.print_stock_issue', compact('stockIssue', 'printPageSize'));
    }

    public function create(StockIssueCreateRequest $request, StockIssueControllerMethodContainersInterface $stockIssueControllerMethodContainersInterface)
    {
        $createMethodContainer = $stockIssueControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('product.stock_issues.create', compact('departments', 'users', 'warehouses', 'branchName'));
    }

    public function store(StockIssueStoreRequest $request, CodeGenerationServiceInterface $codeGenerator, StockIssueControllerMethodContainersInterface $stockIssueControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $stockIssueControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('product.print_templates.print_stock_issue', compact('stockIssue', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Stock issue created successfully.')]);
        }
    }

    public function edit($id, StockIssueEditRequest $request, StockIssueControllerMethodContainersInterface $stockIssueControllerMethodContainersInterface)
    {
        $editMethodContainer = $stockIssueControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('product.stock_issues.edit', compact('departments', 'users', 'warehouses', 'stockIssue', 'branchName'));
    }

    public function update($id, StockIssueUpdateRequest $request, StockIssueControllerMethodContainersInterface $stockIssueControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $stockIssueControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Stock issue updated successfully.'));
    }

    public function delete($id, StockIssueDeleteRequest $request, StockIssueControllerMethodContainersInterface $stockIssueControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $stockIssueControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Stock issue deleted successfully.'));
    }
}
