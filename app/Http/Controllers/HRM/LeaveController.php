<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Hrm\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function __construct(private LeaveService $leaveService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('leaves_index') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        if ($request->ajax()) {

            return $this->leaveService->leavesTable($request);
        }

        return view('hrm.leaves.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('leaves_create') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $leaveTypes = DB::table('hrm_leave_types')->get(['id', 'name']);
        $users = DB::table('users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);

        return view('hrm.leaves.ajax_view.leaves.create', compact('leaveTypes', 'users'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerator)
    {
        abort_if(!auth()->user()->can('leaves_create') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->leaveService->storeAndUpdateValidation(request: $request);
        $this->leaveService->addLeave(request: $request, codeGenerator: $codeGenerator);

        return response()->json(__('Leave created successfully'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('leaves_edit') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $leaveTypes = DB::table('hrm_leave_types')->get(['id', 'name']);
        $users = DB::table('users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);
        $leave = DB::table('hrm_leaves')->where('id', $id)->first();

        return view('hrm.leaves.ajax_view.leaves.edit', compact('leave', 'leaveTypes', 'users'));
    }

    public function update($id, Request $request)
    {
        abort_if(!auth()->user()->can('leaves_edit') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->leaveService->storeAndUpdateValidation(request: $request);
        $this->leaveService->updateLeave(request: $request, id: $id);

        return response()->json(__('Leave Updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('leaves_delete') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $this->leaveService->deleteLeave(id: $id);
        return response()->json(__('Leave Deleted successfully'));
    }
}
