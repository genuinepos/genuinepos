<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\SalesOrderEditRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Sales\SalesOrderIndexRequest;
use App\Http\Requests\Sales\SalesOrderDeleteRequest;
use App\Http\Requests\Sales\SalesOrderUpdateRequest;
use App\Interfaces\Sales\SalesOrderControllerMethodContainersInterface;

class SalesOrderController extends Controller
{
    public function index(SalesOrderIndexRequest $request, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface, $customerAccountId = null)
    {
        $indexMethodContainer = $salesOrderControllerMethodContainersInterface->indexMethodContainer(request: $request, customerAccountId: $customerAccountId);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('sales.add_sale.orders.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface)
    {
        $showMethodContainer = $salesOrderControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('sales.add_sale.orders.ajax_views.show', compact('order', 'customerCopySaleProducts'));
    }

    public function edit($id, SalesOrderEditRequest $request, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface)
    {
        $editMethodContainer = $salesOrderControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('sales.add_sale.orders.edit', compact('order', 'customerAccounts', 'methods', 'accounts', 'saleAccounts', 'taxAccounts', 'priceGroups', 'priceGroupProducts'));
    }

    public function update($id, SalesOrderUpdateRequest $request, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $salesOrderControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Sales Order updated Successfully.'));
    }

    public function delete($id, SalesOrderDeleteRequest $request, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $salesOrderControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Sales order is deleted successfully'));
    }

    public function searchByOrderId($keyWord, SalesOrderControllerMethodContainersInterface $salesOrderControllerMethodContainersInterface)
    {
        $searchByOrderIdMethodContainer = $salesOrderControllerMethodContainersInterface->searchByOrderIdMethodContainer(keyWord: $keyWord);
        if (isset($searchByOrderIdMethodContainer['noResult'])) {

            return ['noResult' => 'no result'];
        } else {

            return $searchByOrderIdMethodContainer;
        }
    }
}
