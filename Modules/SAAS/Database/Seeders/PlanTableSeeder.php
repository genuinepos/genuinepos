<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
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
        Plan::factory()->create(['name' => 'Gold', 'price' => '10', 'period_month' => '1']);
        Plan::factory()->create(['name' => 'Diamond', 'price' => '20', 'period_month' => '1']);
        Plan::factory()->create(['name' => 'Platinum', 'price' => '50', 'period_month' => '1']);
    }
}
