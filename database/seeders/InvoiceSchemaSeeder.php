<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSchemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoice_schemas = array(
            array('name' => 'yyyy','format' => '1','start_from' => '11','number_of_digit' => NULL,'is_default' => '0','prefix' => 'SDC0','created_at' => '2021-03-02 14:07:36','updated_at' => '2022-11-21 13:21:36'),
            array('name' => 'sss','format' => '1','start_from' => '00','number_of_digit' => NULL,'is_default' => '1','prefix' => 'SD','created_at' => '2021-03-02 14:56:49','updated_at' => '2022-12-31 16:02:52'),
            array('name' => 'test','format' => '1','start_from' => NULL,'number_of_digit' => NULL,'is_default' => '0','prefix' => 'MC','created_at' => '2021-06-06 18:02:32','updated_at' => '2022-12-31 16:02:52'),
            array('name' => 'TEST-4','format' => '2','start_from' => '12','number_of_digit' => NULL,'is_default' => '0','prefix' => '2021/','created_at' => '2021-08-16 17:08:29','updated_at' => '2022-12-06 13:59:19'),
            array('name' => 'EVENT','format' => '2','start_from' => '10','number_of_digit' => NULL,'is_default' => '0','prefix' => '2022/','created_at' => '2022-11-21 13:22:00','updated_at' => '2022-11-26 10:24:38'),
            array('name' => 'Exclusive','format' => '2','start_from' => '10','number_of_digit' => NULL,'is_default' => '0','prefix' => '2022/','created_at' => '2022-11-23 15:41:15','updated_at' => '2022-11-23 15:41:15')
          );
          DB::table('invoice_schemas')->insert($invoice_schemas);
    }
}
