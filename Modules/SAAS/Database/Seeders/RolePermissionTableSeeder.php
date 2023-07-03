<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Permission;
use Modules\SAAS\Entities\Role;

class RolePermissionTableSeeder extends Seeder
{
    public function run()
    {
        foreach ($this->rolesArray() as $role) {
            Role::create(['name' => $role]);
        }
        foreach ($this->permissionsArray() as $permission) {
            Permission::create(['name' => $permission]);
        }
        $admin = Role::first();
        $admin->syncPermissions(Permission::pluck('name'));
    }

    private function rolesArray(): array
    {
        return [
            'Admin',
            'Customer',
            'Reseller',
        ];
    }

    private function permissionsArray(): array
    {
        return [
            'tenants_index',
            'tenants_create',
            'tenants_show',
            'tenants_update',
            'tenants_delete',
            'users_index',
            'users_create',
            'users_show',
            'users_update',
            'users_delete',
        ];
    }
}
