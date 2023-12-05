<?php

namespace App\Services\Users;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Enums\BooleanType;
use Yajra\DataTables\Facades\DataTables;

class RoleService
{
    public function rolesTable(): object
    {
        $roles = $this->roles()->get();

        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('role_edit')) {

                    $html .= '<a href="' . route('users.role.edit', $row->id) . '" class="action-btn c-edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('role_delete')) {

                    $html .= '<a href="' . route('users.role.delete', $row->id) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                }

                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action'])->smart(true)->make(true);
    }


    public function addRole(object $request): void
    {
        $data = $request->except('_token', 'role_name');

        \array_walk($data, function (&$v, $k) {

            $v = (strtolower($v) == 'on' || $v == '1') ? BooleanType::True->value : BooleanType::False->value;
        });

        $updatedPermission = array_keys($data);

        $appPermissionArray = (new \Database\Seeders\RolePermissionSeeder)->getPermissionsArray();
        $appPermissionArray = array_column($appPermissionArray, 'name');
        // dd(\array_diff($updatedPermission, $appPermissionArray));
        // dd(\array_diff($appPermissionArray, $updatedPermission));
        // dd($updatedPermission, $appPermissionArray);

        $updateRole = new Role();
        $updateRole->name = $request->role_name;
        $updateRole->save();
        $updateRole->syncPermissions($updatedPermission);
    }

    public function updateRole(int $id, object $request): void
    {
        $data = $request->except('_token', 'role_name');
        \array_walk($data, function (&$v, $k) {
            $v = (strtolower($v) == 'on' || $v == '1') ? BooleanType::True->value : BooleanType::False->value;
        });

        $updatedPermission = array_keys($data);

        $updateRole = $this->singleRole(id: $id);
        $updateRole->name = $request->role_name;
        $updateRole->save();
        $updateRole->syncPermissions($updatedPermission);
    }

    public function singleRole(?int $id, array $with = null)
    {
        $query = Role::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function roles(array $with = null)
    {
        $query = Role::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function deleteRole($id): array
    {
        $deleteRole = $this->singleRole(id: $id);

        if (isset($deleteRole)) {

            $associationCount = User::role($deleteRole)->count();

            if ($associationCount > 0) {

                return ['pass' => false, 'msg' => __('Role can\'t be deleted. This role is assigned to one or many user.')];
            }

            $deleteRole->delete();
        }

        return ['pass' => true];
    }
}
