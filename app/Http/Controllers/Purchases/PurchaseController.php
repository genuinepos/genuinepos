<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchases\PurchaseStoreRequest;
use App\Http\Requests\Purchases\PurchaseDeleteRequest;
use App\Http\Requests\Purchases\PurchaseUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Purchases\PurchaseControllerMethodContainersInterface;

class PurchaseController extends Controller
{
    public function index(Request $request, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface, $supplierAccountId = null)
    {
        abort_if(!auth()->user()->can('purchase_all'), 403);

        $indexMethodContainer = $purchaseControllerMethodContainersInterface->indexMethodContainer(request: $request, supplierAccountId: $supplierAccountId);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('purchase.purchases.index', compact('branches', 'supplierAccounts', 'purchaseAccounts'));
    }

    public function show($id, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        $showMethodContainer = $purchaseControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('purchase.purchases.ajax_view.show', compact('purchase'));
    }

    function print($id, Request $request, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        $printMethodContainer = $purchaseControllerMethodContainersInterface->printMethodContainer(request: $request, id: $id);

        extract($printMethodContainer);

        return view('purchase.print_templates.print_purchase', compact('purchase', 'printPageSize'));
    }

    public function create(CodeGenerationServiceInterface $codeGenerator, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('purchase_add'), 403);

        $createMethodContainer = $purchaseControllerMethodContainersInterface->createMethodContainer(codeGenerator: $codeGenerator);

        extract($createMethodContainer);

        return view('purchase.purchases.create', compact('invoiceId', 'warehouses', 'methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts'));
    }

    public function store(PurchaseStoreRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $purchaseControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 2) {

            return response()->json(['successMsg' => __('Successfully purchase is created.')]);
        } else {

            return view('purchase.print_templates.print_purchase', compact('purchase', 'payingAmount', 'printPageSize'));
        }
    }

    public function edit($id, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('purchase_edit'), 403);

        $editMethodContainer = $purchaseControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('purchase.purchases.edit', compact('warehouses', 'methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts', 'purchase', 'ownBranchIdOrParentBranchId'));
    }

    public function update($id, PurchaseUpdateRequest $request, CodeGenerationServiceInterface $codeGenerator, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $purchaseControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Purchase updated Successfully.'));
    }

    public function delete($id, PurchaseDeleteRequest $request, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $purchaseControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Purchase is deleted successfully'));
    }

    public function searchPurchasesByInvoiceId($keyWord, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        $searchPurchasesByInvoiceIdMethodContainer = $purchaseControllerMethodContainersInterface->searchPurchasesByInvoiceIdMethodContainer(keyWord: $keyWord);
        if (isset($searchPurchasesByInvoiceIdMethodContainer['noResult'])) {

            return ['noResult' => 'no result'];
        } else {

            return $searchPurchasesByInvoiceIdMethodContainer;
        }
    }

    public function purchaseInvoiceId(CodeGenerationServiceInterface $codeGenerator, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        return $purchaseControllerMethodContainersInterface->purchaseInvoiceIdMethodContainer(codeGenerator: $codeGenerator);
    }
}
