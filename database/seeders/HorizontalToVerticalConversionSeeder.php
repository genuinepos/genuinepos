<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class HorizontalToVerticalConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GeneralSetting::truncate();
        $general_settings['business'] = '{"shop_name":"Genuine POS","address":"Uttara Sector 4, Dhaka","phone":"01970133444","email":"info@genuinepos.com","start_date":"07-04-2021","default_profit":0,"currency":"\u09f3","currency_placement":null,"date_format":"d-m-Y","financial_year_start":"January","time_format":"12","business_logo":"","timezone":"Asia\/Dhaka"}';

        $general_settings['tax'] = '{"tax_1_name":"Tax","tax_1_no":"1","tax_2_name":"GST","tax_2_no":"2","is_tax_en_purchase_sale":1}';
        $general_settings['product'] = '{"product_code_prefix":null,"default_unit_id":"3","is_enable_brands":1,"is_enable_categories":1,"is_enable_sub_categories":1,"is_enable_price_tax":0,"is_enable_warranty":1}';
        $general_settings['sale'] = '{"default_sale_discount":"0.00","default_tax_id":"null","sales_cmsn_agnt":"select_form_cmsn_list","default_price_group_id":"7"}';

        $general_settings['pos'] = '{"is_disable_draft":0,"is_disable_quotation":0,"is_disable_challan":0,"is_disable_hold_invoice":0,"is_disable_multiple_pay":1,"is_show_recent_transactions":0,"is_disable_discount":0,"is_disable_order_tax":0,"is_show_credit_sale_button":1,"is_show_partial_sale_button":1}';

        $general_settings['purchase'] = '{"is_edit_pro_price":0,"is_enable_status":1,"is_enable_lot_no":1}';
        $general_settings['dashboard'] = '{"view_stock_expiry_alert_for":"31"}';
        $general_settings['system'] = '{"theme_color":"dark-theme","datatable_page_entry":"25"}';

        $general_settings['prefix'] = '{"purchase_invoice":"PI","sale_invoice":"SI","purchase_return":"PRI","stock_transfer":"STI","stock_djustment":"SAR","sale_return":"SRI","expenses":"EXI","supplier_id":"SID","customer_id":null,"purchase_payment":"PPI","sale_payment":"SPI","expanse_payment":"EXPI"}';

        $general_settings['send_es_settings'] = '{"send_inv_via_email":0,"send_notice_via_sms":0,"cmr_due_rmdr_via_email":0,"cmr_due_rmdr_via_sms":0}';

        $general_settings['email_setting'] = '{"MAIL_MAILER":"smtp","MAIL_HOST":"smtp.gmail.com","MAIL_PORT":"587","MAIL_USERNAME":"s1@gmail.com","MAIL_PASSWORD":"speeddigit@54321","MAIL_ENCRYPTION":"tls","MAIL_FROM_ADDRESS":"s1@gmail.com","MAIL_FROM_NAME":"SpeedDigit","MAIL_ACTIVE":true}';
        
        $general_settings['sms_setting'] = '[]';
        $general_settings['modules'] = '{"purchases":1,"add_sale":1,"pos":1,"transfer_stock":1,"stock_adjustment":1,"expenses":1,"accounting":1,"contacts":1,"hrms":1,"requisite":1}';

        $general_settings['reward_point_settings'] = '{"enable_cus_point":1,"point_display_name":"Reward Point","amount_for_unit_rp":"10","min_order_total_for_rp":"100","max_rp_per_order":"","redeem_amount_per_unit_rp":"0.10","min_order_total_for_redeem":"","min_redeem_point":"","max_redeem_point":""}';

        $general_settings['mf_settings'] = '';
        $general_settings['multi_branches'] = '0';
        $general_settings['hrm'] = '0';
        $general_settings['services'] = '0';
        $general_settings['manufacturing'] = '0';
        $general_settings['projects'] = '0';
        $general_settings['essentials'] = '0';
        $general_settings['e_commerce'] = '0';

        foreach ($general_settings as $key => $value) {
            if(isset($value) && !is_int($value)) {
                $internalArr = json_decode($value, true);
                if(isset($internalArr) && !is_int($internalArr)) {
                    foreach($internalArr as $keyII => $valueII) {
                        GeneralSetting::create([
                            'key' => $key .'__' .$keyII,
                            'value' => $valueII,
                        ]);
                    }
                } else {
                    GeneralSetting::create([
                        'key' => $key,
                        'value' => $value,
                    ]);
                }

            } else {
                GeneralSetting::create([
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }
    }
}
