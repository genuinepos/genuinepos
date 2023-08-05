<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        if (! auth()->user()->can('role_view')) {
            abort(403, 'Access Forbidden.');
        }

        return view('users.roles.index');
    }

    public function allRoles()
    {
        if (! auth()->user()->can('role_view')) {
            abort(403, 'Access Forbidden.');
        }
        $roles = Role::all();

        return view('users.roles.ajax_view.role_list', compact('roles'));
    }

    public function create()
    {
        if (! auth()->user()->can('role_add')) {
            abort(403, 'Access Forbidden.');
        }

        return view('users.roles.create');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('role_add')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'role_name' => 'required|unique:roles,name',
        ]);

        $data = $request->except('_token', 'role_name');

        \array_walk($data, function (&$v, $k) {
            $v = (strtolower($v) == 'on' || $v == '1') ? 1 : 0;
        });

        $updatedPermission = array_keys($data);

        $updateRole = new Role();
        $updateRole->name = $request->role_name;
        $updateRole->save();
        $updateRole->syncPermissions($updatedPermission);
        session()->flash('successMsg', 'Successfully Added!');

        return redirect()->route('users.role.index');
    }

    public function update(Request $request, $roleId)
    {
        if (! auth()->user()->can('user_edit')) {
            abort(403, 'Access Forbidden.');
        }
        // return $request->all();
        $this->validate($request, [
            'role_name' => 'required|unique:roles,name,'.$roleId,
        ]);
        $data = $request->except('_token', 'role_name');
        \array_walk($data, function (&$v, $k) {
            $v = (strtolower($v) == 'on' || $v == '1') ? 1 : 0;
        });

        $updatedPermission = array_keys($data);

        $updateRole = Role::where('id', $roleId)->first();
        $updateRole->name = $request->role_name;
        $updateRole->save();
        $updateRole->syncPermissions($updatedPermission);
        session()->flash('successMsg', 'Successfully Updated!');

        return redirect()->route('users.role.index');
    }

    public function edit($roleId)
    {
        if (! auth()->user()->can('role_edit')) {
            abort(403, 'Access Forbidden.');
        }
        $role = Role::where('id', $roleId)->firstOrFail();

        return view('users.roles.edit', compact('role'));
    }

    public function delete(Request $request, $roleId)
    {
        if (! auth()->user()->can('role_delete')) {
            abort(403, 'Access Forbidden');
        }

        $deleteRole = Role::find($roleId);
        if (! is_null($deleteRole)) {
            $deleteRole->delete();
        }

        return response()->json('Successfully Deleted!');
    }
}
