<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LicenseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(FeatureTableSeeder::class);
        $this->call(PlanTableSeeder::class);
        $this->call(PlanFeatureTableSeeder::class);

    }
}
