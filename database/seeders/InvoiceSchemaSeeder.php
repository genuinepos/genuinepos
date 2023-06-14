<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

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
            array('name' => 'TEST-1', 'format' => '2', 'start_from' => '12', 'number_of_digit' => NULL, 'is_default' => '0', 'prefix' => '2021/', 'created_at' => '2021-08-16 17:08:29', 'updated_at' => '2022-12-06 13:59:19'),
            array('name' => 'EVENT', 'format' => '2', 'start_from' => '10', 'number_of_digit' => NULL, 'is_default' => '0', 'prefix' => '2022/', 'created_at' => '2022-11-21 13:22:00', 'updated_at' => '2022-11-26 10:24:38'),
            array('name' => 'Exclusive', 'format' => '2', 'start_from' => '10', 'number_of_digit' => NULL, 'is_default' => '0', 'prefix' => '2022/', 'created_at' => '2022-11-23 15:41:15', 'updated_at' => '2022-11-23 15:41:15')
        );
        DB::table('invoice_schemas')->insert($invoice_schemas);
    }
}
