<?php

namespace Database\Seeders;

use DB;
use App\Models\GeneralSetting;
use App\Services\CacheServiceInterface;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{

    private function getSettings(): array
    {
        $general_settings = array(
            array('key' => 'addons__branches', 'value' => 1, 'branch_id' => null),
            array('key' => 'addons__hrm', 'value' => 1, 'branch_id' => null),
            array('key' => 'addons__todo', 'value' => 1, 'branch_id' => null),
            array('key' => 'addons__service', 'value' => 1, 'branch_id' => null),
            array('key' => 'addons__manufacturing', 'value' => 1, 'branch_id' => null),
            array('key' => 'addons__e_commerce', 'value' => 1, 'branch_id' => null),
            array('key' => 'addons__branch_limit', 'value' => 1, 'branch_id' => null),
            array('key' => 'addons__cash_counter_limit', 'value' => 1, 'branch_id' => null),
            array('key' => 'business__shop_name', 'value' => 'GenuinePos', 'branch_id' => null),
            array('key' => 'business__address', 'value' => 'Uttara, Sector - 4, Road No - 7, House No - 17, Dhaka, Bangladesh.', 'branch_id' => null),
            array('key' => 'business__phone', 'value' => '01719244933, 1722789897', 'branch_id' => null),
            array('key' => 'business__email', 'value' => 'speeddigitinfo@gmail.com', 'branch_id' => null),
            array('key' => 'business__start_date', 'value' => null, 'branch_id' => null),
            array('key' => 'business__default_profit', 'value' => '0', 'branch_id' => null),
            array('key' => 'business__currency', 'value' => 'TK.', 'branch_id' => null),
            array('key' => 'business__currency_placement', 'value' => null, 'branch_id' => null),
            array('key' => 'business__date_format', 'value' => 'd-m-Y', 'branch_id' => null),
            array('key' => 'business__stock_accounting_method', 'value' => '2', 'branch_id' => null),
            array('key' => 'business__time_format', 'value' => '12', 'branch_id' => null),
            array('key' => 'business__business_logo', 'value' => null, 'branch_id' => null),
            array('key' => 'business__timezone', 'value' => 'Asia/Dhaka', 'branch_id' => null),
            array('key' => 'tax__tax_1_name', 'value' => 'Tax', 'branch_id' => null),
            array('key' => 'tax__tax_1_no', 'value' => '1', 'branch_id' => null),
            array('key' => 'tax__tax_2_name', 'value' => 'GST', 'branch_id' => null),
            array('key' => 'tax__tax_2_no', 'value' => '2', 'branch_id' => null),
            array('key' => 'tax__is_tax_en_purchase_sale', 'value' => '1', 'branch_id' => null),
            array('key' => 'product__product_code_prefix', 'value' => null, 'branch_id' => null),
            array('key' => 'product__default_unit_id', 'value' => '3', 'branch_id' => null),
            array('key' => 'product__is_enable_brands', 'value' => '1', 'branch_id' => null),
            array('key' => 'product__is_enable_categories', 'value' => '1', 'branch_id' => null),
            array('key' => 'product__is_enable_sub_categories', 'value' => '1', 'branch_id' => null),
            array('key' => 'product__is_enable_price_tax', 'value' => '0', 'branch_id' => null),
            array('key' => 'product__is_enable_warranty', 'value' => '1', 'branch_id' => null),
            array('key' => 'sale__default_sale_discount', 'value' => '0.00', 'branch_id' => null),
            array('key' => 'sale__default_tax_id', 'value' => 'null', 'branch_id' => null),
            array('key' => 'sale__sales_commission_agent', 'value' => 'select_form_commission_list', 'branch_id' => null),
            array('key' => 'sale__default_price_group_id', 'value' => '7', 'branch_id' => null),
            array('key' => 'pos__is_enabled_multiple_pay', 'value' => '1', 'branch_id' => null),
            array('key' => 'pos__is_enabled_draft', 'value' => '1', 'branch_id' => null),
            array('key' => 'pos__is_enabled_quotation', 'value' => '1', 'branch_id' => null),
            array('key' => 'pos__is_enabled_suspend', 'value' => '1', 'branch_id' => null),
            array('key' => 'pos__is_enabled_discount', 'value' => '1', 'branch_id' => null),
            array('key' => 'pos__is_enabled_order_tax', 'value' => '1', 'branch_id' => null),
            array('key' => 'pos__is_show_recent_transactions', 'value' => '1', 'branch_id' => null),
            array('key' => 'pos__is_enabled_credit_full_sale', 'value' => '1', 'branch_id' => null),
            array('key' => 'pos__is_enabled_hold_invoice', 'value' => '1', 'branch_id' => null),
            array('key' => 'purchase__is_edit_pro_price', 'value' => '1', 'branch_id' => null),
            array('key' => 'purchase__is_enable_status', 'value' => '1', 'branch_id' => null),
            array('key' => 'purchase__is_enable_lot_no', 'value' => '1', 'branch_id' => null),
            array('key' => 'dashboard__view_stock_expiry_alert_for', 'value' => '31', 'branch_id' => null),
            array('key' => 'system__theme_color', 'value' => 'dark-theme', 'branch_id' => null),
            array('key' => 'system__datatables_page_entry', 'value' => '25', 'branch_id' => null),
            array('key' => 'prefix__purchase_invoice', 'value' => 'PI', 'branch_id' => null),
            array('key' => 'prefix__sale_invoice', 'value' => 'SI', 'branch_id' => null),
            array('key' => 'prefix__purchase_return', 'value' => 'PRI', 'branch_id' => null),
            array('key' => 'prefix__stock_transfer', 'value' => 'STI', 'branch_id' => null),
            array('key' => 'prefix__stock_adjustment', 'value' => 'SAR', 'branch_id' => null),
            array('key' => 'prefix__sale_return', 'value' => 'SRI', 'branch_id' => null),
            array('key' => 'prefix__expenses', 'value' => 'EXI', 'branch_id' => null),
            array('key' => 'prefix__supplier_id', 'value' => 'SID', 'branch_id' => null),
            array('key' => 'prefix__customer_id', 'value' => null, 'branch_id' => null),
            array('key' => 'prefix__purchase_payment', 'value' => 'PV', 'branch_id' => null),
            array('key' => 'prefix__sale_payment', 'value' => 'SPV', 'branch_id' => null),
            array('key' => 'prefix__expanse_payment', 'value' => 'EPV', 'branch_id' => null),
            array('key' => 'email_settings__send_inv_via_email', 'value' => '0', 'branch_id' => null),
            array('key' => 'email_settings__send_notice_via_sms', 'value' => '0', 'branch_id' => null),
            array('key' => 'email_settings__customer_due_reminder_via_email', 'value' => '0', 'branch_id' => null),
            array('key' => 'email_settings__customer_due_reminder_via_sms', 'value' => '0', 'branch_id' => null),
            array('key' => 'email_config__MAIL_MAILER', 'value' => 'smtp', 'branch_id' => null),
            array('key' => 'email_config__MAIL_HOST', 'value' => 'smtp.gmail.com', 'branch_id' => null),
            array('key' => 'email_config__MAIL_PORT', 'value' => '587', 'branch_id' => null),
            array('key' => 'email_config__MAIL_USERNAME', 'value' => 's1@gmail.com', 'branch_id' => null),
            array('key' => 'email_config__MAIL_PASSWORD', 'value' => 'speeddigit@54321', 'branch_id' => null),
            array('key' => 'email_config__MAIL_ENCRYPTION', 'value' => 'tls', 'branch_id' => null),
            array('key' => 'email_config__MAIL_FROM_ADDRESS', 'value' => 's1@gmail.com', 'branch_id' => null),
            array('key' => 'email_config__MAIL_FROM_NAME', 'value' => 'SpeedDigit', 'branch_id' => null),
            array('key' => 'email_config__MAIL_ACTIVE', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__purchases', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__add_sale', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__pos', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__transfer_stock', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__stock_adjustment', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__expenses', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__accounting', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__contacts', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__hrms', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__requisite', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__manufacturing', 'value' => '1', 'branch_id' => null),
            array('key' => 'modules__service', 'value' => '1', 'branch_id' => null),
            array('key' => 'reward_point_settings__enable_cus_point', 'value' => '0', 'branch_id' => null),
            array('key' => 'reward_point_settings__point_display_name', 'value' => 'Reward Point', 'branch_id' => null),
            array('key' => 'reward_point_settings__amount_for_unit_rp', 'value' => '10', 'branch_id' => null),
            array('key' => 'reward_point_settings__min_order_total_for_rp', 'value' => '100', 'branch_id' => null),
            array('key' => 'reward_point_settings__max_rp_per_order', 'value' => '50', 'branch_id' => null),
            array('key' => 'reward_point_settings__redeem_amount_per_unit_rp', 'value' => '0.10', 'branch_id' => null),
            array('key' => 'reward_point_settings__min_order_total_for_redeem', 'value' => '500', 'branch_id' => null),
            array('key' => 'reward_point_settings__min_redeem_point', 'value' => '30', 'branch_id' => null),
            array('key' => 'reward_point_settings__max_redeem_point', 'value' => '', 'branch_id' => null),
            array('key' => 'mf_settings__production_ref_prefix', 'value' => 'MF', 'branch_id' => null),
            array('key' => 'mf_settings__enable_editing_ingredient_qty', 'value' => '1', 'branch_id' => null),
            array('key' => 'mf_settings__enable_updating_product_price', 'value' => '1', 'branch_id' => null),
            array('key' => 'multi_branches', 'value' => '0', 'branch_id' => null),
            array('key' => 'hrm', 'value' => '0', 'branch_id' => null),
            array('key' => 'services', 'value' => '0', 'branch_id' => null),
            array('key' => 'manufacturing', 'value' => '0', 'branch_id' => null),
            array('key' => 'projects', 'value' => '0', 'branch_id' => null),
            array('key' => 'essentials', 'value' => '0', 'branch_id' => null),
            array('key' => 'e_commerce', 'value' => '0', 'branch_id' => null),
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
            GeneralSetting::create([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'branch_id' => $setting['branch_id'],
            ]);
        }
        $cacheService->syncGeneralSettings();
    }
}
