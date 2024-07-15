<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Services\Hrm\LeaveTypeService;
use App\Http\Requests\HRM\LeaveTypeEditRequest;
use App\Http\Requests\HRM\LeaveTypeIndexRequest;
use App\Http\Requests\HRM\LeaveTypeStoreRequest;
use App\Http\Requests\HRM\LeaveTypeCreateRequest;
use App\Http\Requests\HRM\LeaveTypeDeleteRequest;
use App\Http\Requests\HRM\LeaveTypeUpdateRequest;

class LeaveTypeController extends Controller
{
    public function __construct(private LeaveTypeService $leaveTypeService)
    {
    }

    public function index(LeaveTypeIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->leaveTypeService->leaveTypesTable();
        }
    }

    public function create(LeaveTypeCreateRequest $request)
    {
        return view('hrm.leaves.ajax_view.types.create');
    }

    public function store(LeaveTypeStoreRequest $request)
    {
        $this->leaveTypeService->addLeaveType(request: $request);

        return response()->json(__('Leave type created successfully'));
    }

    public function edit($id, LeaveTypeEditRequest $request)
    {
        $leaveType = $this->leaveTypeService->singleLeaveType(id: $id);

        return view('hrm.leaves.ajax_view.types.edit', compact('leaveType'));
    }

    public function update($id, LeaveTypeUpdateRequest $request)
    {
        $this->leaveTypeService->updateLeaveType(request: $request, id: $id);
        return response()->json(__('Leave type updated successfully'));
    }

    public function delete(LeaveTypeDeleteRequest $request, $id)
    {
        $this->leaveTypeService->deleteLeaveType(id: $id);
        return response()->json(__('Leave type deleted successfully'));
    }
}
