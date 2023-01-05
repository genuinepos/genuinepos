<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            array('name' => '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 0.55\'\', Barcode 20 Per Sheet','description' => '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 0.55\'\', Barcode 20 Per Sheet','is_continuous' => '0','top_margin' => '0.1200','left_margin' => '0.1200','sticker_width' => '4.0000','sticker_height' => '0.5500','paper_width' => '8.5000','paper_height' => '11.0000','row_distance' => '1.0000','column_distance' => '1.0000','stickers_in_a_row' => '10','stickers_in_one_sheet' => '20','is_default' => '0','is_fixed' => '1','created_at' => NULL,'updated_at' => '2021-07-01 17:09:33'),
            array('name' => 'Sticker Print, Continuous feed or rolls , Barcode Size: 38mm X 25mm','description' => NULL,'is_continuous' => '1','top_margin' => '0.0000','left_margin' => '0.0000','sticker_width' => '2.0000','sticker_height' => '0.5000','paper_width' => '1.8000','paper_height' => '0.9843','row_distance' => '0.0000','column_distance' => '0.0000','stickers_in_a_row' => '1','stickers_in_one_sheet' => '1','is_default' => '0','is_fixed' => '1','created_at' => NULL,'updated_at' => '2022-12-05 10:50:05'),
            array('name' => '40 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2\'\' * 0.39\'\', Barcode 40 Per Sheet','description' => NULL,'is_continuous' => '0','top_margin' => '0.3000','left_margin' => '0.1000','sticker_width' => '2.0000','sticker_height' => '0.3900','paper_width' => '8.5000','paper_height' => '11.0000','row_distance' => '0.0000','column_distance' => '0.0000','stickers_in_a_row' => '10','stickers_in_one_sheet' => '30','is_default' => '0','is_fixed' => '1','created_at' => NULL,'updated_at' => '2021-07-01 17:55:53'),
            array('name' => '30 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2.4\'\' * 0.55\'\', Barcode 30 Per Sheet','description' => NULL,'is_continuous' => '0','top_margin' => '0.1000','left_margin' => '0.1000','sticker_width' => '2.4000','sticker_height' => '0.5500','paper_width' => '8.5000','paper_height' => '11.0000','row_distance' => '0.0000','column_distance' => '0.0000','stickers_in_a_row' => '30','stickers_in_one_sheet' => '30','is_default' => '0','is_fixed' => '1','created_at' => NULL,'updated_at' => '2021-07-01 18:05:57'),
            array('name' => 'barcode','description' => 'hello world','is_continuous' => '0','top_margin' => '10.0000','left_margin' => '100.0000','sticker_width' => '3.0000','sticker_height' => '5.0000','paper_width' => '3.0000','paper_height' => '10.0000','row_distance' => '10.0000','column_distance' => '1.0000','stickers_in_a_row' => '1','stickers_in_one_sheet' => '1','is_default' => '0','is_fixed' => '0','created_at' => NULL,'updated_at' => '2022-12-05 11:17:57'),
            array('name' => 'Demo 1','description' => 'Demo description','is_continuous' => '1','top_margin' => '5.0000','left_margin' => '0.0000','sticker_width' => '5.0000','sticker_height' => '5.0000','paper_width' => '5.0000','paper_height' => '5.0000','row_distance' => '0.0000','column_distance' => '0.0000','stickers_in_a_row' => '5','stickers_in_one_sheet' => '5','is_default' => '1','is_fixed' => '0','created_at' => NULL,'updated_at' => '2022-12-05 11:34:57'),
            array('name' => 'New one','description' => 'Aliquip sed eius vol','is_continuous' => '1','top_margin' => '35.0000','left_margin' => '49.0000','sticker_width' => '2.0000','sticker_height' => '83.0000','paper_width' => '54.0000','paper_height' => '36.0000','row_distance' => '4.0000','column_distance' => '41.0000','stickers_in_a_row' => '45','stickers_in_one_sheet' => '62','is_default' => '0','is_fixed' => '0','created_at' => NULL,'updated_at' => '2022-12-05 11:34:57'),
            array('name' => 'Later one','description' => 'Sit quos et repudia','is_continuous' => '0','top_margin' => '30.0000','left_margin' => '30.0000','sticker_width' => '63.0000','sticker_height' => '29.0000','paper_width' => '32.0000','paper_height' => '90.0000','row_distance' => '38.0000','column_distance' => '4.0000','stickers_in_a_row' => '96','stickers_in_one_sheet' => '5','is_default' => '0','is_fixed' => '0','created_at' => NULL,'updated_at' => NULL),
            array('name' => 'Daniel Bryan','description' => 'Quos et deserunt aut','is_continuous' => '0','top_margin' => '47.0000','left_margin' => '40.0000','sticker_width' => '29.0000','sticker_height' => '36.0000','paper_width' => '89.0000','paper_height' => '79.0000','row_distance' => '70.0000','column_distance' => '66.0000','stickers_in_a_row' => '59','stickers_in_one_sheet' => '8','is_default' => '0','is_fixed' => '0','created_at' => NULL,'updated_at' => '2022-12-05 11:15:21')
          );
          DB::table('barcode_settings')->insert($barcode_settings);
    }
}
