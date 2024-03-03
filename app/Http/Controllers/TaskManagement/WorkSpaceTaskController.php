<?php

namespace App\Http\Controllers\TaskManagement;

use App\Http\Controllers\Controller;
use App\Models\TaskManagement\Workspace;
use App\Models\TaskManagement\WorkspaceTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkSpaceTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index($workspaceId)
    {
        abort_if(!auth()->user()->can('work_space') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $ws = Workspace::with(['admin', 'ws_users', 'ws_users.user'])->where('id', $workspaceId)->first();

        return view('essentials.work_space.tasks.index', compact('ws'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('work_space') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        WorkspaceTask::insert([
            'workspace_id' => $request->ws_id,
            'task_name' => $request->task_name,
            'status' => $request->task_status,
        ]);

        return response()->json('Task added successfully.');
    }

    public function taskList($workspaceId)
    {
        abort_if(!auth()->user()->can('work_space') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $ws_tasks = DB::table('workspace_tasks')->where('workspace_id', $workspaceId)
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

        $ws_users = DB::table('workspace_users')->where('workspace_id', $workspaceId)
            ->leftJoin('users', 'workspace_users.user_id', 'users.id')
            ->select(
                'users.id',
                'users.prefix',
                'users.name',
                'users.last_name',
            )->get();

        return view('essentials.work_space.tasks.ajax_view.task_list', compact('ws_tasks', 'ws_users'));
    }

    public function update(Request $request)
    {
        abort_if(!auth()->user()->can('work_space') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $updateTask = WorkspaceTask::where('id', $request->id)->first();
        $updateTask->update([
            'task_name' => $request->value,
        ]);

        return response()->json('Task updated successfully.');
    }

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('work_space') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $deleteWorkspaceTask = WorkspaceTask::where('id', $id)->first();
        if (!is_null($deleteWorkspaceTask)) {
            $deleteWorkspaceTask->delete();
        }

        return response()->json('Task deleted successfully.');
    }

    public function assignUser(Request $request, $id)
    {
        abort_if(!auth()->user()->can('work_space') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $updateTask = WorkspaceTask::where('id', $id)->first();
        $updateTask->update([
            'user_id' => $request->user_id,
        ]);

        return response()->json('Successfully.');
    }

    public function changeStatus(Request $request, $id)
    {
        abort_if(!auth()->user()->can('work_space') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $updateTask = WorkspaceTask::where('id', $id)->first();
        $updateTask->update([
            'status' => $request->status,
        ]);

        return response()->json('Successfully.');
    }

    public function changePriority(Request $request, $id)
    {
        abort_if(!auth()->user()->can('work_space') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $updateTask = WorkspaceTask::where('id', $id)->first();
        $updateTask->update([
            'priority' => $request->priority,
        ]);

        return response()->json('Successfully.');
    }
}
