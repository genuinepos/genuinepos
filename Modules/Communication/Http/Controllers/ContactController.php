<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Communication\Entities\ContactGroup;
use Modules\Communication\Entities\Contacts;
use Modules\Communication\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $groups = ContactGroup::all();
        $numbers = Contacts::all();
        return view('communication::contacts.index', compact('groups', 'numbers'));
    }
}
