<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Role;
use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\SAAS\Http\Requests\RoleStoreRequest;
use Modules\SAAS\Interfaces\RoleServiceInterface;
use Modules\SAAS\Database\Seeders\RolePermissionTableSeeder;

class RoleController extends Controller
{
    public function __construct(
        private RoleServiceInterface $roleServiceInterface,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('roles_index'), 403);
        if ($request->ajax()) {

            return $this->roleServiceInterface->rolesTable();
        }

        return view('saas::roles.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('roles_create'), 403);
        return view('saas::roles.create');
    }

    public function store(RoleStoreRequest $request)
    {
        $this->roleServiceInterface->addRole(request: $request);

        return redirect()->route('saas.roles.index')->with('success', __('Role created successfully!'));
    }

    public function show($id)
    {
        abort_if(!auth()->user()->can('roles_index'), 403);
        return view('saas::roles.show');
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('roles_update'), 403);
        return view('saas::roles.edit', ['role' => $role]);
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        $this->roleServiceInterface->updateRole(id: $id, request: $request);

        return redirect()->route('saas.roles.index')->with('success', 'Role updated successfully!');
    }

    public function delete(Role $role)
    {
        // $role->update(['status' => 0]);
        $role->syncPermissions([]);
        $role->delete();

        return redirect()->route('saas.roles.index')->with('success', 'Role disabled successfully!');
    }
}
