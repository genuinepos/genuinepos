<?php

namespace App\Http\Controllers;

class ReleaseNoteController extends Controller
{
    public function index()
    {
        return view('settings.version_release_note.index');
    }
}
