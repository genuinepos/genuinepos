<?php

namespace App\Services\TaskManagement;

use App\Models\TaskManagement\WorkspaceTask;

class WorkspaceTaskService
{
    public function addWorkspaceTask(object $request): void
    {
        WorkspaceTask::insert([
            'workspace_id' => $request->workspace_id,
            'task_name' => $request->task_name,
            'status' => $request->task_status,
        ]);
    }

    public function updateWorkspaceTask(object $request): void
    {
        $updateTask = $this->singleWorkspaceTask(id: $request->id);
        $updateTask->update([
            'task_name' => $request->value,
        ]);
    }

    public function assignUser(object $request, int $id): void
    {
        $updateTask = $this->singleWorkspaceTask(id: $id);

        $updateTask->update([
            'user_id' => $request->user_id,
        ]);
    }

    public function changeStatus(object $request, int $id): void
    {
        $updateTask = $this->singleWorkspaceTask(id: $id);

        $updateTask->update([
            'status' => $request->status,
        ]);
    }

    public function changePriority(object $request, int $id): void
    {
        $updateTask = $this->singleWorkspaceTask(id: $id);

        $updateTask->update([
            'priority' => $request->priority,
        ]);
    }

    public function deleteWorkspaceTask(int $id): void
    {
        $deleteWorkspaceTask = $this->singleWorkspaceTask(id: $id);

        if (isset($deleteWorkspaceTask)) {

            $deleteWorkspaceTask->delete();
        }
    }

    public function singleWorkspaceTask(int $id, array $with = null)
    {
        $query = WorkspaceTask::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
