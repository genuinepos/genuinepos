<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CodeGenerationService;
use App\Http\Requests\Purchases\PurchaseStoreRequest;
use App\Http\Requests\Purchases\PurchaseUpdateRequest;
use App\Interfaces\Purchases\PurchaseControllerMethodContainersInterface;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

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
        abort_if(!auth()->user()->can('purchase_all'), 403);

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

    public function create(PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('purchase_add'), 403);

        $createMethodContainer = $purchaseControllerMethodContainersInterface->createMethodContainer();

        extract($createMethodContainer);

        return view('purchase.purchases.create', compact('warehouses', 'methods', 'accounts', 'purchaseAccounts', 'taxAccounts', 'supplierAccounts'));
    }

    public function store(PurchaseStoreRequest $request, CodeGenerationService $codeGenerator, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
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

    public function update($id, PurchaseUpdateRequest $request, CodeGenerationService $codeGenerator, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $editMethodContainer = $purchaseControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Purchase updated Successfully.'));
    }

    public function delete($id, PurchaseControllerMethodContainersInterface $purchaseControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('purchase_delete'), 403);

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

    public function searchPurchasesByInvoiceId($keyWord)
    {
        $purchases = DB::table('purchases')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->where('purchases.invoice_id', 'like', "%{$keyWord}%")
            ->where('purchases.branch_id', auth()->user()->branch_id)
            ->where('purchases.purchase_status', 1)
            ->select(
                'purchases.id as purchase_id',
                'purchases.warehouse_id',
                'purchases.invoice_id as p_invoice_id',
                'purchases.supplier_account_id',
                'warehouses.warehouse_name',
            )->limit(35)->get();

        if (count($purchases) > 0) {

            return view('search_results_view.purchase_invoice_search_result_list', compact('purchases'));
        } else {

            return response()->json(['noResult' => 'no result']);
        }
    }
}
