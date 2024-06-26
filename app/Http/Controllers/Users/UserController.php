<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserDeleteRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Services\Users\UserActivityLogService;
use App\Interfaces\Users\UserControllerMethodContainersInterface;

class UserController extends Controller
{
    public function index(Request $request, UserControllerMethodContainersInterface $userControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('user_view'), 403);

        $indexMethodContainer = $userControllerMethodContainersInterface->indexMethodContainer(request: $request);

        if ($request->ajax()) {

            return $indexMethodContainer;
        }

        extract($indexMethodContainer);

        return view('users.index', compact('branches'));
    }

    public function show($id, UserControllerMethodContainersInterface $userControllerMethodContainersInterface)
    {
        $showMethodContainer = $userControllerMethodContainersInterface->showMethodContainer(id: $id);
        extract($showMethodContainer);

        return view('users.show', compact('user'));
    }

    public function create(UserControllerMethodContainersInterface $userControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('user_add'), 403);

        $createMethodContainer = $userControllerMethodContainersInterface->createMethodContainer();
        extract($createMethodContainer);

        return view('users.create', compact('departments', 'designations', 'shifts', 'branches', 'roles'));
    }

    public function store(UserStoreRequest $request, UserControllerMethodContainersInterface $userControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $storeMethodContainer = $userControllerMethodContainersInterface->storeMethodContainer(request: $request);

            if (isset($storeMethodContainer['pass']) && $storeMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $storeMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('User created successfully'));
    }

    public function edit($id, UserControllerMethodContainersInterface $userControllerMethodContainersInterface)
    {
        abort_if(!auth()->user()->can('user_edit'), 403);

        $editMethodContainer = $userControllerMethodContainersInterface->editMethodContainer(id: $id);

        extract($editMethodContainer);

        return view('users.edit', compact('user', 'roles', 'branches', 'departments', 'designations', 'shifts'));
    }

    public function update($id, UserUpdateRequest $request, UserControllerMethodContainersInterface $userControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $updateMethodContainer = $userControllerMethodContainersInterface->updateMethodContainer(id: $id, request: $request);

            if (isset($updateMethodContainer['pass']) && $updateMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $updateMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('successMsg', __('Successfully user updated'));

        return response()->json(__('User updated successfully'));
    }

    public function delete($id, UserDeleteRequest $request, UserControllerMethodContainersInterface $userControllerMethodContainersInterface)
    {
        try {
            DB::beginTransaction();

            $deleteMethodContainer = $userControllerMethodContainersInterface->deleteMethodContainer(id: $id);

            if (isset($deleteMethodContainer['pass']) && $deleteMethodContainer['pass'] == false) {

                return response()->json(['errorMsg' => $deleteMethodContainer['msg']]);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('User deleted successfully'));
    }

    public function changeBranch(Request $request, UserControllerMethodContainersInterface $userControllerMethodContainersInterface)
    {
        $deleteMethodContainer = $userControllerMethodContainersInterface->changeBranchMethodContainer(request: $request);

        return response()->json(__('Succeed'));
    }

    public function branchUsers(UserControllerMethodContainersInterface $userControllerMethodContainersInterface, $isOnlyAuthenticatedUser, $allowAll, $branchId = null)
    {
        return $userControllerMethodContainersInterface->branchUsersMethodContainer(isOnlyAuthenticatedUser: $isOnlyAuthenticatedUser, allowAll: $allowAll, branchId: $branchId);
    }

    function currentUserAndEmployeeCount(UserControllerMethodContainersInterface $userControllerMethodContainersInterface, $branchId = null)
    {
        return $userControllerMethodContainersInterface->currentUserAndEmployeeCountMethodContainer(branchId: $branchId);
    }
}
