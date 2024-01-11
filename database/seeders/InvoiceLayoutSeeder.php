<?php

namespace Database\Seeders;

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
        $invoiceLayout = array('id' => '1', 'branch_id' => NULL, 'name' => 'Default layout', 'page_size' => '1', 'show_shop_logo' => '1', 'header_text' => NULL, 'is_header_less' => '0', 'gap_from_top' => NULL, 'customer_name' => '1', 'customer_tax_no' => '0', 'customer_address' => '1', 'customer_phone' => '1', 'sub_heading_1' => NULL, 'sub_heading_2' => NULL, 'sub_heading_3' => NULL, 'invoice_heading' => 'Invoice', 'quotation_heading' => 'Quotation', 'challan_heading' => 'Challan', 'branch_city' => '1', 'branch_state' => '1', 'branch_country' => '1', 'branch_zipcode' => '1', 'branch_phone' => '1', 'branch_alternate_number' => '1', 'branch_email' => '1', 'product_imei' => '1', 'product_w_type' => '1', 'product_w_duration' => '1', 'product_w_discription' => '0', 'product_discount' => '1', 'product_tax' => '1', 'product_price_inc_tax' => '0', 'product_price_exc_tax' => '0', 'invoice_notice' => 'Thanks for buying from us', 'sale_note' => '0', 'show_total_in_word' => '1', 'footer_text' => NULL, 'bank_name' => NULL, 'bank_branch' => NULL, 'account_name' => NULL, 'account_no' => NULL, 'is_default' => '1', 'created_at' => '2021-03-02 18:24:36', 'updated_at' => '2023-12-03 17:50:35', 'sales_order_heading' => 'Sales Order');

        DB::table('invoice_layouts')->insert($invoiceLayout);
    }
}
