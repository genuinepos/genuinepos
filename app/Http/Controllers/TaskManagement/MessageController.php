<?php

namespace App\Http\Controllers\TaskManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\TaskManagement\MessageService;
use App\Http\Requests\TaskManagement\MessageStoreRequest;

class MessageController extends Controller
{
    public function __construct(private MessageService $messageService)
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('messages_index') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        return view('task_management.messages.index');
    }

    public function store(MessageStoreRequest $request)
    {
        $this->messageService->addMessage(request: $request);
        return response()->json(__('Message send successfully.'));
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('messages_delete') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $this->messageService->deleteMessage(id: $id);

        return response()->json(__('Message deleted successfully.'));
    }

    public function allMessage()
    {
        abort_if(!auth()->user()->can('messages_index') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $messages = DB::table('messages')
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

        return view('task_management.messages.ajax_view.message_list', compact('messages'));
    }
}
