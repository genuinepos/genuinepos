<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;

class SAASDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolePermissionTableSeeder::class);
    }
}
