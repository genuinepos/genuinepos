<?php

namespace Modules\SAAS\Database\Seeders;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Modules\SAAS\Entities\User::factory(10)->create();
    }
}
