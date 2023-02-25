<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminUser = User::where('username', 'superadmin')->first();
        $superAdminRole = Role::where('name', 'superadmin')->first();
        $superAdminUser?->assignRole($superAdminRole?->name);

        $adminUser = User::where('username', 'admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $adminUser?->assignRole($adminRole?->name);
    }
}
