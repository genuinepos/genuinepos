<?php

namespace App\Http\Controllers\Users;

use App\Services\Users\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\RoleEditRequest;
use App\Http\Requests\Users\RoleIndexRequest;
use App\Http\Requests\Users\RoleStoreRequest;
use App\Http\Requests\Users\RoleCreateRequest;
use App\Http\Requests\Users\RoleDeleteRequest;
use App\Http\Requests\Users\RoleUpdateRequest;

class RoleController extends Controller
{
    public function __construct(private RoleService $roleService)
    {
    }

    public function index(RoleIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->roleService->rolesTable();
        }

        return view('users.roles.index');
    }

    public function create(RoleCreateRequest $request)
    {
        return view('users.roles.create');
    }

    public function store(RoleStoreRequest $request)
    {
        $this->roleService->addRole($request);

        session()->flash('successMsg', __('Role added successfully'));
        return redirect()->route('users.role.index');
    }

    public function edit($id, RoleEditRequest $request)
    {
        $role = $this->roleService->singleRole(id: $id);

        return view('users.roles.edit', compact('role'));
    }

    public function update($id, RoleUpdateRequest $request)
    {
        $this->roleService->updateRole(id: $id, request: $request);

        session()->flash('successMsg', __('Role updated successfully'));
        return redirect()->route('users.role.index');
    }

    public function delete(RoleDeleteRequest $request, $id)
    {
        $deletedRole = $this->roleService->deleteRole($id);
        if ($deletedRole['pass'] == false) {

            return response()->json(['errorMsg' => $deletedRole['msg']]);
        }

        return response()->json(__('Role deleted successfully'));
    }
}
