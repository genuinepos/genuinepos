<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Accounts\PaymentStoreRequest;
use App\Http\Requests\Accounts\PaymentDeleteRequest;
use App\Http\Requests\Accounts\PaymentUpdateRequest;
use App\Interfaces\Accounts\PaymentControllerMethodContainersInterface;

class PaymentController extends Controller
{
    public function index(Request $request, PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $debitAccountId = null)
    {
        abort_if(!auth()->user()->can('payments_index'), 403);

        $indexMethodContainer = $paymentControllerMethodContainersInterface->indexMethodContainer(request: $request, debitAccountId: $debitAccountId);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('accounting.accounting_vouchers.payments.index', compact('branches', 'debitAccounts'));
    }

    public function show(PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $id)
    {
        $showMethodContainer = $paymentControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('accounting.accounting_vouchers.payments.ajax_view.show', compact('payment'));
    }

    public function print($id, Request $request, PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface)
    {
        $printMethodContainer = $paymentControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('accounting.accounting_vouchers.print_templates.print_payment', compact('payment', 'printPageSize'));
    }

    public function create(PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $debitAccountId = null)
    {
        abort_if(!auth()->user()->can('payments_create'), 403);

        $createMethodContainer = $paymentControllerMethodContainersInterface->createMethodContainer(debitAccountId: $debitAccountId);

        extract($createMethodContainer);

        return view('accounting.accounting_vouchers.payments.ajax_view.create', compact('vouchers', 'account', 'accounts', 'methods', 'payableAccounts'));
    }

    public function store(PaymentStoreRequest $request, PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $paymentControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('accounting.accounting_vouchers.print_templates.print_payment', compact('payment', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Payment added successfully.')]);
        }
    }

    public function edit(PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $id, $debitAccountId = null)
    {
        abort_if(!auth()->user()->can('payments_edit'), 403);

        $editMethodContainer = $paymentControllerMethodContainersInterface->editMethodContainer(id: $id, debitAccountId: $debitAccountId);

        extract($editMethodContainer);

        return view('accounting.accounting_vouchers.payments.ajax_view.edit', compact('payment', 'vouchers', 'account', 'accounts', 'methods', 'payableAccounts'));
    }

    public function update(PaymentUpdateRequest $request, PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $id)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $paymentControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payment updated successfully.'));
    }

    public function delete(PaymentDeleteRequest $request, PaymentControllerMethodContainersInterface $paymentControllerMethodContainersInterface, $id)
    {
        try {
            DB::beginTransaction();

            $paymentControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payment deleted successfully.'));
    }
}
