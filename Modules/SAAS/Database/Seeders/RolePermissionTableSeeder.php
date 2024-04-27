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

            $role = Role::where('name', $role)->first();

            if (!isset($role)) {

                Role::create(['name' => $role, 'guard_name' => 'web']);
            }
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
        ];
    }

    public function permissionsArray(): array
    {
        return [
            ['id' => '1', 'name' => 'tenants_index'],
            ['id' => '2', 'name' => 'tenants_create'],
            ['id' => '3', 'name' => 'tenants_show'],
            ['id' => '4', 'name' => 'tenants_destroy'],

            ['id' => '5', 'name' => 'users_index'],
            ['id' => '6', 'name' => 'users_create'],
            ['id' => '7', 'name' => 'users_show'],
            ['id' => '8', 'name' => 'users_update'],
            ['id' => '9', 'name' => 'users_destroy'],

            ['id' => '10', 'name' => 'roles_index'],
            ['id' => '11', 'name' => 'roles_create'],
            ['id' => '13', 'name' => 'roles_update'],
            ['id' => '14', 'name' => 'roles_destroy'],

            ['id' => '15', 'name' => 'plans_index'],
            ['id' => '16', 'name' => 'plans_create'],
            ['id' => '17', 'name' => 'plans_update'],
            ['id' => '18', 'name' => 'plans_destroy'],

            ['id' => '19', 'name' => 'profile_edit'],

            ['id' => '20', 'name' => 'coupons_index'],
            ['id' => '21', 'name' => 'coupons_create'],
            ['id' => '22', 'name' => 'coupons_update'],
            ['id' => '23', 'name' => 'coupons_destroy'],
        ];
    }
}
