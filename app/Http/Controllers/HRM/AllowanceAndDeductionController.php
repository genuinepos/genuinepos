<?php

namespace App\Http\Controllers\HRM;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Hrm\Allowance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Hrm\AllowanceAndDeductionService;

class AllowanceAndDeductionController extends Controller
{
    public function __construct(private AllowanceAndDeductionService $allowanceAndDeductionService)
    {
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('allowances_index') && !auth()->user()->can('deductions_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->allowanceAndDeductionService->allowancesAndDeductionsTable();
        }

        return view('hrm.allowances_and_deductions.index');
    }

    public function create()
    {
        if (!auth()->user()->can('allowances_create') && !auth()->user()->can('deductions_create')) {

            abort(403, 'Access Forbidden.');
        }

        return view('hrm.allowances_and_deductions.ajax_view.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('allowances_create') && !auth()->user()->can('deductions_create')) {

            abort(403, 'Access Forbidden.');
        }

        $this->allowanceAndDeductionService->storeValidation(request: $request);
        $addAllowanceOrDeduction = $this->allowanceAndDeductionService->addAllowanceOrDeduction(request: $request);

        return response()->json($addAllowanceOrDeduction . ' ' . __('is added successfully'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('allowances_edit') && !auth()->user()->can('deductions_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $allowance = $this->allowanceAndDeductionService->singleAllowanceOrDeduction(id: $id);
        return view('hrm.allowances_and_deductions.ajax_view.edit', compact('allowance'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('allowances_edit') && !auth()->user()->can('deductions_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->allowanceAndDeductionService->updateValidation(request: $request, id: $id);
        $updateAllowanceOrDeduction = $this->allowanceAndDeductionService->updateAllowanceOrDeduction(request: $request, id: $id);

        return response()->json($updateAllowanceOrDeduction . ' ' . __('is updated successfully'));
    }

    public function delete($id, Request $request)
    {
        if (!auth()->user()->can('allowances_delete') && !auth()->user()->can('allowances_delete')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteAllowanceOrDeduction = $this->allowanceAndDeductionService->deleteAllowanceOrDeduction(id: $id);
        return response()->json($deleteAllowanceOrDeduction . ' ' . __('deleted successfully'));
    }
}
