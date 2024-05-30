<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Services\Hrm\LeaveTypeService;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function __construct(private LeaveTypeService $leaveTypeService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('leave_types_index') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        if ($request->ajax()) {

            return $this->leaveTypeService->leaveTypesTable();
        }
    }

    public function create()
    {
        abort_if(!auth()->user()->can('leave_types_create') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        return view('hrm.leaves.ajax_view.types.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('leave_types_create') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->leaveTypeService->storeAndUpdateValidation(request: $request);
        $this->leaveTypeService->addLeaveType(request: $request);

        return response()->json(__('Leave type created successfully'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('leave_types_edit') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $leaveType = $this->leaveTypeService->singleLeaveType(id: $id);

        return view('hrm.leaves.ajax_view.types.edit', compact('leaveType'));
    }

    public function update($id, Request $request)
    {
        abort_if(!auth()->user()->can('leave_types_edit') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->leaveTypeService->storeAndUpdateValidation(request: $request);
        $this->leaveTypeService->updateLeaveType(request: $request, id: $id);

        return response()->json(__('Leave type updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('leave_types_delete') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->leaveTypeService->deleteLeaveType(id: $id);

        return response()->json(__('Leave type deleted successfully'));
    }
}
