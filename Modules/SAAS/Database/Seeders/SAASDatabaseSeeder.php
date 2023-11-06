<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Plan;

class SAASDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(RolePermissionTableSeeder::class);
        if (Plan::count() == 0) {
            $this->call(FeatureTableSeeder::class);
            $this->call(PlanTableSeeder::class);
            $this->call(PlanFeatureTableSeeder::class);
        }
    }
}
