<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\QuotationEditRequest;
use App\Http\Requests\Sales\QuotationIndexRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Sales\QuotationUpdateRequest;
use App\Interfaces\Sales\QuotationControllerMethodContainersInterface;

class QuotationController extends Controller
{
    public function index(QuotationIndexRequest $request, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface)
    {
        $indexMethodContainer = $quotationControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('sales.add_sale.quotations.index', compact('branches', 'customerAccounts'));
    }

    public function show($id, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface)
    {
        $showMethodContainer = $quotationControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('sales.add_sale.quotations.ajax_views.show', compact('quotation', 'customerCopySaleProducts'));
    }

    public function edit($id, QuotationEditRequest $request, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface)
    {
        $editMethodContainer = $quotationControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('sales.add_sale.quotations.edit', compact('quotation', 'customerAccounts', 'accounts', 'saleAccounts', 'taxAccounts', 'methods', 'priceGroups', 'priceGroupProducts'));
    }

    public function update($id, QuotationUpdateRequest $request, CodeGenerationServiceInterface $codeGenerator, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $quotationControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Quotation updated Successfully.'));
    }

    public function editStatus($id, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface)
    {
        $editStatusMethodContainer = $quotationControllerMethodContainersInterface->editStatusMethodContainer(id: $id);
        extract($editStatusMethodContainer);

        return view('sales.add_sale.quotations.ajax_views.change_quotation_status', compact('quotation'));
    }

    public function updateStatus($id, Request $request, CodeGenerationServiceInterface $codeGenerator, QuotationControllerMethodContainersInterface $quotationControllerMethodContainersInterface)
    {
        $updateStatusMethodContainer = $quotationControllerMethodContainersInterface->updateStatusMethodContainer(id: $id, request: $request, codeGenerator: $codeGenerator);

        if (isset($updateStatusMethodContainer['pass']) && $updateStatusMethodContainer['pass'] == false) {

            return response()->json(['errorMsg' => $updateStatusMethodContainer['msg']]);
        }

        return response()->json(__('Quotation status is updated successfully'));
    }
}
