<?php

namespace App\Services\Users\MethodContainerServices;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Services\Users\RoleService;
use App\Services\Users\UserService;
use App\Services\Setups\BranchService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Users\UserActivityLogService;
use App\Interfaces\Users\UserControllerMethodContainersInterface;

class UserControllerMethodContainersService implements UserControllerMethodContainersInterface
{
    public function __construct(
        private BranchService $branchService,
        private UserService $userService,
        private RoleService $roleService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->userService->usersTable($request);
        }

        $data['branches'] = $this->branchService->branches(['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function showMethodContainer(int $id): ?array
    {
        $data = [];
        $data['user'] = $this->userService->singleUser(id: $id);
        return $data;
    }

    public function createMethodContainer(): ?array
    {
        $data = [];
        $data['roles'] = $this->roleService->roles()->get();
        $data['departments'] = DB::table('hrm_departments')->orderBy('id', 'desc')->get();
        $data['designations'] = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $data['shifts'] = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return $data;
    }

    public function storeMethodContainer(object $request): ?array
    {
        $restrictions = $this->userService->storeRestrictions(request: $request);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $roleId = $request->allow_login == BooleanType::True->value ? $request->role_id : null;
        $role = $this->roleService->singleRole(id: $roleId);
        $this->userService->addUser(request: $request, role: $role);
        return null;
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $data['user'] = $this->userService->singleUser(id: $id);

        $data['roles'] = $this->roleService->roles()->get();
        $data['branches'] = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $data['departments'] = DB::table('hrm_departments')->orderBy('id', 'desc')->get();
        $data['designations'] = DB::table('hrm_designations')->orderBy('id', 'desc')->get();
        $data['shifts'] = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): ?array
    {
        $restrictions = $this->userService->updateRestrictions(request: $request, id: $id);

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $roleId = $request->allow_login == BooleanType::True->value ? $request->role_id : null;
        $role = $this->roleService->singleRole(id: $roleId);

        $this->userService->updateUser(request: $request, id: $id, role: $role);

        return null;
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deleteUser = $this->userService->deleteUser(id: $id);

        if ($deleteUser['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        return null;
    }

    public function changeBranchMethodContainer(object $request): void
    {
        $this->userService->changeBranch(request: $request);

        $this->userActivityLogService->addLog(action: UserActivityLogActionType::LocationSwitch->value, subjectType: UserActivityLogSubjectType::LocationSwitch->value, dataObj: auth()->user());
        if (auth()->user()?->location_switch_log_description) {

            unset(auth()->user()->location_switch_log_description);
        }
    }

    public function branchUsersMethodContainer(int|string $isOnlyAuthenticatedUser, int|string $allowAll, mixed $branchId = null): ?object
    {
        return $this->userService->getBranchUsers(branchId: $branchId, allowAll: $allowAll, isOnlyAuthenticatedUser: $isOnlyAuthenticatedUser);
    }

    public function currentUserAndEmployeeCountMethodContainer(?int $branchId = null): array
    {
        return $this->userService->currentUserAndEmployeeCount($branchId);
    }
}
