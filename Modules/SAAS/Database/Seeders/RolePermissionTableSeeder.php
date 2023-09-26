<?php

namespace Modules\SAAS\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Permission;
use Modules\SAAS\Entities\Role;

class RolePermissionTableSeeder extends Seeder
{
    public function run()
    {
        foreach ($this->rolesArray() as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        foreach ($this->permissionsArray() as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        $adminRole = User::whereName('admin')->first() ?? Role::first();
        $adminRole->syncPermissions(Permission::pluck('name'));

        $adminUser = User::whereEmail('admin@gmail.com')->first() ?? User::first();
        $adminUser->assignRole($adminRole);
    }

    public function rolesArray(): array
    {
        return [
            'admin',
            'customer',
            'reseller',
        ];
    }

    public function permissionsArray(): array
    {
        return [
            // Business or Tenant
            'tenants_index',
            'tenants_create',
            'tenants_store',
            'tenants_show',
            'tenants_update',
            'tenants_destroy',
            // User
            'users_index',
            'users_create',
            'users_store',
            'users_show',
            'users_update',
            'users_destroy',
            'users_trash',
            'users_restore',
            // Plan
            'plans_index',
            'plans_create',
            'plans_store',
            'plans_show',
            'plans_update',
            'plans_destroy',
            // Profile
            'profile_edit',
        ];
    }
}
