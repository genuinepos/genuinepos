<?php

namespace App\Services\TaskManagement;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Models\TaskManagement\Workspace;
use Yajra\DataTables\Facades\DataTables;

class WorkspaceService
{
    public function workspacesTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $workspaces = '';
        $query = DB::table('workspaces')
            ->leftJoin('branches', 'workspaces.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users', 'workspaces.created_by_id', 'users.id');

        $this->filter(request: $request, query: $query);

        $workspaces = $query->select(
            'workspaces.*',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'users.prefix as created_by_prefix',
            'users.name as created_by__name',
            'users.last_name as created_by_last_name',
        )->where('workspaces.branch_id', auth()->user()->branch_id)->orderBy('id', 'desc');

        return DataTables::of($workspaces)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="#" class="dropdown-item" id="detailsBtn">' . __("View") . '</a>';

                if (auth()->user()->branch_id == $row->branch_id && auth()->user()->can('workspaces_manage_task')) {

                    $html .= '<a href="' . route('workspaces.task.index', [$row->id]) . '" class="dropdown-item">' . __("Manage Tasks") . '</a>';
                }

                if (auth()->user()->branch_id == $row->branch_id && auth()->user()->can('workspaces_edit')) {

                    $html .= '<a href="' . route('workspaces.edit', [$row->id]) . '" class="dropdown-item" id="edit">' . __("Edit") . '</a>';
                }

                if (auth()->user()->branch_id == $row->branch_id && auth()->user()->can('workspaces_delete')) {

                    $html .= '<a href="' . route('workspaces.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __("Delete") . '</a>';
                }
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = $generalSettings['business_or_shop__date_format'];
                return date($__date_format, strtotime($row->created_at));
            })
            ->editColumn('name', function ($row) {

                return $row->name . ' <a href="' . route('workspaces.attachments.index', [$row->id]) . '" class="btn btn-sm btn-info text-white" id="attachments">' . __("Docs") . '</a>';
            })
            ->editColumn('from', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->branch_area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'] . '(' . __('Business') . ')';
                }
            })
            ->editColumn('start_date', function ($row) use ($generalSettings) {

                $__date_format = $generalSettings['business_or_shop__date_format'];
                return date($__date_format, strtotime($row->start_date));
            })
            ->editColumn('end_date', function ($row) use ($generalSettings) {

                $__date_format = $generalSettings['business_or_shop__date_format'];
                return date($__date_format, strtotime($row->end_date));
            })
            ->editColumn('assigned_by', function ($row) {

                return $row->created_by_prefix . ' ' . $row->created_by__name . ' ' . $row->created_by_last_name;
            })
            ->rawColumns(['action', 'date', 'start_date', 'end_date', 'from', 'name', 'assigned_by'])
            ->make(true);
    }

    function addWorkspace(object $request, ?object $branch, object $codeGenerator): object
    {
        $workspacePrefix = null;
        if (isset($branch)) {

            $numberOfChildBranch = $branch?->parentBranch && count($branch?->parentBranch?->childBranches) > 0 ? count($branch->parentBranch->childBranches) : '';

            $branchName = $branch?->parentBranch ? $branch?->parentBranch?->name : $branch->name;

            $exp = explode(' ', $branchName);

            foreach ($exp as $ex) {
                $str = str_split($ex);
                $workspacePrefix .= $str[0];
            }

            $workspacePrefix .= $numberOfChildBranch;
        }

        $__workspacePrefix = isset($workspacePrefix) ? $workspacePrefix : 'PM';
        $workspaceNo = $codeGenerator->generateMonthWise(table: 'workspaces', column: 'workspace_no', prefix: $__workspacePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addWorkspace = new Workspace();
        $addWorkspace->workspace_no = $workspaceNo;
        $addWorkspace->branch_id = auth()->user()->branch_id;
        $addWorkspace->name = $request->name;
        $addWorkspace->priority = $request->priority;
        $addWorkspace->status = $request->status;
        $addWorkspace->start_date = date('Y-m-d', strtotime($request->start_date));
        $addWorkspace->end_date = date('Y-m-d', strtotime($request->end_date));
        $addWorkspace->description = $request->description;
        $addWorkspace->estimated_hours = $request->estimated_hours;
        $addWorkspace->created_by_id = auth()->user()->id;
        $addWorkspace->created_at = Carbon::now();
        $addWorkspace->save();

        return $addWorkspace;
    }

    public function updateWorkspace(object $request, int $id): object
    {
        $updateWorkspace = $this->singleWorkspace(id: $id, with: ['users']);

        foreach ($updateWorkspace->users as $user) {

            $user->is_delete_in_update = BooleanType::True->value;
            $user->save();
        }

        $updateWorkspace->name = $request->name;
        $updateWorkspace->priority = $request->priority;
        $updateWorkspace->status = $request->status;
        $updateWorkspace->start_date = date('Y-m-d', strtotime($request->start_date));
        $updateWorkspace->end_date = date('Y-m-d', strtotime($request->end_date));
        $updateWorkspace->description = $request->description;
        $updateWorkspace->estimated_hours = $request->estimated_hours;
        $updateWorkspace->save();

        return $updateWorkspace;
    }

    public function deleteWorkspace(int $id): void
    {
        $deleteWorkspace = $this->singleWorkspace(id: $id, with: ['attachments']);

        if (isset($deleteWorkspace)) {

            foreach ($deleteWorkspace->attachments as $attachment) {

                if (isset($attachment->attachment) && file_exists(public_path('uploads/workspace_attachments/' . $attachment->attachment))) {

                    unlink(public_path('uploads/workspace_attachments/' . $attachment->attachment));
                }
            }

            $deleteWorkspace->delete();
        }
    }

    public function singleWorkspace(int $id, array $with = null)
    {
        $query = Workspace::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('workspaces.branch_id', null);
            } else {

                $query->where('workspaces.branch_id', $request->branch_id);
            }
        }

        if ($request->priority) {

            $query->where('workspaces.priority', $request->priority);
        }

        if ($request->status) {

            $query->where('workspaces.status', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('workspaces.created_at', $date_range);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('workspaces.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
