<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonAjaxCallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function branchAuthenticatedUsers($branchId)
    {
        $branch_id = $branchId != 'NULL' ? $branchId : NULL;
        return DB::table('admin_and_users')
            ->where('branch_id', $branch_id)
            ->where('allow_login', 1)->get();
    }

    public function categorySubcategories($categoryId)
    {
        return DB::table('categories')->where('parent_category_id', $categoryId)->select('id', 'name')->get();
    }
}
