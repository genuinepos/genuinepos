<?php

namespace App\Http\Controllers\TaskManagement;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\TaskManagement\MessageService;
use App\Http\Requests\TaskManagement\MessageIndexRequest;
use App\Http\Requests\TaskManagement\MessageStoreRequest;
use App\Http\Requests\TaskManagement\MessageDeleteRequest;

class MessageController extends Controller
{
    public function __construct(private MessageService $messageService) {}

    public function index(MessageIndexRequest $request)
    {
        return view('task_management.messages.index');
    }

    public function store(MessageStoreRequest $request)
    {
        $this->messageService->addMessage(request: $request);
        return response()->json(__('Message send successfully.'));
    }

    public function delete($id, MessageDeleteRequest $request)
    {
        $this->messageService->deleteMessage(id: $id);

        return response()->json(__('Message deleted successfully.'));
    }

    public function allMessage(MessageIndexRequest $request)
    {
        $messages = $this->messageService->allMessages();

        return view('task_management.messages.ajax_view.message_list', compact('messages'));
    }
}
