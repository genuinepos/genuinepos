<?php

namespace App\Http\Controllers\HRM\Reports;

use App\Enums\BooleanType;
use App\Enums\RoleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Hrm\DepartmentService;
use Yajra\DataTables\Facades\DataTables;

class AttendanceReportController extends Controller
{
    public function __construct(
        private UserService $userService,
        private DepartmentService $departmentService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('attendance_report') || config('generalSettings')['subscription']->features['hrm'] == 0, 403);

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $month = date('m');
        $year = date('Y');
        $datesAndDays = [];
        // determine the number of days in the month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $daysInMonth; $i++) {

            array_push($datesAndDays, date("d D", mktime(0, 0, 0, $month, $i, $year)));
        }

        return view('hrm.reports.attendance_report.index', compact('branches', 'datesAndDays'));
    }

    public function listOfIndex(Request $request)
    {
        if ($request->month_year == '') {

            return response()->json(['errorMsg' => 'Month & Year is required']);
        }

        $data = [];
        $month_year = explode('-', $request->month_year);
        $dateTime = \DateTime::createFromFormat('m', $month_year[1]);

        $month = $dateTime->format('F');
        $__month = date('m', strtotime($month));
        $year = $month_year[0];
        $dates = [];
        $datesAndDays = [];
        // determine the number of days in the month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $__month, $year);
        for ($i = 1; $i <= $daysInMonth; $i++) {

            array_push($datesAndDays, date("d D", mktime(0, 0, 0, $__month, $i, $year)));
            array_push($dates, date('Y-m-d', strtotime($i . '-' . $__month . '-' . $year)));
        }

        $users = '';
        $query = $this->userService->users(with: ['branch', 'branch.parentBranch', 'attendances' => function ($query) use ($month, $year) {
            $query->where('month', $month)->where('year', $year)
                ->select('id', 'user_id', 'clock_in_ts', 'clock_in_note', 'clock_out_note');
        }, 'leaves' => function ($query) use ($__month, $year) {

            $query->whereYear('start_date', $year)
                ->whereMonth('start_date', '<=', $__month)
                ->whereYear('end_date', $year)
                ->whereMonth('end_date', '>=', $__month);
        }]);

        $this->filter(request: $request, query: $query);

        $users = $query->select('id', 'prefix', 'name', 'last_name', 'emp_id', 'branch_id')->get();

        $found = $this->attendanceFound(users: $users);

        $holidayBranches = \App\Models\Hrm\HolidayBranch::query()->with(['holiday'])
            ->leftJoin('hrm_holidays', 'hrm_holiday_branches.holiday_id', 'hrm_holidays.id')
            ->whereYear('hrm_holidays.start_date', $year)
            ->whereMonth('hrm_holidays.start_date', '<=', $__month)
            ->whereYear('hrm_holidays.end_date', $year)
            ->whereMonth('hrm_holidays.end_date', '>=', $__month)
            ->get();

        return view('hrm.reports.attendance_report.ajax_view.attendance_index_list', compact('users', 'dates', 'datesAndDays', 'found', 'holidayBranches'));
    }

    public function print(Request $request)
    {
        if ($request->month_year == '') {

            return response()->json(['errorMsg' => __('Month & Year is required')]);
        }

        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;

        $data = [];
        $month_year = explode('-', $request->month_year);
        $dateTime = \DateTime::createFromFormat('m', $month_year[1]);

        $month = $dateTime->format('F');
        $__month = date('m', strtotime($month));
        $year = $month_year[0];
        $dates = [];
        $datesAndDays = [];
        // determine the number of days in the month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $__month, $year);
        for ($i = 1; $i <= $daysInMonth; $i++) {

            array_push($datesAndDays, date("d D", mktime(0, 0, 0, $__month, $i, $year)));
            array_push($dates, date('Y-m-d', strtotime($i . '-' . $__month . '-' . $year)));
        }

        $users = '';
        $query = $this->userService->users(with: ['branch', 'branch.parentBranch', 'attendances' => function ($query) use ($month, $year) {
            $query->where('month', $month)->where('year', $year)
                ->select('id', 'user_id', 'clock_in_ts', 'clock_in_note', 'clock_out_note');
        }, 'leaves' => function ($query) use ($__month, $year) {

            $query->whereYear('start_date', $year)
                ->whereMonth('start_date', '<=', $__month)
                ->whereYear('end_date', $year)
                ->whereMonth('end_date', '>=', $__month);
        }]);

        $this->filter(request: $request, query: $query);

        $users = $query->select('id', 'prefix', 'name', 'last_name', 'emp_id', 'branch_id')->get();

        $found = $this->attendanceFound(users: $users);

        $holidayBranches = \App\Models\Hrm\HolidayBranch::query()->with(['holiday'])
            ->leftJoin('hrm_holidays', 'hrm_holiday_branches.holiday_id', 'hrm_holidays.id')
            ->whereYear('hrm_holidays.start_date', $year)
            ->whereMonth('hrm_holidays.start_date', '<=', $__month)
            ->whereYear('hrm_holidays.end_date', $year)
            ->whereMonth('hrm_holidays.end_date', '>=', $__month)
            ->get();

        return view('hrm.reports.attendance_report.ajax_view.print', compact(
            'users',
            'dates',
            'datesAndDays',
            'found',
            'holidayBranches',
            'ownOrParentBranch',
            'filteredBranchName',
            'month',
            'year'
        ));
    }

    private function filter(object $request, object $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('branch_id', null);
            } else {

                $query->where('branch_id', $request->branch_id);
            }
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('branch_id', auth()->user()->branch_id)->whereNotIn('role_type', [RoleType::SuperAdmin->value]);
        }

        return $query;
    }

    private function attendanceFound(object $users)
    {
        $found = false;
        foreach ($users as $user) {

            if ($found == false) {

                if (count($user->attendances) > 0) {

                    $found = true;
                }
            } else {

                break;
            }
        }

        return $found;
    }
}
