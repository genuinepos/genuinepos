<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Hrm\LeaveType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LeaveTypeController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('leave_type')) {

            abort(403, 'Access Forbidden.');
        }
        if ($request->ajax()) {

            $leaveTypes = LeaveType::orderBy('id', 'DESC')->get();

            return DataTables::of($leaveTypes)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('hrm.leave.type.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('hrm.leave.type.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('leave_count_interval', function ($row) {

                    if ($row->leave_count_interval == 1) {
                        return '<span class="badge bg-primary">'.__('Current Month').'</span>';
                    } elseif ($row->leave_count_interval == 2) {
                        return '<span class="badge bg-warning">'.__('Current Financial Year').'</span>';
                    } else {
                        return '<span class="badge bg-info">'.__('none').'</span>';
                    }
                })->rawColumns(['leave_count_interval', 'action'])->smart(true)->make(true);
        }

        return view('hrm.leave_types.index');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('leave_type')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'leave_type' => 'required',
        ]);

        LeaveType::insert([
            'branch_id' => auth()->user()->branch_id,
            'leave_type' => $request->leave_type,
            'max_leave_count' => $request->max_leave_count,
            'leave_count_interval' => $request->leave_count_interval,
        ]);

        return response()->json('Leave type created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('leave_type')) {

            abort(403, 'Access Forbidden.');
        }

        $leaveType = LeaveType::where('id', $id)->first();

        return view('hrm.leave_types.ajax_view.edit', compact('leaveType'));
    }

    public function update(Request $request)
    {
        if (! auth()->user()->can('leave_type')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'leave_type' => 'required',
        ]);

        $updateLeaveType = LeaveType::where('id', $request->id)
            ->update([
                'leave_type' => $request->leave_type,
                'max_leave_count' => $request->max_leave_count,
                'leave_count_interval' => $request->leave_count_interval,
            ]);

        return response()->json('Leave type updated successfully');
    }

    public function delete(Request $request, $id)
    {
        if (! auth()->user()->can('leave_type')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteCategory = LeaveType::find($id);
        $deleteCategory->delete();

        return response()->json('Leave type deleted successfully');
    }
}
