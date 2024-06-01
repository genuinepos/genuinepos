<?php

namespace App\Http\Controllers\HRM;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\PayrollStoreRequest;
use App\Http\Requests\HRM\PayrollDeleteRequest;
use App\Http\Requests\HRM\PayrollUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Hrm\PayrollControllerMethodContainersInterface;

class PayrollController extends Controller
{
    public function index(Request $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('payrolls_index') || config('generalSettings')['subscription']->features['hrm'] == BooleanType::False->value, 403);

        $indexMethodContainer = $payrollControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;;
        }

        extract($indexMethodContainer);

        return view('hrm.payrolls.index', compact('users', 'departments', 'branches'));
    }

    public function show($id, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        $showMethodContainer = $payrollControllerMethodContainersInterface->showMethodContainer(id: $id);

        extract($showMethodContainer);

        return view('hrm.payrolls.ajax_view.show', compact('payroll'));
    }

    public function print($id, Request $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        $printMethodContainer = $payrollControllerMethodContainersInterface->printMethodContainer(id: $id, request: $request);

        extract($printMethodContainer);

        return view('hrm.print_templates.print_payroll', compact('payroll', 'printPageSize'));
    }

    public function create(Request $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('payrolls_create') || config('generalSettings')['subscription']->features['hrm'] == BooleanType::False->value, 403);

        $createMethodContainer = $payrollControllerMethodContainersInterface->createMethodContainer(request: $request);

        extract($createMethodContainer);

        if (isset($payroll)) {

            return redirect()->route('hrm.payrolls.edit', $payroll->id);
        }

        return view('hrm.payrolls.create', compact('user', 'expenseAccounts', 'month', 'year', 'totalHours', 'totalPresent', 'allowances', 'deductions'));
    }

    public function store(PayrollStoreRequest $request, CodeGenerationServiceInterface $codeGenerator, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $payrollControllerMethodContainersInterface->storeMethodContainer(request: $request, codeGenerator: $codeGenerator);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Payroll created successfully'));
        return response()->json(__('Payroll created successfully'));
    }

    public function edit($id, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('payrolls_edit') || config('generalSettings')['subscription']->features['hrm'] == BooleanType::False->value, 403);

        $editMethodContainer = $payrollControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('hrm.payrolls.edit', compact('payroll', 'expenseAccounts', 'totalHours', 'totalPresent'));
    }

    public function update($id, PayrollUpdateRequest $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $payrollControllerMethodContainersInterface->updateMethodContainer(id: $id,request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Payroll updated successfully.'));
        return response()->json(__('Payroll updated successfully.'));
    }

    public function delete($id, PayrollDeleteRequest $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        $deleteMethodContainer = $payrollControllerMethodContainersInterface->deleteMethodContainer(id: $id);

        if ($deleteMethodContainer['pass'] == false) {

            return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
        }

        return response()->json(__('Payroll deleted successfully.'));
    }
}
