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
        $adminRole = Role::first();
        $adminRole->syncPermissions(Permission::pluck('name'));

        $adminUser = User::where('email', 'admin@gmail.com')->first();
        if (! isset($adminUser)) {
            $adminUser = $this->makeAnAdmin();
        }
        if (isset($adminUser)) {
            $adminUser->assignRole($adminRole);
        }
    }

    private function makeAnAdmin(): User
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        return $user;
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
            // Business or Tenant
            'tenants_index',
            'tenants_create',
            'tenants_show',
            'tenants_update',
            'tenants_delete',
            // User
            'users_index',
            'users_create',
            'users_show',
            'users_update',
            'users_delete',
            // Profile
            'profile_edit',
            // Plan
            'plans_index',
            'plans_create',
            'plans_show',
            'plans_update',
            'plans_delete',
        ];
    }
}
