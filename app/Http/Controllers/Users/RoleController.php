<?php

namespace App\Http\Controllers\Users;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\Users\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\RoleStoreRequest;
use App\Http\Requests\Users\RoleDeleteRequest;
use App\Http\Requests\Users\RoleUpdateRequest;

class RoleController extends Controller
{
    public function __construct(private RoleService $roleService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('role_view'), 403);

        if ($request->ajax()) {

            return $this->roleService->rolesTable();
        }

        return view('users.roles.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('role_add'), 403);

        return view('users.roles.create');
    }

    public function store(RoleStoreRequest $request)
    {
        $this->roleService->addRole($request);

        session()->flash('successMsg', __('Role added successfully'));
        return redirect()->route('users.role.index');
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        $this->roleService->updateRole(id: $id, request: $request);

        session()->flash('successMsg', __('Role updated successfully'));
        return redirect()->route('users.role.index');
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('user_edit'), 403);

        $role = $this->roleService->singleRole(id: $id);

        return view('users.roles.edit', compact('role'));
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
