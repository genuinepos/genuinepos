<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class GeneralSettingsSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $general_settings = array(
            array('id' => '1','business' => '{"shop_name":"Genuine POS","address":"Gausia Kasem Center 10\\/2 Arambagh (7th floor), Motijheel-1000","phone":"01970133444","email":"info@genuinepos.com","start_date":"07-04-2021","default_profit":0,"currency":"\\u09f3","currency_placement":null,"date_format":"d-m-Y","financial_year_start":"Januaray","time_format":"12","business_logo":"61306cda1e984-.png","timezone":"Asia\\/Dhaka"}','tax' => '{"tax_1_name":"Tax","tax_1_no":"1","tax_2_name":"GST","tax_2_no":"2","is_tax_en_purchase_sale":1}','product' => '{"product_code_prefix":null,"default_unit_id":"3","is_enable_brands":1,"is_enable_categories":1,"is_enable_sub_categories":1,"is_enable_price_tax":0,"is_enable_warranty":1}','sale' => '{"default_sale_discount":"0.00","default_tax_id":"null","sales_cmsn_agnt":"select_form_cmsn_list","default_price_group_id":"7"}','pos' => '{"is_disable_draft":0,"is_disable_quotation":0,"is_disable_challan":0,"is_disable_hold_invoice":0,"is_disable_multiple_pay":1,"is_show_recent_transactions":0,"is_disable_discount":0,"is_disable_order_tax":0,"is_show_credit_sale_button":1,"is_show_partial_sale_button":1}','purchase' => '{"is_edit_pro_price":0,"is_enable_status":1,"is_enable_lot_no":1}','dashboard' => '{"view_stock_expiry_alert_for":"31"}','system' => '[]','prefix' => '{"purchase_invoice":"PI","sale_invoice":"SI","purchase_return":"PRI","stock_transfer":"STI","stock_djustment":"SAR","sale_return":"SRI","expenses":"EXI","supplier_id":"SID","customer_id":null,"purchase_payment":"PPI","sale_payment":"SPI","expanse_payment":"EXPI"}','send_es_settings' => '{"send_inv_via_email":0,"send_notice_via_sms":0,"cmr_due_rmdr_via_email":0,"cmr_due_rmdr_via_sms":0}','email_setting' => '[]','sms_setting' => '[]','modules' => '{"purchases":1,"add_sale":1,"pos":1,"transfer_stock":1,"stock_adjustment":1,"expenses":1,"accounting":1,"contacts":1,"hrms":1,"requisite":1}','reward_poing_settings' => '{"enable_cus_point":1,"point_display_name":"Reward Point","amount_for_unit_rp":"10","min_order_total_for_rp":"100","max_rp_per_order":"","redeem_amount_per_unit_rp":"0.10","min_order_total_for_redeem":"","min_redeem_point":"","max_redeem_point":""}','multi_branches' => '0','hrm' => '0','services' => '0','menufacturing' => '0','projects' => '0','essentials' => '0','e_commerce' => '0','contact_default_cr_limit' => '50000000.00','created_at' => NULL,'updated_at' => '2021-09-02 13:31:41')
          );

        DB::table('general_settings')->insert($general_settings);
    }
}
