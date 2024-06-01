<?php

namespace App\Http\Controllers\HRM;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Hrm\LeaveTypeService;
use App\Http\Requests\HRM\LeaveTypeStoreRequest;
use App\Http\Requests\HRM\LeaveTypeDeleteRequest;
use App\Http\Requests\HRM\LeaveTypeUpdateRequest;

class LeaveTypeController extends Controller
{
    public function __construct(private LeaveTypeService $leaveTypeService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('leave_types_index'), 403);

        if ($request->ajax()) {

            return $this->leaveTypeService->leaveTypesTable();
        }
    }

    public function create()
    {
        abort_if(!auth()->user()->can('leave_types_create'), 403);
        return view('hrm.leaves.ajax_view.types.create');
    }

    public function store(LeaveTypeStoreRequest $request)
    {
        $this->leaveTypeService->addLeaveType(request: $request);
        return response()->json(__('Leave type created successfully'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('leave_types_edit'), 403);

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
