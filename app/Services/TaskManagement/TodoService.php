<?php

namespace App\Services\TaskManagement;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Models\TaskManagement\Todo;
use Yajra\DataTables\Facades\DataTables;

class TodoService
{
    public function todoTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $todo = '';
        $query = DB::table('todos')
            ->leftJoin('branches', 'todos.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('users', 'todos.created_by_id', 'users.id');

        $this->filter(request: $request, query: $query);

        $todo = $query->select(
            'todos.*',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'users.prefix as created_by_prefix',
            'users.name as created_by_name',
            'users.last_name as created_by_last_name',
        )->orderBy('todos.id', 'desc');

        return DataTables::of($todo)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' . __("Action") . ' </button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="show" href="' . route('todo.show', [$row->id]) . '">' . __("View") . '</a>';

                if ($row->branch_id == auth()->user()->branch_id && auth()->user()->can('todo_edit')) {

                    $html .= '<a class="dropdown-item" id="edit" href="' . route('todo.edit', [$row->id]) . '">' . __("Edit") . '</a>';
                }

                if (auth()->user()->can('todo_change_status')) {

                    $html .= '<a class="dropdown-item" id="changeStatus" href="' . route('todo.change.status.modal', [$row->id]) . '">' . __("Change Status") . '</a>';
                }

                if ($row->branch_id == auth()->user()->branch_id && auth()->user()->can('todo_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="' . route('todo.delete', [$row->id]) . '">' . __("Delete") . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('due_date', function ($row) use ($generalSettings) {

                $__date_format = $generalSettings['business_or_shop__date_format'];
                return date($__date_format, strtotime($row->due_date));
            })
            ->editColumn('priority', function ($row) {
                if ($row->priority == 'High') {

                    return '<span class="badge bg-danger">' . __("High") . '</span>';
                } elseif ($row->priority == 'Low') {

                    return '<span class="badge bg-warning">' . __("Low") . '</span>';
                } elseif ($row->priority == 'Medium') {

                    return '<span class="badge bg-secondary">' . __("Medium") . '</span>';
                } else {

                    return '<span class="badge bg-1">' . __("Urgent") . '</span>';
                }
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'New') {

                    return '<span class="badge bg-primary">' . __("New") . '</span>';
                } elseif ($row->status == 'In-Progress') {

                    return '<span class="badge bg-secondary">' .  __("In-Progress") . '</span>';
                } elseif ($row->status == 'On-Hold') {

                    return '<span class="badge bg-warning">' . __("On-Hold") . '</span>';
                } else {

                    return '<span class="badge bg-info">' . __("Completed") . '</span>';
                }
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
            ->editColumn('assigned_by', function ($row) {

                return $row->created_by_prefix . ' ' . $row->created_by_name . ' ' . $row->created_by_last_name;
            })
            ->rawColumns(['action', 'date', 'from', 'name', 'assigned_by', 'priority', 'status'])
            ->make(true);
    }

    public function addTodo(object $request, ?object $branch, object $codeGenerator): object
    {
        $todoPrefix = null;
        if (isset($branch)) {

            $numberOfChildBranch = $branch?->parentBranch && count($branch?->parentBranch?->childBranches) > 0 ? count($branch->parentBranch->childBranches) : '';

            $branchName = $branch?->parentBranch ? $branch?->parentBranch->name : $branch->name;

            $exp = explode(' ', $branchName);

            foreach ($exp as $ex) {
                $str = str_split($ex);
                $todoPrefix .= $str[0];
            }

            $todoPrefix .= $numberOfChildBranch;
        }

        $__todoPrefix = isset($todoPrefix) ? $todoPrefix : 'TD';
        $todoNo = $codeGenerator->generateMonthWise(table: 'todos', column: 'todo_no', prefix: $__todoPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addTodo = new Todo();
        $addTodo->todo_no = $todoNo;
        $addTodo->branch_id = auth()->user()->branch_id;
        $addTodo->task = $request->task;
        $addTodo->priority = $request->priority;
        $addTodo->status = $request->status;
        $addTodo->due_date = date('Y-m-d', strtotime($request->due_date));
        $addTodo->description = $request->description;
        $addTodo->created_by_id = auth()->user()->id;
        $addTodo->created_at = Carbon::now();
        $addTodo->save();

        return $addTodo;
    }

    public function updateTodo(object $request, int $id): object
    {
        $updateTodo = $this->singleTodo(id: $id, with: ['users']);

        foreach ($updateTodo->users as $user) {

            $user->is_delete_in_update = BooleanType::True->value;
            $user->save();
        }

        $updateTodo->task = $request->task;
        $updateTodo->priority = $request->priority;
        $updateTodo->status = $request->status;
        $updateTodo->due_date = date('Y-m-d', strtotime($request->due_date));
        $updateTodo->description = $request->description;
        $updateTodo->save();

        return $updateTodo;
    }

    function changeTodoStatus(object $request, int $id): array
    {
        if (empty($request->status)) {

            return ['pass' => false, 'msg' => __('Please select a status')];
        }

        $todo = $this->singleTodo(id: $id);
        $todo->status = $request->status;
        $todo->save();

        return ['pass' => true];
    }

    function deleteTodo(int $id): void
    {
        $deleteTodo = $this->singleTodo(id: $id);
        if (isset($deleteTodo)) {

            $deleteTodo->delete();
        }
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('todos.branch_id', null);
            } else {

                $query->where('todos.branch_id', $request->branch_id);
            }
        }

        if ($request->priority) {

            $query->where('todos.priority', $request->priority);
        }

        if ($request->status) {

            $query->where('todos.status', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('todos.due_date', $date_range);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('todos.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }

    public function singleTodo(int $id, array $with = null)
    {
        $query = Todo::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
