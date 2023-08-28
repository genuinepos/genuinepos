<?php

namespace Modules\SAAS\Database\Seeders;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Feature;

class FeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = (new RolePermissionSeeder)->getPermissionsArray();
        foreach ($features as $feature) {
            Feature::create(['name' => $feature['name']]);
        }
    }
}
