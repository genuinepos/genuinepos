<?php

namespace App\Services\TaskManagement;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TaskManagement\WorkspaceUsers;

class WorkspaceUserService
{
    function addWorkspaceUsers(object $request, int $workspaceId): void
    {
        if (isset($request->user_ids) && count($request->user_ids) > 0) {

            foreach ($request->user_ids as $user_id) {

                WorkspaceUsers::insert([
                    'workspace_id' => $workspaceId,
                    'user_id' => $user_id,
                ]);
            }
        }
    }

    function updateWorkspaceUsers(object $request, int $workspaceId): void
    {
        if (isset($request->user_ids) && count($request->user_ids) > 0) {

            foreach ($request->user_ids as $userId) {

                $addOrUpdateWorkspaceUser = null;
                $workspaceUser = $this->singleWorkspaceUser(workspaceId: $workspaceId, userId: $userId);

                if (isset($workspaceUser)) {

                    $addOrUpdateWorkspaceUser = $workspaceUser;
                } else {

                    $addOrUpdateWorkspaceUser = new WorkspaceUsers();
                }

                $addOrUpdateWorkspaceUser->workspace_id = $workspaceId;
                $addOrUpdateWorkspaceUser->user_id = $userId;
                $addOrUpdateWorkspaceUser->is_delete_in_update = BooleanType::False->value;
                $addOrUpdateWorkspaceUser->save();
            }
        }

        $deleteUnusedWorkspaceUsers = $this->workspaceUsers()->where('workspace_id', $workspaceId)
            ->where('is_delete_in_update', BooleanType::True->value)->get();

        foreach ($deleteUnusedWorkspaceUsers as $deleteUnusedWorkspaceUser) {
            $deleteUnusedWorkspaceUser->delete();
        }
    }

    public function singleWorkspaceUser(int $workspaceId, int $userId, array $with = null)
    {
        $query = WorkspaceUsers::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('workspace_id', $workspaceId)->where('user_id', $userId)->first();
    }

    public function workspaceUsers(array $with = null)
    {
        $query = WorkspaceUsers::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
