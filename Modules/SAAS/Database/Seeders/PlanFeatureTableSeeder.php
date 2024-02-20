<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Feature;
use Modules\SAAS\Entities\Plan;

class PlanFeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = Plan::all();
        $features = Feature::all()->take(Feature::count() - 20);
        $plans->map(function ($plan) use ($features) {
            $plan->features()->sync($features);
        });
    }
}
