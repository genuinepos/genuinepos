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
        if (!auth()->user()->can('leave_types_index')) {

            abort(403, __('Access Forbidden.'));
        }

        if ($request->ajax()) {

            return $this->leaveTypeService->leaveTypesTable();
        }
    }

    public function create()
    {
        if (!auth()->user()->can('leave_types_create')) {

            abort(403, __('Access Forbidden.'));
        }

        return view('hrm.leaves.ajax_view.types.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('leave_types_create')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->leaveTypeService->storeAndUpdateValidation(request: $request);
        $this->leaveTypeService->addLeaveType(request: $request);

        return response()->json(__('Leave type created successfully'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('leave_types_edit')) {

            abort(403, __('Access Forbidden.'));
        }

        $leaveType = $this->leaveTypeService->singleLeaveType(id: $id);

        return view('hrm.leaves.ajax_view.types.edit', compact('leaveType'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('leave_types_edit')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->leaveTypeService->storeAndUpdateValidation(request: $request);
        $this->leaveTypeService->updateLeaveType(request: $request, id: $id);

        return response()->json(__('Leave type updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('leave_types_delete')) {

            abort(403, __('Access Forbidden.'));
        }

        $this->leaveTypeService->deleteLeaveType(id: $id);

        return response()->json(__('Leave type deleted successfully'));
    }
}
