<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \App\Models\Category::factory()->count(10)->create();
        \App\Models\SubCategory::factory()->count(10)->create();
        \App\Models\Brand::factory()->count(5)->create();
        \App\Models\Unit::factory()->count(5)->create();
        \App\Models\Tax::factory()->count(5)->create();
        \App\Models\Warranty::factory()->count(5)->create();
        \App\Models\Product::factory()->count(5)->create();
    }
}
