<?php

namespace App\Http\Controllers\TaskManagement;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\TaskManagement\WorkspaceService;
use App\Services\TaskManagement\WorkspaceTaskService;
use App\Http\Requests\TaskManagement\WorkspaceTaskIndexRequest;
use App\Http\Requests\TaskManagement\WorkspaceTaskStoreRequest;
use App\Http\Requests\TaskManagement\WorkspaceTaskDeleteRequest;
use App\Http\Requests\TaskManagement\WorkspaceTaskUpdateRequest;
use App\Http\Requests\TaskManagement\WorkspaceTaskAssignUserRequest;
use App\Http\Requests\TaskManagement\WorkspaceTaskChangeStatusRequest;
use App\Http\Requests\TaskManagement\WorkspaceTaskChangePriorityRequest;

class WorkSpaceTaskController extends Controller
{
    public function __construct(private WorkspaceTaskService $workspaceTaskService, private WorkspaceService $workspaceService)
    {
    }

    public function index($workspaceId, WorkspaceTaskIndexRequest $request)
    {
        $workspace = $this->workspaceService->singleWorkspace(id: $workspaceId, with: ['createdBy', 'users', 'users.user']);

        return view('task_management.workspaces.tasks.index', compact('workspace'));
    }

    public function store(WorkspaceTaskStoreRequest $request)
    {
        $this->workspaceTaskService->addWorkspaceTask(request: $request);

        return response()->json(__('Task added successfully.'));
    }

    public function taskList($workspaceId)
    {
        $wsTasks = DB::table('workspace_tasks')->where('workspace_id', $workspaceId)
            ->leftJoin('users', 'workspace_tasks.user_id', 'users.id')
            ->select(
                'workspace_tasks.id',
                'workspace_tasks.task_name',
                'workspace_tasks.status',
                'workspace_tasks.deadline',
                'workspace_tasks.priority',
                'users.id as u_id',
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
            )->orderBy('workspace_tasks.id', 'desc')->get();

        $wsUsers = DB::table('workspace_users')->where('workspace_id', $workspaceId)
            ->leftJoin('users', 'workspace_users.user_id', 'users.id')
            ->select(
                'users.id',
                'users.prefix',
                'users.name',
                'users.last_name',
            )->get();

        return view('task_management.workspaces.tasks.ajax_view.task_list', compact('wsTasks', 'wsUsers'));
    }

    public function update(WorkspaceTaskUpdateRequest $request)
    {
        $this->workspaceTaskService->updateWorkspaceTask(request: $request);

        return response()->json(__('Task updated successfully.'));
    }

    public function assignUser(WorkspaceTaskAssignUserRequest $request, $id)
    {
        $this->workspaceTaskService->assignUser(request: $request, id: $id);

        return response()->json(__('Successfully'));
    }

    public function changeStatus(WorkspaceTaskChangeStatusRequest $request, $id)
    {
        $this->workspaceTaskService->changeStatus(request: $request, id: $id);

        return response()->json(__('Successfully'));
    }

    public function changePriority(WorkspaceTaskChangePriorityRequest $request, $id)
    {
        $this->workspaceTaskService->changePriority(request: $request, id: $id);

        return response()->json(__('Successfully'));
    }

    public function delete(WorkspaceTaskDeleteRequest $request, $id)
    {
        $this->workspaceTaskService->deleteWorkspaceTask(id: $id);

        return response()->json(__('Task deleted successfully.'));
    }
}
