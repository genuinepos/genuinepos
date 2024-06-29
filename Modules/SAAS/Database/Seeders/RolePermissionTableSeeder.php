<?php

namespace Modules\SAAS\Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Role;
use Modules\SAAS\Entities\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class RolePermissionTableSeeder extends Seeder
{
    public function run()
    {
        Artisan::call('optimize:clear');
        Schema::disableForeignKeyConstraints();

        if (Role::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `roles` AUTO_INCREMENT = 1');
        }

        Permission::truncate();
        if (Permission::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `permissions` AUTO_INCREMENT = 1');
        }

        Schema::enableForeignKeyConstraints();

        foreach ($this->rolesArray() as $role) {

            $role = Role::where('name', $role)->first();

            if (!isset($role)) {

                Role::create(['id' => 1, 'name' => $role, 'guard_name' => 'web']);
            }
        }

        foreach ($this->permissionsArray() as $permission) {

            Permission::UpdateOrCreate([
                'id' => $permission['id'],
                'name' => $permission['name']
            ]);
        }

        $adminRole = User::whereName('admin')->first() ?? Role::first();
        $adminRole->syncPermissions(Permission::pluck('name'));

        $adminUser = User::whereEmail('admin@gmail.com')->first() ?? User::first();
        $adminUser->assignRole($adminRole);
    }

    public function rolesArray(): array
    {
        return ['admin'];
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

            ['id' => '24', 'name' => 'tenants_upgrade_plan'],
            ['id' => '25', 'name' => 'tenants_update_payment_status'],
            ['id' => '26', 'name' => 'tenants_update_expire_date'],
        ];
    }
}
