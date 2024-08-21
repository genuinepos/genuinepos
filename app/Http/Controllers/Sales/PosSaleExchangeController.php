<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Sales\PosSaleExchangeControllerMethodContainersInterface;

class PosSaleExchangeController extends Controller
{
    public function searchInvoice(Request $request, PosSaleExchangeControllerMethodContainersInterface $posSaleExchangeControllerMethodContainersInterface)
    {
        $searchInvoiceMethodContainer = $posSaleExchangeControllerMethodContainersInterface->searchInvoiceMethodContainer(request: $request);

        if (isset($searchInvoiceMethodContainer['pass']) && $searchInvoiceMethodContainer['pass'] == false) {

            return response()->json(['errorMsg' => $searchInvoiceMethodContainer['msg']]);
        } else {

            return $searchInvoiceMethodContainer;
        }
    }

    public function prepareExchange(Request $request, PosSaleExchangeControllerMethodContainersInterface $posSaleExchangeControllerMethodContainersInterface)
    {
        $prepareExchangeMethodContainer = $posSaleExchangeControllerMethodContainersInterface->prepareExchangeMethodContainer(request: $request);

        if (isset($prepareExchangeMethodContainer['pass']) && $prepareExchangeMethodContainer['pass'] == false) {

            return response()->json(['errorMsg' => $prepareExchangeMethodContainer['msg']]);
        }

        extract($prepareExchangeMethodContainer);

        return response()->json(['sale' => $sale]);
    }

    public function exchangeConfirm(Request $request, CodeGenerationServiceInterface $codeGenerator, PosSaleExchangeControllerMethodContainersInterface $posSaleExchangeControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $exchangeConfirmMethodContainer = $posSaleExchangeControllerMethodContainersInterface->exchangeConfirmMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($exchangeConfirmMethodContainer['pass']) && $exchangeConfirmMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $exchangeConfirmMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        extract($exchangeConfirmMethodContainer);

        return view('sales.print_templates.sale_print', compact('sale', 'changeAmount', 'customerCopySaleProducts', 'printPageSize'));
    }
}
