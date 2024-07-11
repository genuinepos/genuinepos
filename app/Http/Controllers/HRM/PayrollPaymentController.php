<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\HRM\PayrollPaymentEditRequest;
use App\Http\Requests\HRM\PayrollPaymentStoreRequest;
use App\Http\Requests\HRM\PayrollPaymentCreateRequest;
use App\Http\Requests\HRM\PayrollPaymentDeleteRequest;
use App\Http\Requests\HRM\PayrollPaymentUpdateRequest;
use App\Interfaces\Hrm\PayrollPaymentControllerMethodContainersInterface;

class PayrollPaymentController extends Controller
{
    public function show($id, PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface)
    {
        $showMethodContainer = $payrollPaymentControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('hrm.payroll_payments.show', compact('payment'));
    }

    public function print(
        $id,
        Request $request,
        PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface
    ) {
        $printMethodContainer = $payrollPaymentControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('hrm.print_templates.print_payroll_payment', compact('payment', 'printPageSize'));
    }

    public function create(
        $payrollId,
        PayrollPaymentCreateRequest $request,
        PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface
    ) {
        $createMethodContainer = $payrollPaymentControllerMethodContainersInterface->createMethodContainer(payrollId: $payrollId);

        extract($createMethodContainer);

        return view('hrm.payroll_payments.create', compact('accounts', 'expenseAccounts', 'methods', 'payroll'));
    }

    public function store(
        PayrollPaymentStoreRequest $request,
        CodeGenerationServiceInterface $codeGenerator,
        PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface
    ) {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $payrollPaymentControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            extract($storeMethodContainer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('hrm.print_templates.print_payroll_payment', compact('payment', 'printPageSize'));
        } else {

            return response()->json(['successMsg' => __('Payroll payment added successfully.')]);
        }
    }

    public function edit(
        $id,
        PayrollPaymentEditRequest $request,
        PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface
    ) {
        $editMethodContainer = $payrollPaymentControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('hrm.payroll_payments.edit', compact('accounts', 'expenseAccounts', 'methods', 'payment'));
    }

    public function update(
        $id,
        PayrollPaymentUpdateRequest $request,
        PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface
    ) {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $payrollPaymentControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payroll payment updated successfully.'));
    }

    public function delete(
        $id,
        PayrollPaymentDeleteRequest $request,
        PayrollPaymentControllerMethodContainersInterface $payrollPaymentControllerMethodContainersInterface
    ) {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $payrollPaymentControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Payroll payment deleted successfully.'));
    }
}
