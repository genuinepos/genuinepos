<?php

namespace App\Services\TaskManagement;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\TaskManagement\Message;

class MessageService
{
    public function addMessage(object $request): void
    {
        Message::insertGetId([
            'description' => $request->description,
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->user()->id,
            'created_at' => Carbon::now(),
        ]);
    }

    public function deleteMessage(int $id): void
    {
        $deleteMsg = $this->singleMessage(id: $id);

        if (isset($deleteMsg)) {

            $deleteMsg->delete();
        }
    }

    public function singleMessage(int $id, array $with = null)
    {
        $query = Message::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function allMessages(): object
    {
        return DB::table('messages')
            ->leftJoin('users', 'messages.user_id', 'users.id')
            ->where('messages.branch_id', auth()->user()->branch_id)
            ->select(
                'messages.id',
                'messages.user_id',
                'messages.description',
                'messages.created_at',
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
            )->orderBy('id', 'asc')->get();
    }
}
