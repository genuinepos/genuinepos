<?php

namespace App\Http\Controllers\hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        return view('hrm.dashboard.hrm_dashboard', compact('branches'));
    }

    public function userCountTable(Request $request)
    {
        $userCount = '';
        $users = '';
        $userCountQ = DB::table('admin_and_users');
        $usersQ = DB::table('hrm_department')
        ->leftJoin('admin_and_users', 'hrm_department.id', 'admin_and_users.department_id')
        ->select(
            DB::raw('COUNT(admin_and_users.id) as total_users'),
            'hrm_department.department_name'
        );
        
        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $userCountQ->where('admin_and_users.branch_id', NULL);
                $usersQ->where('admin_and_users.branch_id', NULL);
            } else {
                $userCountQ->where('admin_and_users.branch_id', $request->branch_id);
                $usersQ->where('admin_and_users.branch_id', $request->branch_id);
            }
        }

        $userCount = $userCountQ->count();
        $users = $usersQ->groupBy('admin_and_users.department_id')
        ->groupBy('department_name')
        ->get();

        return view('hrm.dashboard.ajax_view.user_count_table', compact('userCount', 'users'));
    }
}
