<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Sales\SalesOrderToInvoiceStoreRequest;
use App\Http\Requests\Sales\SalesOrderToInvoiceCreateRequest;
use App\Interfaces\Sales\SalesOrderToInvoiceControllerMethodContainersInterface;

class SalesOrderToInvoiceController extends Controller
{
    public function create(
        SalesOrderToInvoiceCreateRequest $request,
        CodeGenerationServiceInterface $codeGenerator,
        SalesOrderToInvoiceControllerMethodContainersInterface $salesOrderToInvoiceControllerMethodContainersInterface,
        $id = null
    ) {
        $createMethodContainer = $salesOrderToInvoiceControllerMethodContainersInterface->createMethodContainer(codeGenerator: $codeGenerator, id: $id);

        extract($createMethodContainer);

        return view('sales.order_to_invoice.create', compact('ownBranchIdOrParentBranchId', 'branchName', 'accounts', 'methods', 'saleAccounts', 'warehouses', 'taxAccounts', 'order', 'invoiceId', 'accountBalance'));
    }

    public function store(
        SalesOrderToInvoiceStoreRequest $request,
        CodeGenerationServiceInterface $codeGenerator,
        SalesOrderToInvoiceControllerMethodContainersInterface $salesOrderToInvoiceControllerMethodContainersInterface
    ) {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $salesOrderToInvoiceControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('sales.print_templates.sale_print', compact('sale', 'receivedAmount', 'changeAmount', 'customerCopySaleProducts', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Sales Invoice created successfully')]);
        }
    }
}
