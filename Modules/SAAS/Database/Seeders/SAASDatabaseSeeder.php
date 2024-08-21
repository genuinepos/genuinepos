<?php

namespace Modules\SAAS\Database\Seeders;

use App\Models\User;
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
        // if (User::count() === 0) {
        //     $this->call(UserTableSeeder::class);
        //     $this->call(RolePermissionTableSeeder::class);
        // }

        // if (Plan::count() == 0) {
        //     $this->call(PlanTableSeeder::class);
        //     $this->call(CurrencyDatabaseSeedSeeder::class);
        // }

        $this->call(UserTableSeeder::class);
        $this->call(RolePermissionTableSeeder::class);
        $this->call(PlanTableSeeder::class);
        $this->call(CurrencyDatabaseSeedSeeder::class);
    }
}
