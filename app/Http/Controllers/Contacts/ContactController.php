<?php

namespace App\Http\Controllers\Contacts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function create($type) {

        $customerGroups = DB::table('customer_groups')->select('id', 'group_name')->get();
        return view('contacts.ajax_view.create', compact('type', 'customerGroups'));
    }
}
