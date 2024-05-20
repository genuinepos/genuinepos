<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Purchases\PurchaseOrderStoreRequest;
use App\Http\Requests\Purchases\PurchaseOrderDeleteRequest;
use App\Http\Requests\Purchases\PurchaseOrderUpdateRequest;
use App\Interfaces\Purchases\PurchaseOrderControllerMethodContainersInterface;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface, $supplierAccountId = null)
    {
        abort_if(!auth()->user()->can('purchase_order_index'), 403);

        $indexMethodContainer = $purchaseOrderControllerMethodContainersInterface->indexMethodContainer(request: $request, supplierAccountId: $supplierAccountId);

        if ($request->ajax()) {

            return $indexMethodContainer;;
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

    public function create(CodeGenerationServiceInterface $codeGenerator, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('purchase_order_add'), 403);

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

    public function edit($id, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('purchase_order_edit'), 403);

        $editMethodContainer = $purchaseOrderControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('purchase.orders.edit', compact('methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts', 'order', 'ownBranchIdOrParentBranchId'));
    }

    public function update($id, PurchaseOrderUpdateRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseOrderControllerMethodContainersInterface $purchaseOrderControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $editMethodContainer = $purchaseOrderControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

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
