<?php

namespace App\Services\Hrm;

use App\Models\Hrm\LeaveType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LeaveTypeService
{
    public function leaveTypesTable(): object
    {
        $leaveTypes = DB::table('hrm_leave_types')->orderBy('id', 'desc')->get();
        return DataTables::of($leaveTypes)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('leave_types_edit')) {

                    $html .= '<a href="' . route('hrm.leave.type.edit', [$row->id]) . '" class="action-btn c-edit" id="editLeaveType" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('leave_types_delete')) {

                    $html .= '<a href="' . route('hrm.leave.type.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteLeaveType" title="Delete"><span class="fas fa-trash"></span></a>';
                }

                $html .= '</div>';

                return $html;
            })
            ->editColumn('leave_count_interval', function ($row) {

                if ($row->leave_count_interval == 1) {
                    return '<span class="badge bg-primary">' . __('Current Month') . '</span>';
                } elseif ($row->leave_count_interval == 2) {
                    return '<span class="badge bg-warning">' . __('Current Financial Year') . '</span>';
                } else {
                    return '<span class="badge bg-info">' . __('None') . '</span>';
                }
            })->rawColumns(['leave_count_interval', 'action'])->smart(true)->make(true);
    }

    public function addLeaveType(object $request): void
    {
        $addLeaveType = new LeaveType();
        $addLeaveType->name = $request->name;
        $addLeaveType->max_leave_count = $request->max_leave_count;
        $addLeaveType->leave_count_interval = $request->leave_count_interval;
        $addLeaveType->save();
    }

    public function updateLeaveType(object $request, int $id): void
    {
        $updateLeaveType = $this->singleLeaveType(id: $id);
        $updateLeaveType->name = $request->name;
        $updateLeaveType->max_leave_count = $request->max_leave_count;
        $updateLeaveType->leave_count_interval = $request->leave_count_interval;
        $updateLeaveType->save();
    }

    function deleteLeaveType(int $id): void
    {
        $deleteLeaveType = $this->singleLeaveType(id: $id);
        if (!is_null($deleteLeaveType)) {

            $deleteLeaveType->delete();
        }
    }

    public function singleLeaveType(int $id, array $with = null)
    {
        $query = LeaveType::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
