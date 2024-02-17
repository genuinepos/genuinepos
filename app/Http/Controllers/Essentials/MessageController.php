<?php

namespace App\Http\Controllers\Essentials;

use App\Http\Controllers\Controller;
use App\Models\Essential\Message;
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
        if (!auth()->user()->can('msg')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        if (!auth()->user()->can('msg')) {
            abort(403, 'Access Forbidden.');
        }

        return view('essentials.messages.index');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('msg')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

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
        if (!auth()->user()->can('msg')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

        $deleteMsg = Message::where('id', $id)->first();
        if (!is_null($deleteMsg)) {
            $deleteMsg->delete();
        }

        return response()->json('Message deleted successfully.');
    }

    public function allMessage()
    {
        if (!auth()->user()->can('msg')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__manage_task'] == 0) {
            abort(403, 'Access Forbidden.');
        }

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
