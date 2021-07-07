<?php

namespace App\Http\Controllers\Essentials;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    public function index()
    {
        return view('essentials.memos.index');
    }
}
