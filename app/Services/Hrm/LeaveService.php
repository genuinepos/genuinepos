<?php

namespace App\Services\Hrm;

use App\Models\Hrm\Leave;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LeaveService
{
    public function leavesTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $leaves = '';
        $query = DB::table('hrm_leaves')
            ->leftJoin('hrm_leave_types', 'hrm_leaves.leave_type_id', 'hrm_leave_types.id')
            ->leftJoin('branches', 'hrm_leaves.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users', 'hrm_leaves.user_id', 'users.id');

        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('hrm_leaves.branch_id', null);
            } else {

                $query->where('hrm_leaves.branch_id', $request->branch_id);
            }
        }

        $leaves = $query->select(
            'hrm_leaves.*',
            'hrm_leave_types.name as leave_type',
            'users.name as user_name',
            'users.prefix as user_prefix',
            'users.last_name as user_last_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('id', 'desc')->get();

        return DataTables::of($leaves)
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('leaves_delete')) {

                        $html .= '<a href="' . route('hrm.leaves.edit', [$row->id]) . '" class="action-btn c-edit" id="editLeave" title="Edit"><span class="fas fa-edit"></span></a>';
                    }

                    if (auth()->user()->can('leaves_delete')) {

                        $html .= '<a href="' . route('hrm.leaves.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteLeave" title="Delete"><span class="fas fa-trash "></span></a>';
                    }
                }

                $html .= '</div>';

                return $html;
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('user', function ($row) {

                return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name;
            })
            ->editColumn('status', function ($row) {

                if ($row->status == 0) {

                    return '<span class="badge bg-danger">' . __('Pending') . '</span>';
                } else {

                    return '<span class="badge bg-success">' . __('Success') . '</span>';
                }
            })
            ->rawColumns(['user', 'status', 'action'])->smart(true)->make(true);
    }

    public function addLeave(object $request, object $codeGenerator): void
    {
        $leaveNo = $codeGenerator->generateWithoutYearMonth(table: 'hrm_leaves', column: 'leave_no', prefix: 'LV', digits: 4, branchId: auth()->user()->branch_id);

        $addLeave = new Leave();
        $addLeave->branch_id = auth()->user()->branch_id;
        $addLeave->leave_no = $leaveNo;
        $addLeave->user_id = $request->user_id;
        $addLeave->leave_type_id = $request->leave_type_id;
        $addLeave->start_date = date('Y-m-d', strtotime($request->start_date));
        $addLeave->end_date = date('Y-m-d', strtotime($request->end_date));;
        $addLeave->reason = $request->reason;
        $addLeave->status = 0;
        $addLeave->created_by_id = auth()->user()->id;
        $addLeave->save();
    }

    public function updateLeave(object $request, int $id): void
    {
        $updateLeave = $this->singleLeave(id: $id);
        $updateLeave->user_id = $request->user_id;
        $updateLeave->leave_type_id = $request->leave_type_id;
        $updateLeave->start_date = date('Y-m-d', strtotime($request->start_date));
        $updateLeave
            ->end_date = date('Y-m-d', strtotime($request->end_date));;
        $updateLeave->reason = $request->reason;
        $updateLeave->save();
    }

    public function deleteLeave(int $id): void
    {
        $deleteLeave = $this->singleLeave(id: $id);
        if (!is_null($deleteLeave)) {

            $deleteLeave->delete();
        }
    }

    public function singleLeave(int $id, array $with = null)
    {
        $query = Leave::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
