<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Purchases\PurchaseReturnEditRequest;
use App\Http\Requests\Purchases\PurchaseReturnIndexRequest;
use App\Http\Requests\Purchases\PurchaseReturnStoreRequest;
use App\Http\Requests\Purchases\PurchaseReturnDeleteRequest;
use App\Http\Requests\Purchases\PurchaseReturnUpdateRequest;
use App\Interfaces\Purchases\PurchaseReturnControllerMethodContainersInterface;

class PurchaseReturnController extends Controller
{
    public function index(PurchaseReturnIndexRequest $request, PurchaseReturnControllerMethodContainersInterface $purchaseReturnControllerMethodContainersInterface)
    {
        $indexMethodContainer = $purchaseReturnControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('purchase.purchase_return.index', compact('branches', 'supplierAccounts'));
    }

    public function show($id, PurchaseReturnControllerMethodContainersInterface $purchaseReturnControllerMethodContainersInterface)
    {
        $showMethodContainer = $purchaseReturnControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('purchase.purchase_return.ajax_view.show', compact('return'));
    }

    public function print($id, Request $request, PurchaseReturnControllerMethodContainersInterface $purchaseReturnControllerMethodContainersInterface)
    {
        $printMethodContainer = $purchaseReturnControllerMethodContainersInterface->printMethodContainer(request: $request, id: $id);

        extract($printMethodContainer);

        return view('purchase.print_templates.print_purchase_return', compact('return', 'printPageSize'));
    }

    public function create(PurchaseReturnCreateRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseReturnControllerMethodContainersInterface $purchaseReturnControllerMethodContainersInterface)
    {
        $createMethodContainer = $purchaseReturnControllerMethodContainersInterface->createMethodContainer(codeGenerator: $codeGenerator);

        extract($createMethodContainer);

        return view('purchase.purchase_return.create', compact('accounts', 'methods', 'purchaseAccounts', 'warehouses', 'taxAccounts', 'supplierAccounts', 'branchName', 'voucherNo'));
    }

    public function store(PurchaseReturnStoreRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseReturnControllerMethodContainersInterface $purchaseReturnControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $purchaseReturnControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('purchase.print_templates.print_purchase_return', compact('return', 'receivedAmount', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Purchase Return Created Successfully.')]);
        }
    }

    public function edit($id, PurchaseReturnEditRequest $request, PurchaseReturnControllerMethodContainersInterface $purchaseReturnControllerMethodContainersInterface)
    {
        $editMethodContainer = $purchaseReturnControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('purchase.purchase_return.edit', compact('return', 'accounts', 'methods', 'purchaseAccounts', 'warehouses', 'taxAccounts', 'supplierAccounts', 'branchName'));
    }

    public function update($id, PurchaseReturnUpdateRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseReturnControllerMethodContainersInterface $purchaseReturnControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $purchaseReturnControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Purchase return updated Successfully.'));
    }

    public function delete($id, PurchaseReturnDeleteRequest $request, PurchaseReturnControllerMethodContainersInterface $purchaseReturnControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $purchaseReturnControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Purchase return deleted successfully'));
    }
}
