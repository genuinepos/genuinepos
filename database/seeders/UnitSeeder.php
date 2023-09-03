<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['name' => 'Piece', 'code_name' => 'PC', 'created_at' => '2020-11-02 10:57:56', 'updated_at' => '2020-11-02 10:57:56'],
            ['name' => 'Kilogram', 'code_name' => 'KG', 'created_at' => '2020-11-03 06:41:16', 'updated_at' => '2020-11-03 06:41:16'],
            ['name' => 'Dozon', 'code_name' => 'DZ', 'created_at' => '2020-11-03 06:42:06', 'updated_at' => '2020-12-30 06:26:39'],
            ['name' => 'KG', 'code_name' => 'KG', 'created_at' => '2020-11-03 06:42:06', 'updated_at' => '2020-12-30 06:26:39'],
            ['name' => 'Gram', 'code_name' => 'GM', 'created_at' => '2020-12-30 09:13:06', 'updated_at' => '2020-12-30 09:13:18'],
            ['name' => 'Ton', 'code_name' => 'TN', 'created_at' => '2021-01-19 10:27:58', 'updated_at' => '2021-01-19 10:27:58'],
            ['name' => 'Pound', 'code_name' => 'PND', 'created_at' => '2021-01-19 10:29:11', 'updated_at' => '2021-01-19 10:29:11'],
            ['name' => 'Unit', 'code_name' => 'UT', 'created_at' => '2021-07-15 12:08:10', 'updated_at' => '2021-07-15 12:08:10'],
            ['name' => 'Item', 'code_name' => 'ITM', 'created_at' => '2021-07-15 12:53:29', 'updated_at' => '2021-07-15 12:53:29'],
            ['name' => 'Litter', 'code_name' => '1', 'created_at' => '2021-11-18 18:32:46', 'updated_at' => '2021-11-18 18:32:46'],
            ['name' => 'Miter', 'code_name' => 'MI', 'created_at' => '2022-11-20 17:46:50', 'updated_at' => '2022-11-20 17:46:50'],
        ];
        \Illuminate\Support\Facades\DB::table('units')->insert($units);
    }
}
