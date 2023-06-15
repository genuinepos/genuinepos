<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BarcodeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barcode_settings = array(
            array('name' => 'Sticker Print, Continuous feed or rolls , Barcode Size: 38mm X 25mm', 'description' => NULL, 'is_continuous' => '1', 'top_margin' => '0.0000', 'left_margin' => '0.0000', 'sticker_width' => '2.0000', 'sticker_height' => '0.5000', 'paper_width' => '1.8000', 'paper_height' => '0.9843', 'row_distance' => '0.0000', 'column_distance' => '0.0000', 'stickers_in_a_row' => '1', 'stickers_in_one_sheet' => '1', 'is_default' => '0', 'is_fixed' => '1', 'created_at' => NULL, 'updated_at' => '2022-12-05 10:50:05'),
            array('name' => 'Bulk - A4 Page', 'description' => NULL, 'is_continuous' => '0', 'top_margin' => '0.2000', 'left_margin' => '0.0000', 'sticker_width' => '1.5000', 'sticker_height' => '0.8000', 'paper_width' => '8.0000', 'paper_height' => '11.0000', 'row_distance' => '0.2000', 'column_distance' => '0.2000', 'stickers_in_a_row' => '1', 'stickers_in_one_sheet' => '1', 'is_default' => '0', 'is_fixed' => '1', 'created_at' => NULL, 'updated_at' => '2022-12-05 10:50:05'),
        );
        DB::table('barcode_settings')->insert($barcode_settings);
    }
}
