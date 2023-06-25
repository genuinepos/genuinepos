<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Hrm\Attendance;
use App\Models\Hrm\Department;
use App\Models\Hrm\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployee = User::all()->count();
        $totalDepartment = Department::all()->count();
        $todayAttendance =  Attendance::whereDate('created_at', today())->count();
        $todayLeave = leave::where('start_date', date('d-m-Y'))->count();
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('hrm.dashboard.hrm_dashboard', compact('branches', 'totalEmployee', 'totalDepartment', 'todayAttendance', 'todayLeave'));
    }

    public function userCountTable(Request $request)
    {
        $userCount = '';
        $users = '';
        $userCountQ = DB::table('users');
        $usersQ = DB::table('hrm_department')
            ->leftJoin('users', 'hrm_department.id', 'users.department_id')
            ->select(
                DB::raw('COUNT(users.id) as total_users'),
                'hrm_department.department_name'
            );

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $userCountQ->where('users.branch_id', NULL);
                $usersQ->where('users.branch_id', NULL);
            } else {
                $userCountQ->where('users.branch_id', $request->branch_id);
                $usersQ->where('users.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $userCount = $userCountQ->count();
            $users = $usersQ->groupBy('users.department_id')
                ->groupBy('department_name')
                ->get();
        } else {
            $userCount = $userCountQ->where('users.branch_id', auth()->user()->branch_id)->count();
            $users = $usersQ->groupBy('users.department_id')
                ->groupBy('department_name')
                ->where('users.branch_id', auth()->user()->branch_id)
                ->get();
        }
        return view('hrm.dashboard.ajax_view.user_count_table', compact('userCount', 'users'));
    }

    public function todayAttTable(Request $request)
    {
        $todayAttendances = '';
        $todayAttQ = DB::table('hrm_attendances')
            ->leftJoin('users', 'hrm_attendances.user_id', 'users.id')
            ->whereDate('hrm_attendances.at_date_ts', Carbon::today());

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $todayAttQ->where('users.branch_id', NULL);
            } else {
                $todayAttQ->where('users.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $todayAttendances = $todayAttQ->select(
                'users.prefix',
                'users.name',
                'users.last_name',
                'hrm_attendances.clock_in',
                'hrm_attendances.clock_out',
            )->get();
        } else {
            $todayAttendances = $todayAttQ->select(
                'users.prefix',
                'users.name',
                'users.last_name',
                'hrm_attendances.clock_in',
                'hrm_attendances.clock_out',
            )->get();
        }

        return view('hrm.dashboard.ajax_view.today_attendance_table', compact('todayAttendances'));
    }

    public function leaveTable(Request $request)
    {
        $leaves = '';
        $leaveQuery = DB::table('hrm_leaves')->leftJoin('users', 'hrm_leaves.employee_id', 'users.id');

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $leaveQuery->where('users.branch_id', NULL);
            } else {
                $leaveQuery->where('users.branch_id', $request->branch_id);
            }
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $leaves = $leaveQuery->select(
                'users.prefix',
                'users.name',
                'users.last_name',
                'hrm_leaves.start_date',
                'hrm_leaves.end_date',
                'hrm_leaves.status',
            )->get();
        } else {
            $leaves = $leaveQuery->select(
                'users.prefix',
                'users.name',
                'users.last_name',
                'hrm_leaves.start_date',
                'hrm_leaves.end_date',
                'hrm_leaves.status',
            )->get();
        }

        return view('hrm.dashboard.ajax_view.leave_table', compact('leaves'));
    }

    public function upcomingHolidays(Request $request)
    {
        $holidays = '';
        $holidaysQuery = DB::table('hrm_holidays')->leftJoin('branches', 'hrm_holidays.branch_id', 'branches.id');

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $holidays = $holidaysQuery->select(
                'hrm_holidays.holiday_name',
                'hrm_holidays.start_date',
                'hrm_holidays.end_date',
            )->whereDate('start_date', '>', date('Y-m-d'))->get();
        } else {
            $holidays = $holidaysQuery->select(
                'hrm_holidays.holiday_name',
                'hrm_holidays.start_date',
                'hrm_holidays.end_date',
            )->whereDate('start_date', '>', date('Y-m-d'))->get();
        }

        return view('hrm.dashboard.ajax_view.upcomingHolidays', compact('holidays'));
    }
}
