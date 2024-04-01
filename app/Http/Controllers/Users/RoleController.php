<?php

namespace App\Http\Controllers\Users;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\Users\RoleService;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct(
        private RoleService $roleService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('role_view')) {
            
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->roleService->rolesTable();
        }

        return view('users.roles.index');
    }

    public function create()
    {
        if (!auth()->user()->can('role_add')) {

            abort(403, 'Access Forbidden.');
        }

        return view('users.roles.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('role_add')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'role_name' => 'required|unique:roles,name',
        ]);

        $this->roleService->addRole($request);

        session()->flash('successMsg', __('Role added successfully'));
        return redirect()->route('users.role.index');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('user_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'role_name' => 'required|unique:roles,name,' . $id,
        ]);

        $this->roleService->updateRole(id: $id, request: $request);

        session()->flash('successMsg', __('Role updated successfully'));
        return redirect()->route('users.role.index');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('role_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $role = $this->roleService->singleRole(id: $id);

        return view('users.roles.edit', compact('role'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('role_delete')) {
            abort(403, 'Access Forbidden');
        }

        $deletedRole = $this->roleService->deleteRole($id);
        if ($deletedRole['pass'] == false) {

            return response()->json(['errorMsg' => $deletedRole['msg']]);
        }

        return response()->json(__('Role deleted successfully'));
    }
}
