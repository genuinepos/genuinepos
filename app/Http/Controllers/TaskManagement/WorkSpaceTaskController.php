<?php

namespace App\Http\Controllers\TaskManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TaskManagement\Workspace;
use App\Models\TaskManagement\WorkspaceTask;
use App\Services\TaskManagement\WorkspaceService;
use App\Services\TaskManagement\WorkspaceTaskService;

class WorkSpaceTaskController extends Controller
{
    public function __construct(private WorkspaceTaskService $workspaceTaskService, private WorkspaceService $workspaceService)
    {
    }

    public function index($workspaceId)
    {
        abort_if(!auth()->user()->can('workspaces_manage_task') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $workspace = $this->workspaceService->singleWorkspace(id: $workspaceId, with: ['createdBy', 'users', 'users.user']);

        return view('task_management.workspaces.tasks.index', compact('workspace'));
    }

    public function store(Request $request)
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

    public function update(Request $request)
    {
        $this->workspaceTaskService->updateWorkspaceTask(request: $request);

        return response()->json(__('Task updated successfully.'));
    }

    public function assignUser(Request $request, $id)
    {
        $this->workspaceTaskService->assignUser(request: $request, id: $id);

        return response()->json(__('Successfully'));
    }

    public function changeStatus(Request $request, $id)
    {
        $this->workspaceTaskService->changeStatus(request: $request, id: $id);

        return response()->json(__('Successfully'));
    }

    public function changePriority(Request $request, $id)
    {
        $this->workspaceTaskService->changePriority(request: $request, id: $id);

        return response()->json(__('Successfully'));
    }

    public function delete(Request $request, $id)
    {
        $this->workspaceTaskService->deleteWorkspaceTask(id: $id);

        return response()->json(__('Task deleted successfully.'));
    }
}
