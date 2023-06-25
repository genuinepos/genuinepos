<?php

namespace App\Http\Controllers\HRM;

use App\Models\Hrm\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Utils\InvoiceVoucherRefIdUtil;
use Yajra\DataTables\Facades\DataTables;

class LeaveController extends Controller
{
    protected $invoiceVoucherRefIdUtil;

    public function __construct(InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $leaves = '';
            $query = DB::table('hrm_leaves')
                ->leftJoin('hrm_leavetypes', 'hrm_leaves.leave_type_id', 'hrm_leavetypes.id')
                ->leftJoin('users', 'hrm_leaves.employee_id', 'users.id');

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                $query;
            } else {

                $query->where('branch_id', auth()->user()->branch_id);
            }

            $leaves = $query->select(
                'hrm_leaves.*',
                'hrm_leavetypes.leave_type',
                'users.name as e_name',
                'users.prefix as e_prefix',
                'users.last_name as e_last_name'
            )->orderBy('id', 'desc');

            return DataTables::of($leaves)
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('hrm.leaves.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('hrm.leaves.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('employee', function ($row) {

                    return $row->e_prefix . ' ' . $row->e_name . ' ' . $row->e_last_name;
                })
                ->editColumn('status', function ($row) {

                    if ($row->status == 0) :

                        return '<span class="badge bg-warning">' . __('pending') . '</span>';
                    else :

                        return '<span class="badge bg-success">' . __('success') . '</span>';
                    endif;
                })
                ->rawColumns(['employee', 'status', 'action'])->smart(true)->make(true);
        }

        $departments = DB::table('hrm_department')->get(['id', 'department_name']);
        $leaveTypes = DB::table('hrm_leavetypes')->get(['id', 'leave_type']);
        $employees = DB::table('users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);

        return view('hrm.leaves.index', compact('departments', 'leaveTypes', 'employees'));
    }

    public function store(Request $request)
    {
       
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_type_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        Leave::insert([
            'branch_id' => auth()->user()->branch_id,
            'leave_no' => str_pad($this->invoiceVoucherRefIdUtil->getLastId('hrm_leaves'), 4, "0", STR_PAD_LEFT),
            'employee_id' => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 0,
        ]);

        return response()->json('Leave created successfully');
    }

    public function edit($id)
    {
        $departments = DB::table('hrm_department')->get(['id', 'department_name']);
        $leaveTypes = DB::table('hrm_leavetypes')->get(['id', 'leave_type']);
        $employees = DB::table('users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name', 'department_id']);
        $leave = DB::table('hrm_leaves')->where('id', $id)->first();

        return view('hrm.leaves.ajax_view.edit', compact('leave', 'departments', 'leaveTypes', 'employees'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_type_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $leave = Leave::where('id', $request->id)->update([
            'employee_id' => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return response()->json('Leave Updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $Leave = Leave::find($id);
        $Leave->delete();
        DB::statement('ALTER TABLE hrm_leaves AUTO_INCREMENT = 1');
        return response()->json('Leave Deleted successfully');
    }

    public function departmentEmployees($depId)
    {
        $employees = '';
        $query = DB::table('users');

        if ($depId != 'all') {

            $query->where('department_id', $depId);
        }

        $employees = $query->where('branch_id', auth()->user()->branch_id)->orderBy('name', 'asc')->get(['id', 'prefix', 'name', 'last_name']);

        return response()->json($employees);
    }
}
