<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;
use App\Services\CacheServiceInterface;

class GeneralSettingsSeeder extends Seeder
{
    private function getSettings(): array
    {
        $general_settings = array(
            array('id' => '1','key' => 'addons__branches','value' => '1','branch_id' => NULL),
            array('id' => '2','key' => 'addons__hrm','value' => '1','branch_id' => NULL),
            array('id' => '3','key' => 'addons__todo','value' => '1','branch_id' => NULL),
            array('id' => '4','key' => 'addons__service','value' => '1','branch_id' => NULL),
            array('id' => '5','key' => 'addons__manufacturing','value' => '1','branch_id' => NULL),
            array('id' => '6','key' => 'addons__e_commerce','value' => '1','branch_id' => NULL),
            array('id' => '7','key' => 'addons__branch_limit','value' => 3,'branch_id' => NULL),
            array('id' => '8','key' => 'addons__cash_counter_limit','value' => 3,'branch_id' => NULL),
            array('id' => '9','key' => 'business__shop_name','value' => 'Company Name','branch_id' => NULL),
            array('id' => '10','key' => 'business__address','value' => 'Dhaka, Bangladesh','branch_id' => NULL),
            array('id' => '11','key' => 'business__phone','value' => '01700000000/ 01800000000','branch_id' => NULL),
            array('id' => '12','key' => 'business__email','value' => 'company.email@provider.com','branch_id' => NULL),
            array('id' => '13','key' => 'business__start_date','value' => '01-01-1970','branch_id' => NULL),
            array('id' => '14','key' => 'business__default_profit','value' => '0','branch_id' => NULL),
            array('id' => '15','key' => 'business__currency','value' => 'TK.','branch_id' => NULL),
            array('id' => '16','key' => 'business__currency_placement','value' => NULL,'branch_id' => NULL),
            array('id' => '17','key' => 'business__date_format','value' => 'd-m-Y','branch_id' => NULL),
            array('id' => '18','key' => 'business__stock_accounting_method','value' => '1','branch_id' => NULL),
            array('id' => '19','key' => 'business__time_format','value' => '12','branch_id' => NULL),
            array('id' => '20','key' => 'business__business_logo','value' => NULL,'branch_id' => NULL),
            array('id' => '21','key' => 'business__timezone','value' => 'Asia/Dhaka','branch_id' => NULL),
            array('id' => '22','key' => 'system__theme_color','value' => 'dark-theme','branch_id' => NULL),
            array('id' => '23','key' => 'system__datatable_page_entry','value' => '25','branch_id' => NULL),
            array('id' => '24','key' => 'sale__sales_commission_agent','value' => NULL,'branch_id' => NULL),
            array('id' => '25','key' => 'pos__is_enabled_multiple_pay','value' => NULL,'branch_id' => NULL),
            array('id' => '26','key' => 'pos__is_enabled_draft','value' => NULL,'branch_id' => NULL),
            array('id' => '27','key' => 'pos__is_enabled_quotation','value' => NULL,'branch_id' => NULL),
            array('id' => '28','key' => 'pos__is_enabled_suspend','value' => NULL,'branch_id' => NULL),
            array('id' => '29','key' => 'pos__is_enabled_discount','value' => NULL,'branch_id' => NULL),
            array('id' => '30','key' => 'pos__is_enabled_order_tax','value' => NULL,'branch_id' => NULL),
            array('id' => '31','key' => 'pos__is_enabled_credit_full_sale','value' => NULL,'branch_id' => NULL),
            array('id' => '32','key' => 'pos__is_enabled_hold_invoice','value' => NULL,'branch_id' => NULL),
            array('id' => '33','key' => 'system__datatables_page_entry','value' => NULL,'branch_id' => NULL),
            array('id' => '34','key' => 'prefix__stock_adjustment','value' => NULL,'branch_id' => NULL),
            array('id' => '35','key' => 'email_settings__send_inv_via_email','value' => NULL,'branch_id' => NULL),
            array('id' => '36','key' => 'email_settings__send_notice_via_sms','value' => NULL,'branch_id' => NULL),
            array('id' => '37','key' => 'email_settings__customer_due_reminder_via_email','value' => NULL,'branch_id' => NULL),
            array('id' => '38','key' => 'email_settings__customer_due_reminder_via_sms','value' => NULL,'branch_id' => NULL),
            array('id' => '39','key' => 'email_config__MAIL_MAILER','value' => NULL,'branch_id' => NULL),
            array('id' => '40','key' => 'email_config__MAIL_HOST','value' => NULL,'branch_id' => NULL),
            array('id' => '41','key' => 'email_config__MAIL_PORT','value' => NULL,'branch_id' => NULL),
            array('id' => '42','key' => 'email_config__MAIL_USERNAME','value' => NULL,'branch_id' => NULL),
            array('id' => '43','key' => 'email_config__MAIL_PASSWORD','value' => NULL,'branch_id' => NULL),
            array('id' => '44','key' => 'email_config__MAIL_ENCRYPTION','value' => NULL,'branch_id' => NULL),
            array('id' => '45','key' => 'email_config__MAIL_FROM_ADDRESS','value' => NULL,'branch_id' => NULL),
            array('id' => '46','key' => 'email_config__MAIL_FROM_NAME','value' => NULL,'branch_id' => NULL),
            array('id' => '47','key' => 'email_config__MAIL_ACTIVE','value' => NULL,'branch_id' => NULL),
            array('id' => '48','key' => 'modules__manufacturing','value' => NULL,'branch_id' => NULL),
            array('id' => '49','key' => 'modules__service','value' => NULL,'branch_id' => NULL),
            array('id' => '50','key' => 'mf_settings__production_ref_prefix','value' => NULL,'branch_id' => NULL),
            array('id' => '51','key' => 'mf_settings__enable_editing_ingredient_qty','value' => NULL,'branch_id' => NULL),
            array('id' => '52','key' => 'mf_settings__enable_updating_product_price','value' => NULL,'branch_id' => NULL),
            array('id' => '53','key' => 'sms__SMS_URL','value' => NULL,'branch_id' => NULL),
            array('id' => '54','key' => 'sms__API_KEY','value' => NULL,'branch_id' => NULL),
            array('id' => '55','key' => 'sms__SENDER_ID','value' => NULL,'branch_id' => NULL),
            array('id' => '56','key' => 'sms__SMS_ACTIVE','value' => NULL,'branch_id' => NULL),
            array('id' => '57','key' => 'tax__tax_1_name','value' => 'Tax','branch_id' => NULL),
            array('id' => '58','key' => 'tax__tax_1_no','value' => '1','branch_id' => NULL),
            array('id' => '59','key' => 'tax__tax_2_name','value' => 'GST','branch_id' => NULL),
            array('id' => '60','key' => 'tax__tax_2_no','value' => '2','branch_id' => NULL),
            array('id' => '61','key' => 'tax__is_tax_en_purchase_sale','value' => '1','branch_id' => NULL),
            array('id' => '62','key' => 'product__product_code_prefix','value' => NULL,'branch_id' => NULL),
            array('id' => '63','key' => 'product__default_unit_id','value' => '3','branch_id' => NULL),
            array('id' => '64','key' => 'product__is_enable_brands','value' => '1','branch_id' => NULL),
            array('id' => '65','key' => 'product__is_enable_categories','value' => '1','branch_id' => NULL),
            array('id' => '66','key' => 'product__is_enable_sub_categories','value' => '1','branch_id' => NULL),
            array('id' => '67','key' => 'product__is_enable_price_tax','value' => '0','branch_id' => NULL),
            array('id' => '68','key' => 'product__is_enable_warranty','value' => '1','branch_id' => NULL),
            array('id' => '69','key' => 'sale__default_sale_discount','value' => '0.00','branch_id' => NULL),
            array('id' => '70','key' => 'sale__default_tax_id','value' => 'null','branch_id' => NULL),
            array('id' => '71','key' => 'sale__sales_cmsn_agnt','value' => 'select_form_cmsn_list','branch_id' => NULL),
            array('id' => '72','key' => 'sale__default_price_group_id','value' => '7','branch_id' => NULL),
            array('id' => '73','key' => 'pos__is_disable_draft','value' => '0','branch_id' => NULL),
            array('id' => '74','key' => 'pos__is_disable_quotation','value' => '0','branch_id' => NULL),
            array('id' => '75','key' => 'pos__is_disable_challan','value' => '0','branch_id' => NULL),
            array('id' => '76','key' => 'pos__is_disable_hold_invoice','value' => '0','branch_id' => NULL),
            array('id' => '77','key' => 'pos__is_disable_multiple_pay','value' => '1','branch_id' => NULL),
            array('id' => '78','key' => 'pos__is_show_recent_transactions','value' => '0','branch_id' => NULL),
            array('id' => '79','key' => 'pos__is_disable_discount','value' => '0','branch_id' => NULL),
            array('id' => '80','key' => 'pos__is_disable_order_tax','value' => '0','branch_id' => NULL),
            array('id' => '81','key' => 'pos__is_show_credit_sale_button','value' => '1','branch_id' => NULL),
            array('id' => '82','key' => 'pos__is_show_partial_sale_button','value' => '1','branch_id' => NULL),
            array('id' => '83','key' => 'purchase__is_edit_pro_price','value' => '0','branch_id' => NULL),
            array('id' => '84','key' => 'purchase__is_enable_status','value' => '1','branch_id' => NULL),
            array('id' => '85','key' => 'purchase__is_enable_lot_no','value' => '1','branch_id' => NULL),
            array('id' => '86','key' => 'dashboard__view_stock_expiry_alert_for','value' => '31','branch_id' => NULL),
            array('id' => '87','key' => 'prefix__purchase_invoice','value' => 'PI','branch_id' => NULL),
            array('id' => '88','key' => 'prefix__sale_invoice','value' => 'SI','branch_id' => NULL),
            array('id' => '89','key' => 'prefix__purchase_return','value' => 'PRI','branch_id' => NULL),
            array('id' => '90','key' => 'prefix__stock_transfer','value' => 'STI','branch_id' => NULL),
            array('id' => '91','key' => 'prefix__stock_djustment','value' => 'SAR','branch_id' => NULL),
            array('id' => '92','key' => 'prefix__sale_return','value' => 'SRI','branch_id' => NULL),
            array('id' => '93','key' => 'prefix__expenses','value' => 'EXI','branch_id' => NULL),
            array('id' => '94','key' => 'prefix__supplier_id','value' => 'SID','branch_id' => NULL),
            array('id' => '95','key' => 'prefix__customer_id','value' => NULL,'branch_id' => NULL),
            array('id' => '96','key' => 'prefix__purchase_payment','value' => 'PPI','branch_id' => NULL),
            array('id' => '97','key' => 'prefix__sale_payment','value' => 'SPI','branch_id' => NULL),
            array('id' => '98','key' => 'prefix__expanse_payment','value' => 'EXPI','branch_id' => NULL),
            array('id' => '99','key' => 'send_es_settings__send_inv_via_email','value' => '0','branch_id' => NULL),
            array('id' => '100','key' => 'send_es_settings__send_notice_via_sms','value' => '0','branch_id' => NULL),
            array('id' => '101','key' => 'send_es_settings__cmr_due_rmdr_via_email','value' => '0','branch_id' => NULL),
            array('id' => '102','key' => 'send_es_settings__cmr_due_rmdr_via_sms','value' => '0','branch_id' => NULL),
            array('id' => '103','key' => 'email_setting__MAIL_MAILER','value' => 'smtp','branch_id' => NULL),
            array('id' => '104','key' => 'email_setting__MAIL_HOST','value' => 'smtp.gmail.com','branch_id' => NULL),
            array('id' => '105','key' => 'email_setting__MAIL_PORT','value' => '587','branch_id' => NULL),
            array('id' => '106','key' => 'email_setting__MAIL_USERNAME','value' => 's1@gmail.com','branch_id' => NULL),
            array('id' => '107','key' => 'email_setting__MAIL_PASSWORD','value' => 'speeddigit@54321','branch_id' => NULL),
            array('id' => '108','key' => 'email_setting__MAIL_ENCRYPTION','value' => 'tls','branch_id' => NULL),
            array('id' => '109','key' => 'email_setting__MAIL_FROM_ADDRESS','value' => 's1@gmail.com','branch_id' => NULL),
            array('id' => '110','key' => 'email_setting__MAIL_FROM_NAME','value' => 'SpeedDigit','branch_id' => NULL),
            array('id' => '111','key' => 'email_setting__MAIL_ACTIVE','value' => '1','branch_id' => NULL),
            array('id' => '112','key' => 'modules__purchases','value' => '1','branch_id' => NULL),
            array('id' => '113','key' => 'modules__add_sale','value' => '1','branch_id' => NULL),
            array('id' => '114','key' => 'modules__pos','value' => '1','branch_id' => NULL),
            array('id' => '115','key' => 'modules__transfer_stock','value' => '1','branch_id' => NULL),
            array('id' => '116','key' => 'modules__stock_adjustment','value' => '1','branch_id' => NULL),
            array('id' => '117','key' => 'modules__expenses','value' => '1','branch_id' => NULL),
            array('id' => '118','key' => 'modules__accounting','value' => '1','branch_id' => NULL),
            array('id' => '119','key' => 'modules__contacts','value' => '1','branch_id' => NULL),
            array('id' => '120','key' => 'modules__hrms','value' => '1','branch_id' => NULL),
            array('id' => '121','key' => 'modules__requisite','value' => '1','branch_id' => NULL),
            array('id' => '122','key' => 'reward_point_settings__enable_cus_point','value' => '1','branch_id' => NULL),
            array('id' => '123','key' => 'reward_point_settings__point_display_name','value' => 'Reward Point','branch_id' => NULL),
            array('id' => '124','key' => 'reward_point_settings__amount_for_unit_rp','value' => '10','branch_id' => NULL),
            array('id' => '125','key' => 'reward_point_settings__min_order_total_for_rp','value' => '100','branch_id' => NULL),
            array('id' => '126','key' => 'reward_point_settings__max_rp_per_order','value' => '','branch_id' => NULL),
            array('id' => '127','key' => 'reward_point_settings__redeem_amount_per_unit_rp','value' => '0.10','branch_id' => NULL),
            array('id' => '128','key' => 'reward_point_settings__min_order_total_for_redeem','value' => '','branch_id' => NULL),
            array('id' => '129','key' => 'reward_point_settings__min_redeem_point','value' => '','branch_id' => NULL),
            array('id' => '130','key' => 'reward_point_settings__max_redeem_point','value' => '','branch_id' => NULL),
            array('id' => '131','key' => 'mf_settings','value' => NULL,'branch_id' => NULL),
            array('id' => '132','key' => 'multi_branches','value' => '0','branch_id' => NULL),
            array('id' => '133','key' => 'hrm','value' => '0','branch_id' => NULL),
            array('id' => '134','key' => 'services','value' => '0','branch_id' => NULL),
            array('id' => '135','key' => 'manufacturing','value' => '0','branch_id' => NULL),
            array('id' => '136','key' => 'projects','value' => '0','branch_id' => NULL),
            array('id' => '137','key' => 'essentials','value' => '0','branch_id' => NULL),
            array('id' => '138','key' => 'e_commerce','value' => '0','branch_id' => NULL),
            array('id' => '139','key' => 'email_settings__user_forget_password_via_email','value' => NULL,'branch_id' => NULL),
            array('id' => '140','key' => 'email_settings__coupon_offer_via_email','value' => NULL,'branch_id' => NULL),
            array('id' => '141','key' => 'email_settings__discount_redeemed_via_email','value' => NULL,'branch_id' => NULL),
            array('id' => '141','key' => 'email_settings__new_product_arrived_via_email','value' => NULL,'branch_id' => NULL),
            array('id' => '141','key' => 'email_settings__weekly_news_letter_via_email','value' => NULL,'branch_id' => NULL),
          );

        return $general_settings;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(CacheServiceInterface $cacheService)
    {
        $cacheService->removeGeneralSettings();
        GeneralSetting::truncate();
        $settings = $this->getSettings();
        foreach($settings as $setting) {
            // echo $setting['key'] . ': ' . $setting['value'] . PHP_EOL;
            GeneralSetting::create([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'branch_id' => $setting['branch_id'],
            ]);
        }
        $cacheService->syncGeneralSettings();
    }
}
