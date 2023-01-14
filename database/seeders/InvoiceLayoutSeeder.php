<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoice_layouts = array(
            array('name' => 'Default layout','layout_design' => '1','show_shop_logo' => '1','header_text' => NULL,'is_header_less' => '0','gap_from_top' => NULL,'show_seller_info' => '1','customer_name' => '1','customer_tax_no' => '0','customer_address' => '1','customer_phone' => '1','sub_heading_1' => NULL,'sub_heading_2' => NULL,'sub_heading_3' => NULL,'invoice_heading' => 'BILL','quotation_heading' => 'Quotation','draft_heading' => 'Draft','challan_heading' => 'Challan','branch_landmark' => '0','branch_city' => '1','branch_state' => '1','branch_country' => '1','branch_zipcode' => '1','branch_phone' => '1','branch_alternate_number' => '1','branch_email' => '1','product_img' => '0','product_cate' => '0','product_brand' => '0','product_imei' => '1','product_w_type' => '1','product_w_duration' => '1','product_w_discription' => '0','product_discount' => '1','product_tax' => '1','product_price_inc_tax' => '0','product_price_exc_tax' => '0','invoice_notice' => 'If you need any support, Feel free to contact. phone: 9561646, 088-7165665 Mobile : 01819220726','sale_note' => '0','show_total_in_word' => '1','footer_text' => NULL,'bank_name' => NULL,'bank_branch' => NULL,'account_name' => NULL,'account_no' => NULL,'is_default' => '1','created_at' => '2021-03-02 18:24:36','updated_at' => '2022-12-17 12:54:53'),
            array('name' => 'Pos Printer Layout','layout_design' => '2','show_shop_logo' => '1','header_text' => NULL,'is_header_less' => '0','gap_from_top' => NULL,'show_seller_info' => '1','customer_name' => '1','customer_tax_no' => '1','customer_address' => '1','customer_phone' => '1','sub_heading_1' => NULL,'sub_heading_2' => NULL,'sub_heading_3' => NULL,'invoice_heading' => 'Invoice','quotation_heading' => 'Quotation','draft_heading' => 'Draft','challan_heading' => 'Challan','branch_landmark' => '1','branch_city' => '1','branch_state' => '1','branch_country' => '0','branch_zipcode' => '1','branch_phone' => '1','branch_alternate_number' => '1','branch_email' => '1','product_img' => '0','product_cate' => '0','product_brand' => '0','product_imei' => '0','product_w_type' => '1','product_w_duration' => '1','product_w_discription' => '0','product_discount' => '1','product_tax' => '1','product_price_inc_tax' => '0','product_price_exc_tax' => '0','invoice_notice' => 'Invoice Notice','sale_note' => '0','show_total_in_word' => '1','footer_text' => 'Footer Text','bank_name' => 'ff','bank_branch' => 'ff','account_name' => 'ff','account_no' => 'ff','is_default' => '0','created_at' => '2021-03-03 16:20:30','updated_at' => '2022-09-08 10:37:02')
          );
        DB::table('invoice_layouts')->insert($invoice_layouts);
    }
}
