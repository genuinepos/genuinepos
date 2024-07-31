<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Purchases\PurchaseOrderEditRequest;
use App\Http\Requests\Purchases\PurchaseOrderIndexRequest;
use App\Http\Requests\Purchases\PurchaseOrderStoreRequest;
use App\Http\Requests\Purchases\PurchaseOrderCreateRequest;
use App\Http\Requests\Purchases\PurchaseOrderDeleteRequest;
use App\Http\Requests\Purchases\PurchaseOrderUpdateRequest;
use App\Interfaces\Purchases\PurchaseOrderControllerMethodContainersInterface;

class PurchaseOrderController extends Controller
{
    public function index(PurchaseOrderIndexRequest $request, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface, $supplierAccountId = null)
    {
        $indexMethodContainer = $purchaseOrderControllerMethodContainersInterface->indexMethodContainer(request: $request, supplierAccountId: $supplierAccountId);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('purchase.orders.index', compact('branches', 'supplierAccounts'));
    }

    public function show($id, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        $showMethodContainer = $purchaseOrderControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('purchase.orders.ajax_view.show', compact('order'));
    }

    public function print($id, Request $request, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        $printMethodContainer = $purchaseOrderControllerMethodContainersInterface->printMethodContainer(request: $request, id: $id);

        extract($printMethodContainer);

        return view('purchase.print_templates.print_purchase_order', compact('order', 'printPageSize'));
    }

    public function printSupplierCopy($id, Request $request, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        $printSupplierCopyMethodContainer = $purchaseOrderControllerMethodContainersInterface->printSupplierCopyMethodContainer(request: $request, id: $id);

        extract($printSupplierCopyMethodContainer);

        return view('purchase.print_templates.print_order_supplier_copy', compact('order', 'printPageSize'));
    }

    public function create(PurchaseOrderCreateRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        $createMethodContainer = $purchaseOrderControllerMethodContainersInterface->createMethodContainer(codeGenerator: $codeGenerator);

        extract($createMethodContainer);

        return view('purchase.orders.create', compact('orderId', 'methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts'));
    }

    public function store(PurchaseOrderStoreRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $purchaseOrderControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => __('Purchase order is created successfully.')]);
        } else {

            return view('purchase.print_templates.print_purchase_order', compact('order', 'payingAmount', 'printPageSize'));
        }
    }

    public function edit($id, PurchaseOrderEditRequest $request, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        $editMethodContainer = $purchaseOrderControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('purchase.orders.edit', compact('methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts', 'order', 'ownBranchIdOrParentBranchId'));
    }

    public function update($id, PurchaseOrderUpdateRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $purchaseOrderControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Purchase order is updated successfully'));

        return response()->json(__('Purchase order is updated successfully'));
    }

    public function delete($id, PurchaseOrderDeleteRequest $request, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $purchaseOrderControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Purchase order is deleted successfully'));
    }

    public function searchByPoId($keyWord, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        $searchByPoIdMethodContainer = $purchaseOrderControllerMethodContainersInterface->searchByPoIdMethodContainer(keyWord: $keyWord);
        if (isset($searchByPoIdMethodContainer['noResult'])) {

            return ['noResult' => 'no result'];
        } else {

            return $searchByPoIdMethodContainer;
        }
    }

    public function poId(CodeGenerationServiceInterface $codeGenerator, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        return $purchaseOrderControllerMethodContainersInterface->poIdMethodContainer(codeGenerator: $codeGenerator);
    }
}
