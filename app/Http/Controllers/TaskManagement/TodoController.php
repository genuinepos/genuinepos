<?php

namespace App\Http\Controllers\TaskManagement;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\CodeGenerationService;
use App\Services\TaskManagement\TodoService;
use App\Services\TaskManagement\TodoUserService;
use App\Http\Requests\TaskManagement\TodoStoreRequest;
use App\Http\Requests\TaskManagement\TodoDeleteRequest;
use App\Http\Requests\TaskManagement\TodoUpdateRequest;
use App\Http\Requests\TaskManagement\TodoChangeStatusRequest;

class TodoController extends Controller
{
    public function __construct(
        private TodoService $todoService,
        private TodoUserService $todoUserService,
        private UserService $userService,
        private BranchService $branchService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('todo_index') || config('generalSettings')['subscription']->features['task_management'] == BooleanType::False->value, 403);

        if ($request->ajax()) {

            return $this->todoService->todoTable(request: $request);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('task_management.todo.index', compact('branches'));
    }

    public function show($id)
    {
        $todo = $this->todoService->singleTodo(id: $id, with: ['createdBy', 'users', 'users.user']);
        return view('task_management.todo.ajax_view.show', compact('todo'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('todo_create') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);
        $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);
        return view('task_management.todo.ajax_view.create', compact('users'));
    }

    public function store(TodoStoreRequest $request, CodeGenerationService $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $branch = $this->branchService->singleBranch(id: auth()->user()->branch_id, with: ['parentBranch', 'childBranches']);
            $addTodo = $this->todoService->addTodo(request: $request, branch: $branch, codeGenerator: $codeGenerator);
            $this->todoUserService->addTodoUsers(request: $request, todoId: $addTodo->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Todo created successfully.'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('todo_edit') || config('generalSettings')['subscription']->features['task_management'] == BooleanType::False->value, 403);

        $todo = $this->todoService->singleTodo(id: $id, with: ['users']);
        $users = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);

        return view('task_management.todo.ajax_view.edit', compact('todo', 'users'));
    }

    public function update($id, TodoUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateTodo = $this->todoService->updateTodo(request: $request, id: $id);
            $this->todoUserService->updateTodoUsers(request: $request, todoId: $id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Todo update successfully.'));
    }

    public function changeStatusModal($id)
    {
        abort_if(!auth()->user()->can('todo_change_status') || config('generalSettings')['subscription']->features['task_management'] == BooleanType::False->value, 403);

        $todo = $this->todoService->singleTodo(id: $id);

        return view('task_management.todo.ajax_view.change_status', compact('todo'));
    }

    public function changeStatus(TodoChangeStatusRequest $request, $id)
    {
        $changeStatus = $this->todoService->changeTodoStatus(request: $request, id: $id);
        if ($changeStatus['pass'] == false) {

            return response()->json(['errorMsg' => $changeStatus['msg']]);
        }

        return response()->json(__('Todo status changed successfully.'));
    }

    public function delete(TodoDeleteRequest $request, $id)
    {
        $this->todoService->deleteTodo(id: $id);
        return response()->json(__('Todo deleted successfully.'));
    }
}
