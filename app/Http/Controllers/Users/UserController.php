<?php

namespace App\Http\Controllers\Users;

use App\Models\Role;
use App\Models\User;
use App\Enums\BooleanType;
use App\Utils\FileUploader;
use Illuminate\Http\Request;
use App\Models\Setups\Branch;
use App\Models\AdminUserBranch;
use Illuminate\Support\Facades\DB;
use App\Services\Users\RoleService;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private UserService $userService,
        private RoleService $roleService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('user_view')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->userService->usersTable($request);
        }

        $branches = $this->branchService->branches(['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('users.index', compact('branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('user_add')) {

            abort(403, 'Access Forbidden.');
        }

        $roles = $this->roleService->roles()->get();
        $departments = DB::table('hrm_departments')->orderBy('id', 'desc')->get();
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();



        return view('users.create', compact('departments', 'designations', 'shifts', 'branches', 'roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('user_add')) {

            abort(403, 'Access Forbidden.');
        }

        $roleId = $request->allow_login == BooleanType::True->value ? $request->role_id : null;
        $role = $this->roleService->singleRole(id: $roleId);
        $this->userService->addUserValidation(request: $request, role: $role);
        $this->userService->addUser(request: $request, role: $role);

        return response()->json(__('User created successfully'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('user_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $user = $this->userService->singleUser(id: $id);

        $roles = $this->roleService->roles()->get();
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $departments = DB::table('hrm_departments')->orderBy('id', 'desc')->get();
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        return view('users.edit', compact('user', 'roles', 'branches', 'departments', 'designations', 'shifts'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('user_edit')) {
            
            abort(403, 'Access Forbidden.');
        }

        $roleId = $request->allow_login == BooleanType::True->value ? $request->role_id : null;
        $role = $this->roleService->singleRole(id: $roleId);

        $this->userService->updateUserValidation(request: $request, id: $id, role: $role);

        $this->userService->updateUser(request: $request, id: $id, role: $role);

        session()->flash('successMsg', __('Successfully user updated'));

        return response()->json(__('User updated successfully'));
    }

    public function delete($id)
    {
        if (!auth()->user()->can('user_delete')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteUser = $this->userService->deleteUser(id: $id);

        if ($deleteUser['pass'] == false) {

            return response()->json(['errorMsg' => $deleteUser['msg']]);
        }

        return response()->json(__('User deleted successfully'));
    }

    public function changeBranch(Request $request)
    {
        $this->userService->changeBranch(request: $request);
        return response()->json(__('Succeed'));
    }

    public function show($id)
    {
        if (!auth()->user()->can('user_view')) {
            abort(403, 'Access Forbidden.');
        }

        $user = $this->userService->singleUser(id: $id);
        return view('users.show', compact('user'));
    }
}
