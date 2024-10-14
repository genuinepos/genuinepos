<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Setups\BarcodeSetting;

class BarcodeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $barcodeSettings = [
            array('id' => '1', 'name' => 'Sticker Print, Page/Sticker Size: 1.5 in X 1 in (38.1 mm * 25.4 mm)', 'description' => NULL, 'is_continuous' => '1', 'company_name_size' => '9', 'company_name_bold_or_regular' => '1', 'product_code_size' => '8', 'product_name_size' => '8', 'product_name_bold_or_regular' => '0', 'price_size' => '9', 'price_bold_or_regular' => '1', 'bar_width' => '90', 'bar_height' => '40', 'top_margin' => '3', 'left_margin' => '7', 'right_margin' => '7', 'paper_width' => '1.5', 'paper_height' => '1', 'row_distance' => '0.0000', 'column_distance' => '0.0000', 'stickers_in_a_row' => '1', 'stickers_in_one_sheet' => '1', 'is_default' => '1', 'is_fixed' => '0', 'created_at' => NULL, 'updated_at' => '2022-12-05 10:50:05'),
            array('id' => '2', 'name' => 'Bulk - A4 Page', 'description' => NULL, 'is_continuous' => '0', 'company_name_size' => NULL, 'company_name_bold_or_regular' => '1', 'product_code_size' => NULL, 'product_name_size' => NULL, 'product_name_bold_or_regular' => '0', 'price_size' => NULL, 'price_bold_or_regular' => '1', 'bar_width' => '20', 'bar_height' => '40', 'top_margin' => '0.2000', 'left_margin' => '0', 'right_margin' => '0', 'paper_width' => '8.0000', 'paper_height' => '11.0000', 'row_distance' => '0.2000', 'column_distance' => '0.2000', 'stickers_in_a_row' => '1', 'stickers_in_one_sheet' => '1', 'is_default' => '0', 'is_fixed' => '1', 'created_at' => NULL, 'updated_at' => '2022-12-05 10:50:05'),
            array('id' => '3', 'name' => 'Sticker Print, Page/Sticker Size: 1.2 in X 0.80 in (30.48 mm * 20.32 mm)', 'description' => NULL, 'is_continuous' => '1', 'company_name_size' => '7', 'company_name_bold_or_regular' => '1', 'product_code_size' => '7', 'product_name_size' => '7', 'product_name_bold_or_regular' => '0', 'price_size' => '8', 'price_bold_or_regular' => '1', 'bar_width' => '95', 'bar_height' => '20', 'top_margin' => '8', 'left_margin' => '5', 'right_margin' => '5', 'paper_width' => '1.2', 'paper_height' => '0.80', 'row_distance' => '0.0000', 'column_distance' => '0.0000', 'stickers_in_a_row' => '0', 'stickers_in_one_sheet' => '0', 'is_default' => '0', 'is_fixed' => '0', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '4', 'name' => 'Sticker Print, Page/Sticker Size: 1.4 in X 1 in (35.56 mm * 25.4 mm)', 'description' => NULL, 'is_continuous' => '1', 'company_name_size' => '8', 'company_name_bold_or_regular' => '1', 'product_code_size' => '7', 'product_name_size' => '8', 'product_name_bold_or_regular' => '0', 'price_size' => '9', 'price_bold_or_regular' => '1', 'bar_width' => '95', 'bar_height' => '30', 'top_margin' => '8', 'left_margin' => '7', 'right_margin' => '7', 'paper_width' => '1.40', 'paper_height' => '1.00', 'row_distance' => '0.0000', 'column_distance' => '0.0000', 'stickers_in_a_row' => '0', 'stickers_in_one_sheet' => '0', 'is_default' => '0', 'is_fixed' => '0', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '5', 'name' => 'Sticker Print, Page/Sticker Size: 1.80 in X 1.50 in (45.72 mm * 38.1 mm)', 'description' => NULL, 'is_continuous' => '1', 'company_name_size' => '10', 'company_name_bold_or_regular' => '1', 'product_code_size' => '9', 'product_name_size' => '10', 'product_name_bold_or_regular' => '0', 'price_size' => '11', 'price_bold_or_regular' => '1', 'bar_width' => '100', 'bar_height' => '40', 'top_margin' => '15', 'left_margin' => '10', 'right_margin' => '10', 'paper_width' => '1.80', 'paper_height' => '1.5', 'row_distance' => '0.0000', 'column_distance' => '0.0000', 'stickers_in_a_row' => '0', 'stickers_in_one_sheet' => '0', 'is_default' => '0', 'is_fixed' => '0', 'created_at' => NULL, 'updated_at' => NULL)
        ];

        ////////////// Only for update
        // foreach ($barcodeSettings as $barcodeSetting) {

        //     $exists = DB::table()->where('id', $barcodeSetting['id'])->first();

        //     if (isset($exists)) {

        //         DB::table('barcode_settings')->insert($barcodeSetting);
        //     }
        // }

        ///////////// Only for start tenant
        BarcodeSetting::truncate();
        DB::table('barcode_settings')->insert($barcodeSettings);
    }
}
