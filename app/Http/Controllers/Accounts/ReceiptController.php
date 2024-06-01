<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Accounts\ReceiptStoreRequest;
use App\Http\Requests\Accounts\ReceiptDeleteRequest;
use App\Http\Requests\Accounts\ReceiptUpdateRequest;
use App\Interfaces\Accounts\ReceiptControllerMethodContainersInterface;

class ReceiptController extends Controller
{
    public function index(Request $request, ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $creditAccountId = null)
    {
        abort_if(!auth()->user()->can('receipts_index'), 403);

        $indexMethodContainer = $receiptControllerMethodContainersInterface->indexMethodContainer(request: $request, creditAccountId: $creditAccountId);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('accounting.accounting_vouchers.receipts.index', compact('branches', 'creditAccounts'));
    }

    public function show(ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $id)
    {
        $showMethodContainer = $receiptControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('accounting.accounting_vouchers.receipts.ajax_view.show', compact('receipt'));
    }

    public function print($id, Request $request, ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface)
    {
        $printMethodContainer = $receiptControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('accounting.accounting_vouchers.print_templates.print_receipt', compact('receipt', 'printPageSize'));
    }

    public function create(ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $creditAccountId = null)
    {
        abort_if(!auth()->user()->can('receipts_create'), 403);

        $createMethodContainer = $receiptControllerMethodContainersInterface->createMethodContainer(creditAccountId: $creditAccountId);

        extract($createMethodContainer);

        return view('accounting.accounting_vouchers.receipts.ajax_view.create', compact('vouchers', 'account', 'accounts', 'methods', 'receivableAccounts'));
    }

    public function store(ReceiptStoreRequest $request, ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $receiptControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.print_templates.print_receipt', compact('receipt', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Receipt added successfully.')]);
        }
    }

    public function edit(ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $id, $creditAccountId = null)
    {
        abort_if(!auth()->user()->can('receipts_edit'), 403);

        $editMethodContainer = $receiptControllerMethodContainersInterface->editMethodContainer(id: $id, creditAccountId: $creditAccountId);

        extract($editMethodContainer);

        return view('accounting.accounting_vouchers.receipts.ajax_view.edit', compact('receipt', 'vouchers', 'account', 'accounts', 'methods', 'receivableAccounts'));
    }

    public function update(ReceiptUpdateRequest $request, ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $id)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $receiptControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Receipt updated successfully.'));
    }

    public function delete(ReceiptDeleteRequest $request, ReceiptControllerMethodContainersInterface $receiptControllerMethodContainersInterface, $id)
    {
        try {
            DB::beginTransaction();

            $receiptControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Receipt deleted successfully.'));
    }
}
