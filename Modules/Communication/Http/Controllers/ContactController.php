<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Communication\Entities\CommunicationContact;
use Modules\Communication\Entities\CommunicationContactGroup;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $groups = CommunicationContactGroup::all();
        $numbers = CommunicationContact::all();

        return view('communication::contacts.index', compact('groups', 'numbers'));
    }
}
