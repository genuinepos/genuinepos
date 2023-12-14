<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Hrm\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function __construct(
        private LeaveService $leaveService,
    ) {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->leaveService->leavesTable($request);
        }

        return view('hrm.leaves.index');
    }

    public function create()
    {
        $leaveTypes = DB::table('hrm_leave_types')->get(['id', 'name']);
        $users = DB::table('users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);

        return view('hrm.leaves.ajax_view.leaves.create', compact('leaveTypes', 'users'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $codeGenerator)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'leave_type_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $addLeave = $this->leaveService->addLeave(request: $request, codeGenerator: $codeGenerator);
        return response()->json(__('Leave created successfully'));
    }

    public function edit($id)
    {
        $leaveTypes = DB::table('hrm_leave_types')->get(['id', 'name']);
        $users = DB::table('users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);
        $leave = DB::table('hrm_leaves')->where('id', $id)->first();

        return view('hrm.leaves.ajax_view.leaves.edit', compact('leave', 'leaveTypes', 'users'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'leave_type_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $this->leaveService->updateLeave(request: $request, id: $id);
        return response()->json(__('Leave Updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        $this->leaveService->deleteLeave(id: $id);
        return response()->json(__('Leave Deleted successfully'));
    }
}
