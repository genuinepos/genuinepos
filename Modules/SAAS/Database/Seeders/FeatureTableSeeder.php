<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Feature;
use Illuminate\Database\Eloquent\Model;
use Database\Seeders\RolePermissionSeeder;

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
        foreach($features as $feature) {
            Feature::create(['name' => $feature['name']]);
        }
    }
}
