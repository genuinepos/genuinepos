<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Http\Request;
use Modules\SAAS\Entities\Role;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\SAAS\Entities\Permission;

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
                    $html .= '<a href="' . route('saas.roles.edit', $row->id) .
                        '" class="px-2 edit-btn btn btn-primary btn-sm text-white" id="editUser" title="Edit"><span class="fas fa-edit pe-1"></span>Edit</a>';
                    $html .= '<a href="' . route('saas.roles.destroy', $row->id) .
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
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('saas::roles.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Role $role)
    {
        return view('saas::roles.edit', [
            'role' => $role,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}