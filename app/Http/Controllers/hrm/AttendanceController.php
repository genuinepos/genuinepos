<?php

namespace App\Http\Controllers\hrm;

use App\Models\Hrm\Shift;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\Hrm\Attendance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //attendance index page
    public function index()
    {
        // $origin = date_create('2009-10-11');
        // $target = date_create('2009-10-13');
        // $interval = date_diff($origin, $target);
        // return  $interval->format('%R%a days');

        $employee = AdminAndUser::where('status', 1)->get();
        return view('hrm.attendance.index', compact('employee'));
    }

    // Get all attendance by filter **requested by ajax**
    public function allAttendance(Request $request)
    {
        $attendances = '';
        $attendance_query = DB::table('hrm_attendances')
            ->leftJoin('admin_and_users', 'hrm_attendances.user_id', 'admin_and_users.id')
            ->leftJoin('hrm_shifts', 'hrm_attendances.shift_id', 'hrm_shifts.id');

        if ($request->user_id) {
            $attendance_query->where('hrm_attendances.user_id', $request->user_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            //$form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
            $attendance_query->whereBetween('hrm_attendances.at_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        } else {
            $attendance_query->whereDate('hrm_attendances.at_date_ts', date('Y-m-d'));
        }

        $attendances = $attendance_query->select(
            'hrm_attendances.*',
            'admin_and_users.id as user_id',
            'admin_and_users.prefix',
            'admin_and_users.name',
            'admin_and_users.last_name',
            'hrm_shifts.shift_name'
        )
            ->orderBy('hrm_attendances.id', 'desc')
            ->get();

        return view('hrm.attendance.ajax_view.attendance_list', compact('attendances'));
    }

    //attendance store method
    public function storeAttendance(Request $request)
    {
        //date('Y-m-d h:i:s');
        //return date('h:i:s', strtotime('10:12 PM'));
        //return $request->all();
        if ($request->user_ids == null) {
            return response()->json([ 'errorMsg' => 'Select employee first for attendance.']);
        }

        foreach ($request->user_ids as $key => $user_id) {
            $updateAttendance = Attendance::whereDate('hrm_attendances.at_date_ts', date('Y-m-d'))
                ->where('user_id', $user_id)
                ->where('is_completed', 0)
                ->orderBy('id', 'desc')
                ->first();
            if ($updateAttendance) {
                // $updateAttendance->user_id = $user_id;
                // $updateAttendance->at_date_ts = date('Y-m-d');
                // $updateAttendance->clock_in = $request->clock_ins[$key];
                // $updateAttendance->clock_in_ts = date('Y-m-d ') . $request->clock_ins[$key];
                $updateAttendance->clock_out = $request->clock_outs[$key];
                if ($request->clock_outs[$key]) {
                    $updateAttendance->clock_out_ts = date('Y-m-d ') . $request->clock_outs[$key];
                    $updateAttendance->is_completed = 1;
                }
                $updateAttendance->shift_id = $request->shift_ids[$key];
                $updateAttendance->clock_in_note = $request->clock_in_notes[$key];
                $updateAttendance->clock_out_note = $request->clock_out_notes[$key];
                $updateAttendance->save();
            } else {
                $data = new Attendance();
                $data->user_id = $user_id;
                $data->at_date = date('d-m-Y');
                $data->at_date_ts = date('Y-m-d');
                $data->clock_in = $request->clock_ins[$key];
                $data->clock_in_ts = date('Y-m-d ') . $request->clock_ins[$key];
                $data->clock_out = $request->clock_outs[$key];
                if ($request->clock_outs[$key]) {
                    $data->clock_out_ts = date('Y-m-d ') . $request->clock_outs[$key];
                    $data->is_completed = 1;
                }
                $data->shift_id = $request->shift_ids[$key];
                $data->clock_in_note = $request->clock_in_notes[$key];
                $data->clock_out_note = $request->clock_out_notes[$key];
                $data->month = date('F');
                $data->year = date('Y');
                $data->save();
            }
        }
        return response()->json('Successfully Attendance is Added!');
    }

    // Edit modal with data
    public function edit($attendanceId)
    {
        $attendance = DB::table('hrm_attendances')
            ->leftJoin('admin_and_users', 'hrm_attendances.user_id', 'admin_and_users.id')
            ->where('hrm_attendances.id', $attendanceId)
            ->select(
                'hrm_attendances.*',
                'admin_and_users.id as user_id',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name'
            )
            ->first();
        return view('hrm.attendance.ajax_view.edit_attendance_modal', compact('attendance'));
    }

    // Update attendance
    public function update(Request $request)
    {
        $updateAttendance = Attendance::where('id', $request->id)->first();
        if ($updateAttendance) {
            $updateAttendance->at_date_ts = date('Y-m-d ', strtotime($updateAttendance->at_date)).$request->clock_in;
            $updateAttendance->clock_in = $request->clock_in;
            $updateAttendance->clock_in_ts = date('Y-m-d ', strtotime($updateAttendance->at_date)).$request->clock_in;

            if ($request->clock_out) {
                if ($updateAttendance->clock_out) {
                    $updateAttendance->clock_out = $request->clock_out;
                    $filteredDate = explode(' ', $updateAttendance->clock_out_ts);
                    $updateAttendance->clock_out_ts = $filteredDate[0].' '.$request->clock_out;
                }else {
                    $updateAttendance->clock_out = $request->clock_out;
                    $updateAttendance->clock_out_ts = date('Y-m-d ').$request->clock_out;
                    $updateAttendance->is_completed = 1;
                }
            }

            $updateAttendance->clock_in_note = $request->clock_in_note;
            $updateAttendance->clock_out_note = $request->clock_out_note;
            $updateAttendance->save();
        }

        return response()->json('Successfully Attendances is updated!');
    }

    // Delete attendance 
    public function delete(Request $request, $attendanceId)
    {
        $deleteAttendance = Attendance::find($attendanceId);
        if (!is_null($deleteAttendance)) {
            $deleteAttendance->delete();  
        }
        return response()->json('Successfully attendance is deleted');
    }

    // Get Employee/User attendance row **requested by ajax**
    public function getUserAttendanceRow($userId)
    {
        // $startTime = Carbon::parse('2020-02-11 04:04:26');
        // $endTime = Carbon::parse('2020-02-11 04:36:56');

        // $totalDuration = $endTime->diffForHumans($startTime);
        // dd($totalDuration);

        // $startTime = Carbon::parse('2020-02-11 04:04:26');
        // $endTime = Carbon::parse('2020-02-11 04:36:56');

        // $totalDuration =  $startTime->diff($endTime)->format('%H:%I:%S')." Minutes";
        // dd($totalDuration);

        $shifts = DB::table('hrm_shifts')->get();
        $attendance = DB::table('hrm_attendances')
            ->leftJoin('admin_and_users', 'hrm_attendances.user_id', 'admin_and_users.id')
            ->whereDate('hrm_attendances.at_date_ts', date('Y-m-d'))
            ->where('hrm_attendances.user_id', $userId)
            ->where('is_completed', 0)
            ->select(
                'hrm_attendances.*',
                'admin_and_users.id as user_id',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
            )
            ->orderBy('hrm_attendances.id', 'desc')
            ->first();
        $employee = DB::table('admin_and_users')->where('id', $userId)->first();
        return view('hrm.attendance.ajax_view.attendance_row', compact('attendance', 'shifts', 'employee'));
    }
}
