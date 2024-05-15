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
            ['code' => 'U-001', 'name' => 'Pieces', 'code_name' => 'Pc', 'created_at' => '2020-11-02 10:57:56', 'updated_at' => '2020-11-02 10:57:56'],
            ['code' => 'U-002', 'name' => 'Kilogram', 'code_name' => 'Kg', 'created_at' => '2020-11-03 06:41:16', 'updated_at' => '2020-11-03 06:41:16'],
            ['code' => 'U-003', 'name' => 'Dozen', 'code_name' => 'Dz', 'created_at' => '2020-11-03 06:42:06', 'updated_at' => '2020-12-30 06:26:39'],
            ['code' => 'U-004', 'name' => 'Gram', 'code_name' => 'Gm', 'created_at' => '2020-12-30 09:13:06', 'updated_at' => '2020-12-30 09:13:18'],
            ['code' => 'U-005', 'name' => 'Ton', 'code_name' => 'tn', 'created_at' => '2021-01-19 10:27:58', 'updated_at' => '2021-01-19 10:27:58'],
            ['code' => 'U-006', 'name' => 'Pound', 'code_name' => 'lb', 'created_at' => '2021-01-19 10:29:11', 'updated_at' => '2021-01-19 10:29:11'],
            ['code' => 'U-007', 'name' => 'Liter', 'code_name' => 'lt', 'created_at' => '2021-11-18 18:32:46', 'updated_at' => '2021-11-18 18:32:46'],
            ['code' => 'U-008', 'name' => 'Meter', 'code_name' => 'm', 'created_at' => '2022-11-20 17:46:50', 'updated_at' => '2022-11-20 17:46:50'],
            ['code' => 'U-009', 'name' => 'Millimeter', 'code_name' => 'mm', 'created_at' => '2022-11-20 17:46:50', 'updated_at' => '2022-11-20 17:46:50'],
        ];

        \Illuminate\Support\Facades\DB::table('units')->insert($units);
    }
}
