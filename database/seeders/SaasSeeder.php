<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $t1 = Tenant::create(['id' => 'customer1']);
        $t2 = Tenant::create(['id' => 'customer2']);
        $t3 = Tenant::create(['id' => 'customer3']);
        $t4 = Tenant::create(['id' => 'customer4']);
        $t5 = Tenant::create(['id' => 'gposs']);

        $t1->domains()->create(['domain' => 'customer1']);
        $t2->domains()->create(['domain' => 'customer2']);
        $t3->domains()->create(['domain' => 'customer3']);
        $t4->domains()->create(['domain' => 'customer4']);
        $t5->domains()->create(['domain' => 'gposs.test']);

        Tenant::all()->runForEach(function () {
            Artisan::call('migrate');
            Artisan::call('db:seed');
        });
    }
}
