<?php

namespace App\Http\Controllers\Essentials;

use App\Http\Controllers\Controller;
use App\Models\Essential\Todo;
use App\Models\Essential\TodoUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('assign_todo')) {

            abort(403, 'Access Forbidden.');
        }
        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = config('generalSettings');

            $todos = '';
            $query = DB::table('todos')->leftJoin('branches', 'todos.branch_id', 'branches.id')
                ->leftJoin('users', 'todos.admin_id', 'users.id');

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
                $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $query->whereBetween('todos.due_date', $date_range);
            }

            $query->select(
                'todos.*',
                'branches.name as branch_name',
                'branches.branch_code',
                'users.prefix',
                'users.name as a_name',
                'users.last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $todos = $query->orderBy('todos.id', 'desc');
            } else {
                $todos = $query->where('todos.branch_id', auth()->user()->branch_id)
                    ->orderBy('id', 'desc');
            }

            return DataTables::of($todos)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" id="show" href="' . route('todo.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                    $html .= '<a class="dropdown-item" id="edit" href="' . route('todo.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';

                    $html .= '<a class="dropdown-item" id="change_status" href="' . route('todo.status.modal', [$row->id]) . '"><i class="fas fa-pen-nib text-primary"></i> Change Status</a>';

                    $html .= '<a class="dropdown-item" id="delete" href="' . route('todo.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('due_date', function ($row) {
                    return date('d/m/Y', strtotime($row->due_date));
                })
                ->editColumn('priority', function ($row) {
                    if ($row->priority == 'High') {
                        return '<span class="badge bg-danger">' . $row->priority . '</span>';
                    } elseif ($row->priority == 'Low') {
                        return '<span class="badge bg-warning">' . $row->priority . '</span>';
                    } elseif ($row->priority == 'Medium') {
                        return '<span class="badge bg-secondary">' . $row->priority . '</span>';
                    } else {
                        return '<span class="badge bg-1">' . $row->priority . '</span>';
                    }
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'New') {
                        return '<span class="badge bg-primary">' . $row->status . '</span>';
                    } elseif ($row->status == 'In-Progress') {
                        return '<span class="badge bg-secondary">' . $row->status . '</span>';
                    } elseif ($row->status == 'On-Hold') {
                        return '<span class="badge bg-warning">' . $row->status . '</span>';
                    } else {
                        return '<span class="badge bg-info">' . $row->status . '</span>';
                    }
                })
                ->editColumn('from', function ($row) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return '<b>Head Office</b>';
                    }
                })
                ->editColumn('assigned_by', function ($row) {
                    return $row->prefix . ' ' . $row->a_name . ' ' . $row->last_name;
                })
                ->rawColumns(['action', 'date', 'from', 'name', 'assigned_by', 'priority', 'status'])
                ->make(true);
        }

        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        $users = DB::table('users')
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name']);

        return view('essentials.todo.index', compact('branches', 'users'));
    }

    public function store(Request $request)
    {

        if (!auth()->user()->can('assign_todo')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        if (!auth()->user()->can('assign_todo')) {

            return response()->json(['errorMsg' => 'You do\'t have any permission to assign the todo.']);
        }

        $this->validate($request, [
            'task' => 'required',
            'priority' => 'required',
            'status' => 'required',
        ]);

        // Generate invoice ID
        $i = 4;
        $a = 0;
        $IdNo = '';
        while ($a < $i) {
            $IdNo .= rand(1, 9);
            $a++;
        }

        $addTodo = Todo::insertGetId([
            'todo_id' => date('my') . $IdNo,
            'branch_id' => auth()->user()->branch_id,
            'task' => $request->task,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => date('Y-m-d', strtotime($request->due_date)),
            'description' => $request->description,
            'admin_id' => auth()->user()->id,
            'created_at' => date('Y-m-d'),
        ]);

        if (count($request->user_ids) > 0) {
            foreach ($request->user_ids as $user_id) {
                TodoUsers::insert([
                    'todo_id' => $addTodo,
                    'user_id' => $user_id,
                ]);
            }
        }

        return response()->json('Todo created successfully.');
    }

    public function edit($id)
    {

        if (!auth()->user()->can('assign_todo')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        $todo = Todo::with(['todo_users'])->where('id', $id)->first();
        $users = DB::table('users')
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'prefix', 'name', 'last_name']);

        return view('essentials.todo.ajax_view.edit', compact('todo', 'users'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('assign_todo')) {

            abort(403, 'Access Forbidden.');
        }
        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'task' => 'required',
            'priority' => 'required',
            'status' => 'required',
        ]);

        $updateTodo = Todo::with('todo_users')->where('id', $id)->first();

        foreach ($updateTodo->todo_users as $user) {
            $user->is_delete_in_update = 1;
            $user->save();
        }

        $updateTodo->update([
            'task' => $request->task,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => date('Y-m-d', strtotime($request->due_date)),
            'description' => $request->description,
            'admin_id' => auth()->user()->id,
        ]);

        if (count($request->user_ids) > 0) {
            foreach ($request->user_ids as $user_id) {
                $existsUser = TodoUsers::where('todo_id', $id)
                    ->where('user_id', $user_id)->first();
                if ($existsUser) {
                    $existsUser->is_delete_in_update = 0;
                    $existsUser->save();
                } else {
                    TodoUsers::insert([
                        'todo_id' => $id,
                        'user_id' => $user_id,
                    ]);
                }
            }
        }

        $deleteUsers = TodoUsers::where('todo_id', $id)->where('is_delete_in_update', 1)->get();
        foreach ($deleteUsers as $deleteUser) {
            $deleteUser->delete();
        }

        return response()->json('Todo update successfully.');
    }

    public function changeStatusModal($id)
    {
        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        $todo = Todo::with('todo_users')->where('id', $id)->first(['id', 'status']);

        return view('essentials.todo.ajax_view.change_status', compact('todo'));
    }

    public function changeStatus(Request $request, $id)
    {
        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        $todo = Todo::where('id', $id)->first();
        $todo->update([
            'status' => $request->status,
        ]);

        return response()->json('Todo status changed successfully.');
    }

    public function show($id)
    {
        if (!auth()->user()->can('assign_todo')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        $todo = Todo::with(['admin', 'todo_users', 'todo_users.user'])->where('id', $id)->first();

        return view('essentials.todo.ajax_view.show', compact('todo'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('assign_todo')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        $deleteTodo = Todo::where('id', $id)->first();
        if (!is_null($deleteTodo)) {
            $deleteTodo->delete();
        }

        return response()->json('Todo deleted successfully.');
    }
}
