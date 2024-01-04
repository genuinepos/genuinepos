<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use App\Services\CacheServiceInterface;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    private function getSettings(): array
    {
        $general_settings = [
            ['id' => '2', 'key' => 'addons__hrm', 'value' => '1', 'branch_id' => null],
            ['id' => '3', 'key' => 'addons__manage_task', 'value' => '1', 'branch_id' => null],
            ['id' => '4', 'key' => 'addons__service', 'value' => '1', 'branch_id' => null],
            ['id' => '5', 'key' => 'addons__manufacturing', 'value' => '1', 'branch_id' => null],
            ['id' => '6', 'key' => 'addons__e_commerce', 'value' => '0', 'branch_id' => null],
            ['id' => '7', 'key' => 'addons__branch_limit', 'value' => 3, 'branch_id' => null],
            ['id' => '8', 'key' => 'addons__cash_counter_limit', 'value' => 3, 'branch_id' => null],
            ['id' => '9', 'key' => 'business__business_name', 'value' => 'Company Name', 'branch_id' => null],
            ['id' => '10', 'key' => 'business__address', 'value' => 'Dhaka, Bangladesh', 'branch_id' => null],
            ['id' => '11', 'key' => 'business__phone', 'value' => '01700000000/ 01800000000', 'branch_id' => null],
            ['id' => '12', 'key' => 'business__email', 'value' => 'company.email@provider.com', 'branch_id' => null],
            ['id' => '13', 'key' => 'business__account_start_date', 'value' => '01-01-2023', 'branch_id' => null],
            ['id' => '14', 'key' => 'business__default_profit', 'value' => '0', 'branch_id' => null],
            ['id' => '15', 'key' => 'business__currency_id', 'value' => '2', 'branch_id' => null],
            ['id' => '16', 'key' => 'business__currency_symbol', 'value' => 'TK.', 'branch_id' => null],
            ['id' => '17', 'key' => 'business__date_format', 'value' => 'd-m-Y', 'branch_id' => null],
            ['id' => '18', 'key' => 'business__stock_accounting_method', 'value' => '1', 'branch_id' => null],
            ['id' => '19', 'key' => 'business__time_format', 'value' => '12', 'branch_id' => null],
            ['id' => '20', 'key' => 'business__business_logo', 'value' => null, 'branch_id' => null],
            ['id' => '21', 'key' => 'business__timezone', 'value' => 'Asia/Dhaka', 'branch_id' => null],
            ['id' => '22', 'key' => 'system__theme_color', 'value' => 'dark-theme', 'branch_id' => null],
            ['id' => '23', 'key' => 'system__datatable_page_entry', 'value' => '25', 'branch_id' => null],
            ['id' => '25', 'key' => 'pos__is_enabled_multiple_pay', 'value' => null, 'branch_id' => null],
            ['id' => '26', 'key' => 'pos__is_enabled_draft', 'value' => null, 'branch_id' => null],
            ['id' => '27', 'key' => 'pos__is_enabled_quotation', 'value' => null, 'branch_id' => null],
            ['id' => '28', 'key' => 'pos__is_enabled_suspend', 'value' => null, 'branch_id' => null],
            ['id' => '29', 'key' => 'pos__is_enabled_discount', 'value' => null, 'branch_id' => null],
            ['id' => '30', 'key' => 'pos__is_enabled_order_tax', 'value' => null, 'branch_id' => null],
            ['id' => '31', 'key' => 'pos__is_enabled_credit_full_sale', 'value' => null, 'branch_id' => null],
            ['id' => '32', 'key' => 'pos__is_enabled_hold_invoice', 'value' => null, 'branch_id' => null],
            ['id' => '33', 'key' => 'system__datatables_page_entry', 'value' => null, 'branch_id' => null],
            ['id' => '34', 'key' => 'prefix__sales_invoice_prefix', 'value' => null, 'branch_id' => null],
            ['id' => '35', 'key' => 'email_settings__send_inv_via_email', 'value' => null, 'branch_id' => null],
            ['id' => '36', 'key' => 'email_settings__send_notice_via_sms', 'value' => null, 'branch_id' => null],
            ['id' => '37', 'key' => 'email_settings__customer_due_reminder_via_email', 'value' => null, 'branch_id' => null],
            ['id' => '38', 'key' => 'email_settings__customer_due_reminder_via_sms', 'value' => null, 'branch_id' => null],
            ['id' => '39', 'key' => 'email_config__MAIL_MAILER', 'value' => null, 'branch_id' => null],
            ['id' => '40', 'key' => 'email_config__MAIL_HOST', 'value' => null, 'branch_id' => null],
            ['id' => '41', 'key' => 'email_config__MAIL_PORT', 'value' => null, 'branch_id' => null],
            ['id' => '42', 'key' => 'email_config__MAIL_USERNAME', 'value' => null, 'branch_id' => null],
            ['id' => '43', 'key' => 'email_config__MAIL_PASSWORD', 'value' => null, 'branch_id' => null],
            ['id' => '44', 'key' => 'email_config__MAIL_ENCRYPTION', 'value' => null, 'branch_id' => null],
            ['id' => '45', 'key' => 'email_config__MAIL_FROM_ADDRESS', 'value' => null, 'branch_id' => null],
            ['id' => '46', 'key' => 'email_config__MAIL_FROM_NAME', 'value' => null, 'branch_id' => null],
            ['id' => '47', 'key' => 'email_config__MAIL_ACTIVE', 'value' => null, 'branch_id' => null],
            ['id' => '48', 'key' => 'modules__manufacturing', 'value' => null, 'branch_id' => null],
            ['id' => '49', 'key' => 'modules__service', 'value' => null, 'branch_id' => null],
            ['id' => '53', 'key' => 'sms__SMS_URL', 'value' => null, 'branch_id' => null],
            ['id' => '54', 'key' => 'sms__API_KEY', 'value' => null, 'branch_id' => null],
            ['id' => '55', 'key' => 'sms__SENDER_ID', 'value' => null, 'branch_id' => null],
            ['id' => '56', 'key' => 'sms__SMS_ACTIVE', 'value' => null, 'branch_id' => null],
            ['id' => '62', 'key' => 'product__product_code_prefix', 'value' => null, 'branch_id' => null],
            ['id' => '63', 'key' => 'product__default_unit_id', 'value' => '3', 'branch_id' => null],
            ['id' => '64', 'key' => 'product__is_enable_brands', 'value' => '1', 'branch_id' => null],
            ['id' => '65', 'key' => 'product__is_enable_categories', 'value' => '1', 'branch_id' => null],
            ['id' => '66', 'key' => 'product__is_enable_sub_categories', 'value' => '1', 'branch_id' => null],
            ['id' => '67', 'key' => 'product__is_enable_price_tax', 'value' => '0', 'branch_id' => null],
            ['id' => '68', 'key' => 'product__is_enable_warranty', 'value' => '1', 'branch_id' => null],
            ['id' => '69', 'key' => 'add_sale__default_sale_discount', 'value' => '0.00', 'branch_id' => null],
            ['id' => '72', 'key' => 'add_sale__default_price_group_id', 'value' => null, 'branch_id' => null],
            ['id' => '73', 'key' => 'pos__is_disable_draft', 'value' => '0', 'branch_id' => null],
            ['id' => '74', 'key' => 'pos__is_disable_quotation', 'value' => '0', 'branch_id' => null],
            ['id' => '75', 'key' => 'pos__is_disable_challan', 'value' => '0', 'branch_id' => null],
            ['id' => '76', 'key' => 'pos__is_disable_hold_invoice', 'value' => '0', 'branch_id' => null],
            ['id' => '77', 'key' => 'pos__is_disable_multiple_pay', 'value' => '1', 'branch_id' => null],
            ['id' => '78', 'key' => 'pos__is_show_recent_transactions', 'value' => '0', 'branch_id' => null],
            ['id' => '79', 'key' => 'pos__is_disable_discount', 'value' => '0', 'branch_id' => null],
            ['id' => '80', 'key' => 'pos__is_disable_order_tax', 'value' => '0', 'branch_id' => null],
            ['id' => '81', 'key' => 'pos__is_show_credit_sale_button', 'value' => '1', 'branch_id' => null],
            ['id' => '82', 'key' => 'pos__is_show_partial_sale_button', 'value' => '1', 'branch_id' => null],
            ['id' => '83', 'key' => 'purchase__is_edit_pro_price', 'value' => '0', 'branch_id' => null],
            ['id' => '84', 'key' => 'purchase__is_enable_status', 'value' => '1', 'branch_id' => null],
            ['id' => '85', 'key' => 'purchase__is_enable_lot_no', 'value' => '1', 'branch_id' => null],
            ['id' => '86', 'key' => 'dashboard__view_stock_expiry_alert_for', 'value' => '31', 'branch_id' => null],
            ['id' => '87', 'key' => 'prefix__quotation_prefix', 'value' => 'PI', 'branch_id' => null],
            ['id' => '88', 'key' => 'prefix__sales_order_prefix', 'value' => 'SI', 'branch_id' => null],
            ['id' => '89', 'key' => 'prefix__sales_return_prefix', 'value' => 'PRV', 'branch_id' => null],
            ['id' => '90', 'key' => 'prefix__payment_voucher_prefix', 'value' => 'ST', 'branch_id' => null],
            ['id' => '91', 'key' => 'prefix__receipt_voucher_prefix', 'value' => 'SAV', 'branch_id' => null],
            ['id' => '92', 'key' => 'prefix__expense_voucher_prefix', 'value' => 'SRV', 'branch_id' => null],
            ['id' => '93', 'key' => 'prefix__contra_voucher_prefix', 'value' => 'EV', 'branch_id' => null],
            ['id' => '94', 'key' => 'prefix__purchase_invoice_prefix', 'value' => 'S-', 'branch_id' => null],
            ['id' => '95', 'key' => 'prefix__purchase_order_prefix', 'value' => 'C-', 'branch_id' => null],
            ['id' => '96', 'key' => 'prefix__purchase_return_prefix', 'value' => 'PV', 'branch_id' => null],
            ['id' => '97', 'key' => 'prefix__stock_adjustment_prefix', 'value' => 'RV', 'branch_id' => null],
            ['id' => '103', 'key' => 'email_setting__MAIL_MAILER', 'value' => 'smtp', 'branch_id' => null],
            ['id' => '104', 'key' => 'email_setting__MAIL_HOST', 'value' => 'smtp.gmail.com', 'branch_id' => null],
            ['id' => '105', 'key' => 'email_setting__MAIL_PORT', 'value' => '587', 'branch_id' => null],
            ['id' => '106', 'key' => 'email_setting__MAIL_USERNAME', 'value' => 's1@gmail.com', 'branch_id' => null],
            ['id' => '107', 'key' => 'email_setting__MAIL_PASSWORD', 'value' => 'speeddigit@54321', 'branch_id' => null],
            ['id' => '108', 'key' => 'email_setting__MAIL_ENCRYPTION', 'value' => 'tls', 'branch_id' => null],
            ['id' => '109', 'key' => 'email_setting__MAIL_FROM_ADDRESS', 'value' => 's1@gmail.com', 'branch_id' => null],
            ['id' => '110', 'key' => 'email_setting__MAIL_FROM_NAME', 'value' => 'SpeedDigit', 'branch_id' => null],
            ['id' => '111', 'key' => 'email_setting__MAIL_ACTIVE', 'value' => '1', 'branch_id' => null],
            ['id' => '112', 'key' => 'modules__purchases', 'value' => '1', 'branch_id' => null],
            ['id' => '113', 'key' => 'modules__add_sale', 'value' => '1', 'branch_id' => null],
            ['id' => '114', 'key' => 'modules__pos', 'value' => '1', 'branch_id' => null],
            ['id' => '115', 'key' => 'modules__transfer_stock', 'value' => '1', 'branch_id' => null],
            ['id' => '116', 'key' => 'modules__stock_adjustments', 'value' => '1', 'branch_id' => null],
            ['id' => '118', 'key' => 'modules__accounting', 'value' => '1', 'branch_id' => null],
            ['id' => '119', 'key' => 'modules__contacts', 'value' => '1', 'branch_id' => null],
            ['id' => '120', 'key' => 'modules__hrms', 'value' => '1', 'branch_id' => null],
            ['id' => '121', 'key' => 'modules__manage_task', 'value' => '1', 'branch_id' => null], //

            ['id' => '122', 'key' => 'reward_point_settings__enable_cus_point', 'value' => '1', 'branch_id' => null],
            ['id' => '123', 'key' => 'reward_point_settings__point_display_name', 'value' => 'Reward Point', 'branch_id' => null],
            ['id' => '124', 'key' => 'reward_point_settings__amount_for_unit_rp', 'value' => '10', 'branch_id' => null],
            ['id' => '125', 'key' => 'reward_point_settings__min_order_total_for_rp', 'value' => '100', 'branch_id' => null],
            ['id' => '126', 'key' => 'reward_point_settings__max_rp_per_order', 'value' => '', 'branch_id' => null],
            ['id' => '127', 'key' => 'reward_point_settings__redeem_amount_per_unit_rp', 'value' => '0.10', 'branch_id' => null],
            ['id' => '128', 'key' => 'reward_point_settings__min_order_total_for_redeem', 'value' => '', 'branch_id' => null],
            ['id' => '129', 'key' => 'reward_point_settings__min_redeem_point', 'value' => '', 'branch_id' => null],
            ['id' => '130', 'key' => 'reward_point_settings__max_redeem_point', 'value' => '', 'branch_id' => null],

            ['id' => '139', 'key' => 'send_email__send_invoice_via_email', 'value' => null, 'branch_id' => null],
            ['id' => '140', 'key' => 'send_email__send_notification_via_email', 'value' => null, 'branch_id' => null],
            ['id' => '141', 'key' => 'send_email__customer_due_reminder_via_email', 'value' => null, 'branch_id' => null],
            ['id' => '141', 'key' => 'send_email__user_forget_password_via_email', 'value' => null, 'branch_id' => null],
            ['id' => '141', 'key' => 'send_email__coupon_offer_via_email', 'value' => null, 'branch_id' => null],

            ['id' => '139', 'key' => 'send_sms__send_invoice_via_sms', 'value' => null, 'branch_id' => null],
            ['id' => '140', 'key' => 'send_sms__send_notification_via_sms', 'value' => null, 'branch_id' => null],
            ['id' => '141', 'key' => 'send_sms__customer_due_reminder_via_sms', 'value' => null, 'branch_id' => null],

            ['id' => '142', 'key' => 'business__financial_year_start_month', 'value' => '1', 'branch_id' => null],
            ['id' => '143', 'key' => 'add_sale__default_tax_ac_id', 'value' => 'null', 'branch_id' => null],
            ['id' => '144', 'key' => 'pos__default_tax_ac_id', 'value' => 'null', 'branch_id' => null],

            ['id' => '145', 'key' => 'prefix__supplier_id', 'value' => 'S-', 'branch_id' => null, 'parent_branch_id' => null],
            ['id' => '146', 'key' => 'prefix__customer_id', 'value' => 'C-', 'branch_id' => null, 'parent_branch_id' => null],

            ['id' => '147', 'key' => 'manufacturing__production_voucher_prefix', 'value' => 'MF', 'branch_id' => null, 'parent_branch_id' => null],
            ['id' => '148', 'key' => 'manufacturing__is_edit_ingredients_qty_in_production', 'value' => 1, 'branch_id' => null, 'parent_branch_id' => null],
            ['id' => '149', 'key' => 'manufacturing__is_update_product_cost_and_price_in_production', 'value' => 1, 'branch_id' => null, 'parent_branch_id' => null],
        ];

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
        foreach ($settings as $setting) {
            GeneralSetting::create([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'branch_id' => $setting['branch_id'],
            ]);
        }
        $cacheService->syncGeneralSettings();
    }
}
