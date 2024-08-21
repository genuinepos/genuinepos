<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\PayrollEditRequest;
use App\Http\Requests\HRM\PayrollIndexRequest;
use App\Http\Requests\HRM\PayrollStoreRequest;
use App\Http\Requests\HRM\PayrollCreateRequest;
use App\Http\Requests\HRM\PayrollDeleteRequest;
use App\Http\Requests\HRM\PayrollUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Hrm\PayrollControllerMethodContainersInterface;

class PayrollController extends Controller
{
    public function index(PayrollIndexRequest $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
        $indexMethodContainer = $payrollControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
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

    public function create(PayrollCreateRequest $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
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

    public function edit($id, PayrollEditRequest $request, PayrollControllerMethodContainersInterface $payrollControllerMethodContainersInterface)
    {
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
