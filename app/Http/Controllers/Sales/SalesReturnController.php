<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Sales\SalesReturnEditRequest;
use App\Http\Requests\Sales\SalesReturnIndexRequest;
use App\Http\Requests\Sales\SalesReturnStoreRequest;
use App\Http\Requests\Sales\SalesReturnCreateRequest;
use App\Http\Requests\Sales\SalesReturnDeleteRequest;
use App\Http\Requests\Sales\SalesReturnUpdateRequest;
use App\Interfaces\Sales\SalesReturnControllerMethodContainersInterface;

class SalesReturnController extends Controller
{
    public function index(SalesReturnIndexRequest $request, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        $indexMethodContainer = $salesReturnControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('sales.sales_return.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        $showMethodContainer = $salesReturnControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('sales.sales_return.ajax_view.show', compact('return'));
    }

    public function print($id, Request $request, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        $printMethodContainer = $salesReturnControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('sales.print_templates.sales_return_print', compact('return', 'paidAmount', 'printPageSize'));
    }

    public function create(SalesReturnCreateRequest $request, CodeGenerationServiceInterface $codeGenerator, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        $createMethodContainer = $salesReturnControllerMethodContainersInterface->createMethodContainer(codeGenerator: $codeGenerator);

        extract($createMethodContainer);

        return view('sales.sales_return.create', compact('accounts', 'methods', 'saleAccounts', 'warehouses', 'priceGroups', 'priceGroupProducts', 'taxAccounts', 'customerAccounts', 'branchName', 'voucherNo'));
    }

    public function store(SalesReturnStoreRequest $request, CodeGenerationServiceInterface $codeGenerator, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $salesReturnControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('sales.print_templates.sales_return_print', compact('return', 'paidAmount', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Sales Return Created Successfully.')]);
        }
    }

    public function edit($id, SalesReturnEditRequest $request, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        $editMethodContainer = $salesReturnControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('sales.sales_return.edit', compact('return', 'accounts', 'methods', 'saleAccounts', 'warehouses', 'priceGroups', 'priceGroupProducts', 'taxAccounts', 'customerAccounts', 'branchName'));
    }

    public function update($id, SalesReturnUpdateRequest $request, CodeGenerationServiceInterface $codeGenerator, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $salesReturnControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Sales Return is updated Successfully.'));
    }

    public function delete($id, SalesReturnDeleteRequest $request, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $salesReturnControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Sales Return is deleted Successfully.'));
    }

    public function voucherNo(CodeGenerationServiceInterface $codeGenerator, SalesReturnControllerMethodContainersInterface $salesReturnControllerMethodContainersInterface)
    {
        return $salesReturnControllerMethodContainersInterface->voucherNoMethodContainer(codeGenerator: $codeGenerator);
    }
}
