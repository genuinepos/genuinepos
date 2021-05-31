<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }
    
    // Admin dashboard
    public function index()
    {
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('dashboard.dashboard_1', compact('branches'));
    }
    
    public function changeLang($lang)
    {
        session(['lang' => $lang]);
        return redirect()->back();
    }
}
