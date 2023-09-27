<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Plan;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::factory()->create(['name' => 'Gold','slug' => 'gold', 'price' => '1000', 'period_unit' => 'month', 'period_value' => '1']);
        Plan::factory()->create(['name' => 'Diamond','slug' => 'diamond', 'price' => '2000', 'period_unit' => 'month', 'period_value' => '1']);
        Plan::factory()->create(['name' => 'Platinum','slug' => 'platinm', 'price' => '5000', 'period_unit' => 'month', 'period_value' => '1']);
    }
}
