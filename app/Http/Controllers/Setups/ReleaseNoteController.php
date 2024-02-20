<?php

namespace App\Http\Controllers\Setups;

use App\Http\Controllers\Controller;

class ReleaseNoteController extends Controller
{
    public function index()
    {
        return view('setups.version_release_note.index');
    }
}
