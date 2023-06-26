<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Modules\SAAS\Entities\Tenant;

class TenancyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $domains = ['customer1',  'customer2', 'gposs'];
        $tenants = [];
        foreach ($domains as $key => $domain) {
            $tenants[$key] = Tenant::create(['id' => $domain]);
            $tenants[$key]->domains()->create(['domain' => $domain]);
        }
        Tenant::all()->runForEach(function () {
            Artisan::call('migrate');
            Artisan::call('db:seed');
        });
    }
}
