<?php

namespace App\Http\Controllers\HRM;

use App\Enums\BooleanType;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Hrm\Attendance;
use App\Services\Hrm\ShiftService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Hrm\AttendanceService;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    public function __construct(
        private AttendanceService $attendanceService,
        private ShiftService $shiftService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->attendanceService->attendancesTable(request: $request);
        }

        $users = DB::table('users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('hrm.attendances.index', compact('users', 'branches'));
    }

    public function create()
    {
        $departments = DB::table('hrm_departments')->get(['id', 'name']);
        $users = DB::table('users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'emp_id']);

        return view('hrm.attendances.ajax_view.create', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        if ($request->user_ids == null) {

            return response()->json(['errorMsg' => __('Select employee first for attendance.')]);
        }

        try {
            DB::beginTransaction();

            $this->attendanceService->addAttendances(request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Attendance Added Successfully!'));
    }

    public function edit($id)
    {
        $attendance = DB::table('hrm_attendances')
            ->leftJoin('users', 'hrm_attendances.user_id', 'users.id')
            ->where('hrm_attendances.id', $id)
            ->select(
                'hrm_attendances.*',
                'users.id as user_id',
                'users.prefix',
                'users.name',
                'users.last_name'
            )->first();

        $shifts = $this->shiftService->shifts()->get();

        return view('hrm.attendances.ajax_view.edit', compact('attendance', 'shifts'));
    }

    public function update($id, Request $request)
    {
        $this->attendanceService->validation(request: $request);
        $this->attendanceService->updateAttendance(request: $request, id: $id);
        return response()->json(__('Attendances updated successfully!'));
    }

    public function delete($id, Request $request)
    {
        $this->attendanceService->deleteAttendance(id: $id);
        return response()->json(__('Attendance deleted successfully'));
    }

    public function userAttendanceRow($userId)
    {
        $attendance = DB::table('hrm_attendances')
            ->leftJoin('users', 'hrm_attendances.user_id', 'users.id')
            ->whereDate('hrm_attendances.at_date_ts', date('Y-m-d'))
            ->where('hrm_attendances.user_id', $userId)
            ->where('is_completed', BooleanType::False->value)
            ->select(
                'hrm_attendances.*',
                'users.id as user_id',
                'users.prefix',
                'users.name',
                'users.last_name',
                'users.emp_id',
            )->orderBy('hrm_attendances.id', 'desc')->first();

        $shifts = $this->shiftService->shifts()->get();
        $user = DB::table('users')->where('id', $userId)->first();

        return view('hrm.attendances.ajax_view.attendance_row', compact('attendance', 'user', 'shifts'));
    }
}
