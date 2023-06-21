<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tenancy\Entities\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class TenancyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $t1 = Tenant::create(['id' => 'customer1']);
        // $t2 = Tenant::create(['id' => 'customer2']);
        // $t5 = Tenant::create(['id' => 'gposs']);

        // $t1->domains()->create(['domain' => 'customer1']);
        // $t2->domains()->create(['domain' => 'customer2']);
        // $t5->domains()->create(['domain' => 'gposs.test']);

        Tenant::all()->runForEach(function () {
            // Artisan::call('migrate');
            Artisan::call('db:seed');
        });
    }
}
