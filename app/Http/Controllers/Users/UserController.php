<?php

namespace App\Http\Controllers\Users;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Users\RoleService;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Services\Users\UserActivityLogService;

class UserController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private UserService $userService,
        private RoleService $roleService,
        private UserActivityLogService $userActivityLogService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('user_view'), 403);

        if ($request->ajax()) {

            return $this->userService->usersTable($request);
        }

        $currentUserCount = $this->userService->users()->where('branch_id', auth()->user()->branch_id)->count();

        $branches = $this->branchService->branches(['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('users.index', compact('branches', 'currentUserCount'));
    }

    public function show($id)
    {
        $user = $this->userService->singleUser(id: $id);
        return view('users.show', compact('user'));
    }

    public function create()
    {
        abort_if(!auth()->user()->can('user_add'), 403);

        $roles = $this->roleService->roles()->get();
        $departments = DB::table('hrm_departments')->orderBy('id', 'desc')->get();
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('users.create', compact('departments', 'designations', 'shifts', 'branches', 'roles'));
    }

    public function store(UserStoreRequest $request)
    {
        // $restrictions = $this->userService->storeRestrictions(request: $request);
        $roleId = $request->allow_login == BooleanType::True->value ? $request->role_id : null;
        $role = $this->roleService->singleRole(id: $roleId);
        $this->userService->addUser(request: $request, role: $role);

        return response()->json(__('User created successfully'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('user_edit'), 403);

        $user = $this->userService->singleUser(id: $id);

        $roles = $this->roleService->roles()->get();
        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $departments = DB::table('hrm_departments')->orderBy('id', 'desc')->get();
        $designations = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $shifts = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        return view('users.edit', compact('user', 'roles', 'branches', 'departments', 'designations', 'shifts'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $roleId = $request->allow_login == BooleanType::True->value ? $request->role_id : null;
        $role = $this->roleService->singleRole(id: $roleId);

        $this->userService->updateUser(request: $request, id: $id, role: $role);

        session()->flash('successMsg', __('Successfully user updated'));

        return response()->json(__('User updated successfully'));
    }

    public function delete($id)
    {
        abort_if(!auth()->user()->can('user_delete'), 403);

        $deleteUser = $this->userService->deleteUser(id: $id);

        if ($deleteUser['pass'] == false) {

            return response()->json(['errorMsg' => $deleteUser['msg']]);
        }

        return response()->json(__('User deleted successfully'));
    }

    public function changeBranch(Request $request)
    {
        $this->userService->changeBranch(request: $request);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::LocationSwitch->value, subjectType: UserActivityLogSubjectType::LocationSwitch->value, dataObj: auth()->user());
        if (auth()->user()?->location_switch_log_description) {

            unset(auth()->user()->location_switch_log_description);
        }

        return response()->json(__('Succeed'));
    }

    public function branchUsers($isOnlyAuthenticatedUser, $allowAll, $branchId = null)
    {
        return $this->userService->getBranchUsers(branchId: $branchId, allowAll: $allowAll, isOnlyAuthenticatedUser: $isOnlyAuthenticatedUser);
    }
}
