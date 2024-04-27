<?php

namespace Modules\SAAS\Services;

use App\Models\User;
use App\Enums\BooleanType;
use Modules\SAAS\Entities\Role;
use Yajra\DataTables\Facades\DataTables;
use Modules\SAAS\Interfaces\RoleServiceInterface;
use Modules\SAAS\Database\Seeders\RolePermissionTableSeeder;

class RoleService implements RoleServiceInterface
{
    public function rolesTable(): object
    {
        $roles = Role::query();
        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('roles_update')) {

                    $html .= '<a href="' . route('saas.roles.edit', $row->id) . '" class="px-2 edit-btn btn btn-primary btn-sm text-white" id="editUser" title="Edit"><span class="fas fa-edit pe-1"></span>Edit</a>';
                }

                if (auth()->user()->can('roles_destroy')) {

                    $html .= '<a href="' . route('saas.roles.delete', $row->id) . '" class="px-2 delete-btn btn btn-danger btn-sm text-white ms-2" id="deleteUser" title="Delete"><span class="fas fa-trash pe-1"></span>Delete</a>';
                }

                $html .= '</div>';

                return $html;
            })
            ->make(true);
    }

    public function addRole(object $request): void
    {

        $data = $request->except('_token', 'name');

        \array_walk($data, function (&$v, $k) {

            $v = (strtolower($v) == 'on' || $v == '1') ? BooleanType::True->value : BooleanType::False->value;
        });

        $updatedPermission = array_keys($data);

        $addRole = new Role();
        $addRole->name = $request->name;
        $addRole->save();
        $addRole->syncPermissions($updatedPermission);
    }

    public function updateRole(int $id, object $request): void
    {
        $data = $request->except('_token', '_method', 'name');
        \array_walk($data, function (&$v, $k) {
            $v = (strtolower($v) == 'on' || $v == '1') ? BooleanType::True->value : BooleanType::False->value;
        });

        $updatedPermission = array_keys($data);

        $updateRole = $this->singleRole(id: $id);
        $updateRole->name = $request->name;
        $updateRole->save();
        $updateRole->syncPermissions($updatedPermission);
    }

    public function singleRole(?int $id, array $with = null): ?object
    {
        $query = Role::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function roles(array $with = null): object
    {
        $query = Role::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function deleteRole(int $id): array
    {
        $deleteRole = $this->singleRole(id: $id);

        if (isset($deleteRole)) {

            $associationCount = User::role($deleteRole)->count();

            if ($associationCount > 0) {

                return ['pass' => false, 'msg' => __('Role can\'t be deleted. This role is assigned to one or many users.')];
            }

            $deleteRole->delete();
        }

        return ['pass' => true];
    }
}
