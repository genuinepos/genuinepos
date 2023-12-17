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
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->allowanceAndDeductionService->allowancesAndDeductionsTable();
        }

        return view('hrm.allowances_and_deductions.index');
    }

    public function create()
    {
        return view('hrm.allowances_and_deductions.ajax_view.create');
    }

    public function store(Request $request)
    {
        $this->allowanceAndDeductionService->storeValidation(request: $request);
        $addAllowanceOrDeduction = $this->allowanceAndDeductionService->addAllowanceOrDeduction(request: $request);

        return response()->json($addAllowanceOrDeduction . ' ' . __('is added successfully'));
    }

    public function edit($id)
    {
        $allowance = $this->allowanceAndDeductionService->singleAllowanceOrDeduction(id: $id);
        return view('hrm.allowances_and_deductions.ajax_view.edit', compact('allowance'));
    }

    public function update($id, Request $request)
    {
        $this->allowanceAndDeductionService->updateValidation(request: $request, id: $id);
        $updateAllowanceOrDeduction = $this->allowanceAndDeductionService->updateAllowanceOrDeduction(request: $request, id: $id);

        return response()->json($updateAllowanceOrDeduction . ' ' . __('is updated successfully'));
    }

    public function delete($id, Request $request)
    {
        $deleteAllowanceOrDeduction = $this->allowanceAndDeductionService->deleteAllowanceOrDeduction(id: $id);
        return response()->json($deleteAllowanceOrDeduction . ' ' . __('deleted successfully'));
    }
}
