<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $categories = DB::table('categories')->select('id', 'name')->get();
        return view('manufacturing.report.index', compact('branches', 'categories'));
    }
}
