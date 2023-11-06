<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Modules\SAAS\Entities\Permission;
use Modules\SAAS\Entities\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::query();
        if ($request->ajax()) {
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('saas.roles.edit', $row->id).
                        '" class="px-2 edit-btn btn btn-primary btn-sm text-white" id="editUser" title="Edit"><span class="fas fa-edit pe-1"></span>Edit</a>';
                    $html .= '<a href="'.route('saas.roles.destroy', $row->id).
                        '" class="px-2 delete-btn btn btn-danger btn-sm text-white ms-2" id="deleteUser" title="Delete"><span class="fas fa-trash pe-1"></span>Delete</a>';
                    $html .= '</div>';

                    return $html;
                })
                ->make(true);
        }

        return view('saas::roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('saas::roles.create', [
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|min:1',
        ]);

        $permissionsArray = $request->permissions;
        Arr::forget($permissionsArray, 'select_all');
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions(array_keys($permissionsArray));

        return redirect()->route('saas.roles.index')->with('success', 'Role created successfully!');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('saas::roles.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit(Role $role)
    {
        return view('saas::roles.edit', [
            'role' => $role,
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|min:1',
        ]);

        $permissionsArray = $request->permissions;
        Arr::forget($permissionsArray, 'select_all');
        $role->syncPermissions(array_keys($permissionsArray));

        return redirect()->route('saas.roles.index')->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy(Role $role)
    {
        // $role->update(['status' => 0]);
        $role->syncPermissions([]);
        $role->delete();

        return redirect()->route('saas.roles.index')->with('success', 'Role disabled successfully!');
    }
}
