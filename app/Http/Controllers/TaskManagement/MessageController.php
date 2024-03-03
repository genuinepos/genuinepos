<?php

namespace App\Http\Controllers\TaskManagement;

use App\Http\Controllers\Controller;
use App\Models\TaskManagement\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('subscriptionRestrictions');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('msg') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        return view('essentials.messages.index');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('msg') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $this->validate($request, [

            'description' => 'required',
        ]);

        Message::insertGetId([
            'description' => $request->description,
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->user()->id,
            'created_at' => Carbon::now(),
        ]);

        return response()->json('Message send successfully.');
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('msg') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

        $deleteMsg = Message::where('id', $id)->first();
        if (!is_null($deleteMsg)) {
            $deleteMsg->delete();
        }

        return response()->json('Message deleted successfully.');
    }

    public function allMessage()
    {
        abort_if(!auth()->user()->can('msg') || config('generalSettings')['subscription']->features['task_management'] == 0, 403);

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
            )
            ->get();

        return view('essentials.messages.ajax_view.message_list', compact('messages'));
    }
}
