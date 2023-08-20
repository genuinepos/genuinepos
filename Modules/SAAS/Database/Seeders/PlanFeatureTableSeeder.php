<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\SAAS\Entities\Plan;
use Modules\SAAS\Entities\Feature;

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
       $plans->map(function($plan) use($features) {
            $plan->features()->sync($features);
       });
    }
}
