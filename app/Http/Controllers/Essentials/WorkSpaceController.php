<?php

namespace App\Http\Controllers\Essentials;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WorkSpaceController extends Controller
{
    public function index()
    {
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        $users = DB::table('admin_and_users')->where('branch_id', auth()->user()->branch_id)->get(['id', 'prefix', 'name', 'last_name']);
        return view('essentials.work_space.index', compact('branches', 'users'));
    }
}
