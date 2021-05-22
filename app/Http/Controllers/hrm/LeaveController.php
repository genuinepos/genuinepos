<?php

namespace App\Http\Controllers\hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminAndUser;
use App\Models\Hrm\Leavetype;
use App\Models\Hrm\Leave;
use Illuminate\Support\Facades\Cache;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //leave page method
    public function index()
    {
        $employee = AdminAndUser::where('status', 1)->get();
        $leavetype = Leavetype::all();
        return view('hrm.leave.index', compact('employee', 'leavetype'));
    }

    //all leave data for ajax
    public function allLeave()
    {
        $leave = Leave::orderBy('id', 'DESC')->get();
        return view('hrm.leave.ajax.list', compact('leave'));
    }

    //store leave
    public function storeLeave(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        Leave::insert([
            'reference_number' => hexdec(substr(uniqid(), -5)),
            'employee_id' => $request->employee_id,
            'leave_id' => $request->leave_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 0,
        ]);
        return response()->json('Successfully Leave Added!');
    }

    //get leave type
    public function GetLeaveType()
    {
        $admins = Leavetype::all();
        return response()->json($admins);
    }

    //update leave
    public function updateLeave(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $leave = Leave::where('id', $request->id)->first();
        $leave->update([
            'employee_id' => $request->employee_id,
            'leave_id' => $request->leave_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);
        return response()->json('Successfully Leave Updated!');
    }

    //destroy leave
    public function deleteLeave(Request $request, $id)
    {
        $Leave = Leave::find($id);
        $Leave->delete();
        return response()->json('Successfully leave Deleted');
    }
}
