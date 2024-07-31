<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Services\Hrm\AllowanceAndDeductionService;
use App\Http\Requests\HRM\AllowanceAndDeductionEditRequest;
use App\Http\Requests\HRM\AllowanceAndDeductionIndexRequest;
use App\Http\Requests\HRM\AllowanceAndDeductionStoreRequest;
use App\Http\Requests\HRM\AllowanceAndDeductionCreateRequest;
use App\Http\Requests\HRM\AllowanceAndDeductionDeleteRequest;
use App\Http\Requests\HRM\AllowanceAndDeductionUpdateRequest;

class AllowanceAndDeductionController extends Controller
{
    public function __construct(private AllowanceAndDeductionService $allowanceAndDeductionService)
    {
    }

    public function index(AllowanceAndDeductionIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->allowanceAndDeductionService->allowancesAndDeductionsTable();
        }

        return view('hrm.allowances_and_deductions.index');
    }

    public function create(AllowanceAndDeductionCreateRequest $request)
    {
        return view('hrm.allowances_and_deductions.ajax_view.create');
    }

    public function store(AllowanceAndDeductionStoreRequest $request)
    {
        $addAllowanceOrDeduction = $this->allowanceAndDeductionService->addAllowanceOrDeduction(request: $request);
        return response()->json($addAllowanceOrDeduction . ' ' . __('is added successfully'));
    }

    public function edit($id, AllowanceAndDeductionEditRequest $request)
    {
        $allowance = $this->allowanceAndDeductionService->singleAllowanceOrDeduction(id: $id);
        return view('hrm.allowances_and_deductions.ajax_view.edit', compact('allowance'));
    }

    public function update($id, AllowanceAndDeductionUpdateRequest $request)
    {
        $updateAllowanceOrDeduction = $this->allowanceAndDeductionService->updateAllowanceOrDeduction(request: $request, id: $id);
        return response()->json($updateAllowanceOrDeduction . ' ' . __('is updated successfully'));
    }

    public function delete($id, AllowanceAndDeductionDeleteRequest $request)
    {
        $deleteAllowanceOrDeduction = $this->allowanceAndDeductionService->deleteAllowanceOrDeduction(id: $id);
        return response()->json($deleteAllowanceOrDeduction . ' ' . __('deleted successfully'));
    }
}
