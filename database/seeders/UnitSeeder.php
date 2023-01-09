<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $units = array(
            array('name' => 'Piece','code_name' => 'PC','dimension' => '1 Per piece','created_at' => '2020-11-02 10:57:56','updated_at' => '2020-11-02 10:57:56'),
            array('name' => 'Kilogram','code_name' => 'KG','dimension' => '100 Kilogram = 1 KG','created_at' => '2020-11-03 06:41:16','updated_at' => '2020-11-03 06:41:16'),
            array('name' => 'Dozon','code_name' => 'DZ','dimension' => '12 Pieces = 1 DZ','created_at' => '2020-11-03 06:42:06','updated_at' => '2020-12-30 06:26:39'),
            array('name' => 'KG','code_name' => 'KG','dimension' => '12 Pieces = 1 DZ','created_at' => '2020-11-03 06:42:06','updated_at' => '2020-12-30 06:26:39'),
            array('name' => 'Gram','code_name' => 'GM','dimension' => '1','created_at' => '2020-12-30 09:13:06','updated_at' => '2020-12-30 09:13:18'),
            array('name' => 'Ton','code_name' => 'TN','dimension' => NULL,'created_at' => '2021-01-19 10:27:58','updated_at' => '2021-01-19 10:27:58'),
            array('name' => 'Pound','code_name' => 'PND','dimension' => NULL,'created_at' => '2021-01-19 10:29:11','updated_at' => '2021-01-19 10:29:11'),
            array('name' => 'Unit','code_name' => 'UT','dimension' => NULL,'created_at' => '2021-07-15 12:08:10','updated_at' => '2021-07-15 12:08:10'),
            array('name' => 'Item','code_name' => 'ITM','dimension' => NULL,'created_at' => '2021-07-15 12:53:29','updated_at' => '2021-07-15 12:53:29'),
            array('name' => 'Litter','code_name' => '1','dimension' => NULL,'created_at' => '2021-11-18 18:32:46','updated_at' => '2021-11-18 18:32:46'),
            array('name' => 'Miter','code_name' => 'MI','dimension' => NULL,'created_at' => '2022-11-20 17:46:50','updated_at' => '2022-11-20 17:46:50'),
          );
        \DB::table('units')->insert($units);
    }
}
