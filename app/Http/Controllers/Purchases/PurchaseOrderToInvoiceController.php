<?php

namespace App\Http\Controllers\Purchases;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Purchases\PurchaseOrderToInvoiceStoreRequest;
use App\Interfaces\Purchases\PurchaseOrderToInvoiceControllerMethodContainersInterface;

class PurchaseOrderToInvoiceController extends Controller
{
    public function create(
        CodeGenerationServiceInterface $codeGenerator,
        PurchaseOrderToInvoiceControllerMethodContainersInterface $purchaseOrderToInvoiceControllerMethodContainersInterface,
        $id = null
    ) {
        abort_if(!auth()->user()->can('purchase_order_to_invoice'), 403);

        $createMethodContainer = $purchaseOrderToInvoiceControllerMethodContainersInterface->createMethodContainer(codeGenerator: $codeGenerator, id: $id);

        extract($createMethodContainer);

        return view('purchase.order_to_invoice.create', compact('order', 'invoiceId', 'accounts', 'methods', 'purchaseAccounts', 'warehouses', 'taxAccounts', 'accountBalance'));
    }

    public function store(
        PurchaseOrderToInvoiceStoreRequest $request,
        CodeGenerationServiceInterface $codeGenerator,
        PurchaseOrderToInvoiceControllerMethodContainersInterface $purchaseOrderToInvoiceControllerMethodContainersInterface
    ) {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $purchaseOrderToInvoiceControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

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
}
