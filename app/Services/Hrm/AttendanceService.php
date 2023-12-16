<?php

namespace App\Services\Hrm;

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use Carbon\CarbonInterval;
use App\Models\Hrm\Attendance;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AttendanceService
{
    public function attendancesTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $attendances = '';
        $query = DB::table('hrm_attendances')
            ->leftJoin('users', 'hrm_attendances.user_id', 'users.id')
            ->leftJoin('hrm_shifts', 'hrm_attendances.shift_id', 'hrm_shifts.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('users.branch_id', null);
            } else {

                $query->where('users.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('hrm_attendances.user_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('hrm_attendances.at_date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('hrm_attendances.branch_id', auth()->user()->branch_id);
        }

        $attendances = $query->select(
            'hrm_attendances.*',
            'hrm_shifts.name as shift_name',
            'users.prefix',
            'users.name',
            'users.last_name',
            'users.emp_id',
        )->orderBy('hrm_attendances.at_date_ts', 'desc');

        return DataTables::of($attendances)
            ->addColumn('action', function ($row) {

                $html = '';
                $html .= '<div class="dropdown table-dropdown">';
                $html .= '<a href="' . route('hrm.attendances.edit', [$row->id]) . '" class="btn btn-sm btn-primary me-1" id="edit" title="Edit"><i class="la la-edit"></i> ' . __("Edit") . '</a>';

                $html .= '<a href="' . route('hrm.attendances.delete', [$row->id]) . '" class="btn btn-sm btn-danger" id="delete"><i class="la la-trash"></i> ' . __("Delete") . '</a>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('name', function ($row) {

                return $row->prefix . ' ' . $row->name . ' ' . $row->last_name;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                return date($generalSettings['business__date_format'], strtotime($row->at_date_ts));
            })
            ->editColumn('clock_in_out', function ($row) use ($generalSettings) {

                $dateFormat = $generalSettings['business__date_format'];
                $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';

                $clockOut = $row->clock_out_ts ? ' - ' . date($dateFormat . ' ' . $timeFormat, strtotime($row->clock_out_ts)) : '';

                return ' <b>' . date($dateFormat . ' ' . $timeFormat, strtotime($row->clock_in_ts)) . $clockOut . ' </b>';
            })
            ->editColumn('work_duration', function ($row) {

                if ($row->clock_out_ts) {

                    $startTime = Carbon::parse($row->clock_in_ts);
                    $endTime = Carbon::parse($row->clock_out_ts);
                    // $totalDuration = $startTime->diffForHumans($endTime);
                    // $totalDuration = $endTime->diff($startTime)->format('%H:%I:%S');

                    $totalDuration = $startTime->DiffInSeconds($endTime);

                    return CarbonInterval::seconds($totalDuration)->cascade()->forHumans();
                } else {

                    return __('Clock-Out-does-not-exists');
                }
            })
            ->rawColumns(['action', 'date', 'clock_in_out', 'work_duration'])->make(true);
    }

    public function addAttendances(object $request): void
    {
        $generalSettings = config('generalSettings');
        $dateFormat = $generalSettings['business__date_format'];

        foreach ($request->user_ids as $key => $user_id) {

            $addOrUpdateAttendance = '';
            $attendance = Attendance::whereDate('hrm_attendances.at_date_ts', date('Y-m-d'))
                ->where('user_id', $user_id)
                ->where('is_completed', BooleanType::False->value)
                ->first();

            if ($attendance) {

                $addOrUpdateAttendance = $attendance;
            } else {

                $addOrUpdateAttendance = new Attendance();
            }

            $addOrUpdateAttendance->user_id = $user_id;
            $addOrUpdateAttendance->clock_in_date = date($dateFormat);
            $addOrUpdateAttendance->at_date_ts = date('Y-m-d H:i:s');
            $addOrUpdateAttendance->clock_in = $request->clock_ins[$key];
            $addOrUpdateAttendance->clock_in_ts = date('Y-m-d ') . date('H:i:s', strtotime($request->clock_ins[$key]));

            if (isset($request->clock_outs[$key])) {

                $addOrUpdateAttendance->clock_out_date = $request->clock_out_dates[$key];
                $addOrUpdateAttendance->clock_out = $request->clock_outs[$key];
                $addOrUpdateAttendance->clock_out_ts = date('Y-m-d H:i:s', strtotime($request->clock_out_dates[$key] . ' ' . $request->clock_outs[$key]));
                $addOrUpdateAttendance->is_completed = BooleanType::True->value;
            }

            $addOrUpdateAttendance->clock_in_note = $request->clock_in_notes[$key];
            $addOrUpdateAttendance->clock_out_note = $request->clock_out_notes[$key];
            $addOrUpdateAttendance->month = date('F');
            $addOrUpdateAttendance->year = date('Y');
            $addOrUpdateAttendance->shift_id = $request->shift_ids[$key];
            $addOrUpdateAttendance->save();
        }
    }

    function updateAttendance(object $request, int $id): void
    {
        $updateAttendance = Attendance::where('id', $id)->first();

        $updateAttendance->clock_in_date = $request->clock_in_date;
        $time = date(' H:i:s', strtotime($updateAttendance->at_date_ts));
        $updateAttendance->at_date_ts = date('Y-m-d H:i:s', strtotime($request->clock_in_date . $time));
        $updateAttendance->clock_in = $request->clock_in;
        $updateAttendance->clock_in_ts = date('Y-m-d H:i:s', strtotime($request->clock_in_date . '' . $request->clock_in));

        if (isset($request->clock_out)) {

            $updateAttendance->clock_out_date = $request->clock_out_date;
            $updateAttendance->clock_out = $request->clock_out;
            $updateAttendance->clock_out_ts = date('Y-m-d H:i:s', strtotime($request->clock_out_date . ' ' . $request->clock_out));
            $updateAttendance->is_completed = BooleanType::True->value;
        }

        $updateAttendance->clock_in_note = $request->clock_in_note;
        $updateAttendance->clock_out_note = $request->clock_out_note;
        $updateAttendance->shift_id = $request->shift_id;
        $updateAttendance->save();
    }
}
