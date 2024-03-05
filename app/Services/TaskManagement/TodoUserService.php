<?php

namespace App\Services\TaskManagement;

use App\Enums\BooleanType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\TaskManagement\TodoUsers;
use Yajra\DataTables\Facades\DataTables;

class TodoUserService
{
    public function addTodoUsers(object $request, int $todoId): void
    {
        if (count($request->user_ids) > 0) {

            foreach ($request->user_ids as $user_id) {

                TodoUsers::insert([
                    'todo_id' => $todoId,
                    'user_id' => $user_id,
                ]);
            }
        }
    }

    public function updateTodoUsers(object $request, int $todoId): void
    {
        if (count($request->user_ids) > 0) {

            foreach ($request->user_ids as $userId) {

                $addOrUpdateTodoUser = null;
                $todoUser = $this->singleTodoUser(todoId: $todoId, userId: $userId);

                if (isset($todoUser)) {

                    $addOrUpdateTodoUser = $todoUser;
                } else {

                    $addOrUpdateTodoUser = new TodoUsers();
                }

                $addOrUpdateTodoUser->todo_id = $todoId;
                $addOrUpdateTodoUser->user_id = $userId;
                $addOrUpdateTodoUser->is_delete_in_update = BooleanType::False->value;
                $addOrUpdateTodoUser->save();
            }
        }

        $deleteUnusedTodoUsers = $this->todoUsers()->where('todo_id', $todoId)->where('is_delete_in_update', BooleanType::True->value)->get();
        foreach ($deleteUnusedTodoUsers as $deleteUnusedTodoUser) {
            
            $deleteUnusedTodoUser->delete();
        }
    }

    public function singleTodoUser(int $todoId, int $userId, array $with = null)
    {
        $query = TodoUsers::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('todo_id', $todoId)->where('user_id', $userId)->first();
    }

    public function todoUsers(array $with = null)
    {
        $query = TodoUsers::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
