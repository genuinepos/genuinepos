<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use App\Services\CacheServiceInterface;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    private function getSettings(): array
    {
        $generalSettings = [
            ['key' => 'addons__hrm', 'value' => '1', 'branch_id' => null],
            ['key' => 'addons__manage_task', 'value' => '1', 'branch_id' => null],
            ['key' => 'addons__service', 'value' => '1', 'branch_id' => null],
            ['key' => 'addons__manufacturing', 'value' => '1', 'branch_id' => null],
            ['key' => 'addons__e_commerce', 'value' => '0', 'branch_id' => null],
            ['key' => 'addons__branch_limit', 'value' => 3, 'branch_id' => null],
            ['key' => 'addons__cash_counter_limit', 'value' => 3, 'branch_id' => null],
            ['key' => 'business_or_shop__business_name', 'value' => 'Company Name', 'branch_id' => null],
            ['key' => 'business_or_shop__address', 'value' => 'Dhaka, Bangladesh', 'branch_id' => null],
            ['key' => 'business_or_shop__phone', 'value' => 'XXXXXXXXXX', 'branch_id' => null],
            ['key' => 'business_or_shop__email', 'value' => 'company.email@provider.com', 'branch_id' => null],
            ['key' => 'business_or_shop__account_start_date', 'value' => date('d-m-Y'), 'branch_id' => null],
            ['key' => 'business_or_shop__financial_year_start_month', 'value' => '1', 'branch_id' => null],
            ['key' => 'business_or_shop__default_profit', 'value' => '0', 'branch_id' => null],
            ['key' => 'business_or_shop__currency_id', 'value' => '2', 'branch_id' => null],
            ['key' => 'business_or_shop__currency_symbol', 'value' => '$', 'branch_id' => null],
            ['key' => 'business_or_shop__date_format', 'value' => 'd-m-Y', 'branch_id' => null],
            ['key' => 'business_or_shop__stock_accounting_method', 'value' => '1', 'branch_id' => null],
            ['key' => 'business_or_shop__time_format', 'value' => '12', 'branch_id' => null],
            ['key' => 'business_or_shop__business_logo', 'value' => null, 'branch_id' => null],
            ['key' => 'business_or_shop__timezone', 'value' => 'Asia/Dhaka', 'branch_id' => null],
            ['key' => 'system__theme_color', 'value' => 'dark-theme', 'branch_id' => null],
            ['key' => 'system__datatables_page_entry', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_enabled_multiple_pay', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_enabled_draft', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_enabled_quotation', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_enabled_suspend', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_enabled_discount', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_enabled_order_tax', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_enabled_credit_full_sale', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_enabled_hold_invoice', 'value' => null, 'branch_id' => null],

            // ['key' => 'email_settings__send_inv_via_email', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_settings__send_notice_via_sms', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_settings__customer_due_reminder_via_email', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_settings__customer_due_reminder_via_sms', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_MAILER', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_HOST', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_PORT', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_USERNAME', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_PASSWORD', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_ENCRYPTION', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_FROM_ADDRESS', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_FROM_NAME', 'value' => null, 'branch_id' => null],
            // ['key' => 'email_config__MAIL_ACTIVE', 'value' => null, 'branch_id' => null],
            ['key' => 'modules__manufacturing', 'value' => null, 'branch_id' => null],
            ['key' => 'modules__service', 'value' => null, 'branch_id' => null],
            // ['key' => 'sms__SMS_URL', 'value' => null, 'branch_id' => null],
            // ['key' => 'sms__API_KEY', 'value' => null, 'branch_id' => null],
            // ['key' => 'sms__SENDER_ID', 'value' => null, 'branch_id' => null],
            // ['key' => 'sms__SMS_ACTIVE', 'value' => null, 'branch_id' => null],
            ['key' => 'product__product_code_prefix', 'value' => null, 'branch_id' => null],
            ['key' => 'product__default_unit_id', 'value' => null, 'branch_id' => null],
            ['key' => 'product__is_enable_brands', 'value' => '1', 'branch_id' => null],
            ['key' => 'product__is_enable_categories', 'value' => '1', 'branch_id' => null],
            ['key' => 'product__is_enable_sub_categories', 'value' => '1', 'branch_id' => null],
            ['key' => 'product__is_enable_price_tax', 'value' => '0', 'branch_id' => null],
            ['key' => 'product__is_enable_warranty', 'value' => '1', 'branch_id' => null],
            ['key' => 'add_sale__default_sale_discount', 'value' => '0.00', 'branch_id' => null],
            ['key' => 'add_sale__default_price_group_id', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__is_disable_draft', 'value' => '0', 'branch_id' => null],
            ['key' => 'pos__is_disable_quotation', 'value' => '0', 'branch_id' => null],
            ['key' => 'pos__is_disable_challan', 'value' => '0', 'branch_id' => null],
            ['key' => 'pos__is_disable_hold_invoice', 'value' => '0', 'branch_id' => null],
            ['key' => 'pos__is_disable_multiple_pay', 'value' => '1', 'branch_id' => null],
            ['key' => 'pos__is_show_recent_transactions', 'value' => '0', 'branch_id' => null],
            ['key' => 'pos__is_disable_discount', 'value' => '0', 'branch_id' => null],
            ['key' => 'pos__is_disable_order_tax', 'value' => '0', 'branch_id' => null],
            ['key' => 'pos__is_show_credit_sale_button', 'value' => '1', 'branch_id' => null],
            ['key' => 'pos__is_show_partial_sale_button', 'value' => '1', 'branch_id' => null],
            ['key' => 'purchase__is_edit_pro_price', 'value' => '0', 'branch_id' => null],
            ['key' => 'purchase__is_enable_status', 'value' => '1', 'branch_id' => null],
            ['key' => 'purchase__is_enable_lot_no', 'value' => '1', 'branch_id' => null],
            ['key' => 'dashboard__view_stock_expiry_alert_for', 'value' => '31', 'branch_id' => null],
            ['key' => 'prefix__sales_invoice_prefix', 'value' => 'SI', 'branch_id' => null],
            ['key' => 'prefix__quotation_prefix', 'value' => 'Q', 'branch_id' => null],
            ['key' => 'prefix__sales_order_prefix', 'value' => 'SO', 'branch_id' => null],
            ['key' => 'prefix__sales_return_prefix', 'value' => 'SR', 'branch_id' => null],
            ['key' => 'prefix__payment_voucher_prefix', 'value' => 'PV', 'branch_id' => null],
            ['key' => 'prefix__receipt_voucher_prefix', 'value' => 'RV', 'branch_id' => null],
            ['key' => 'prefix__expense_voucher_prefix', 'value' => 'EX', 'branch_id' => null],
            ['key' => 'prefix__contra_voucher_prefix', 'value' => 'CO', 'branch_id' => null],
            ['key' => 'prefix__purchase_invoice_prefix', 'value' => 'PI', 'branch_id' => null],
            ['key' => 'prefix__purchase_order_prefix', 'value' => 'PO', 'branch_id' => null],
            ['key' => 'prefix__purchase_return_prefix', 'value' => 'PR', 'branch_id' => null],
            ['key' => 'prefix__stock_adjustment_prefix', 'value' => 'SA', 'branch_id' => null],
            ['key' => 'prefix__payroll_voucher_prefix', 'value' => 'PRL', 'branch_id' => null],
            ['key' => 'prefix__payroll_payment_voucher_prefix', 'value' => 'PRLP', 'branch_id' => null],
            ['key' => 'prefix__supplier_id', 'value' => 'S-', 'branch_id' => null],
            ['key' => 'prefix__customer_id', 'value' => 'C-', 'branch_id' => null, 'parent_branch_id' => null],
            // ['key' => 'email_setting__MAIL_MAILER', 'value' => 'smtp', 'branch_id' => null],
            // ['key' => 'email_setting__MAIL_HOST', 'value' => 'smtp.gmail.com', 'branch_id' => null],
            // ['key' => 'email_setting__MAIL_PORT', 'value' => '587', 'branch_id' => null],
            // ['key' => 'email_setting__MAIL_USERNAME', 'value' => 's1@gmail.com', 'branch_id' => null],
            // ['key' => 'email_setting__MAIL_PASSWORD', 'value' => 'speeddigit@54321', 'branch_id' => null],
            // ['key' => 'email_setting__MAIL_ENCRYPTION', 'value' => 'tls', 'branch_id' => null],
            // ['key' => 'email_setting__MAIL_FROM_ADDRESS', 'value' => 's1@gmail.com', 'branch_id' => null],
            // ['key' => 'email_setting__MAIL_FROM_NAME', 'value' => 'SpeedDigit', 'branch_id' => null],
            // ['key' => 'email_setting__MAIL_ACTIVE', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__purchases', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__add_sale', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__pos', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__transfer_stock', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__stock_adjustments', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__accounting', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__contacts', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__hrms', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__manage_task', 'value' => '1', 'branch_id' => null], //

            ['key' => 'reward_point_settings__enable_cus_point', 'value' => 0, 'branch_id' => null],
            ['key' => 'reward_point_settings__point_display_name', 'value' => 'Reward Point', 'branch_id' => null],
            ['key' => 'reward_point_settings__amount_for_unit_rp', 'value' => '10', 'branch_id' => null],
            ['key' => 'reward_point_settings__min_order_total_for_rp', 'value' => '100', 'branch_id' => null],
            ['key' => 'reward_point_settings__max_rp_per_order', 'value' => '', 'branch_id' => null],
            ['key' => 'reward_point_settings__redeem_amount_per_unit_rp', 'value' => '0.10', 'branch_id' => null],
            ['key' => 'reward_point_settings__min_order_total_for_redeem', 'value' => '', 'branch_id' => null],
            ['key' => 'reward_point_settings__min_redeem_point', 'value' => '', 'branch_id' => null],
            ['key' => 'reward_point_settings__max_redeem_point', 'value' => '', 'branch_id' => null],

            ['key' => 'send_email__send_invoice_via_email', 'value' => 0, 'branch_id' => null],
            ['key' => 'send_email__send_notification_via_email', 'value' => 0, 'branch_id' => null],
            ['key' => 'send_email__customer_due_reminder_via_email', 'value' => 0, 'branch_id' => null],
            ['key' => 'send_email__user_forget_password_via_email', 'value' => 0, 'branch_id' => null],
            ['key' => 'send_email__coupon_offer_via_email', 'value' => 0, 'branch_id' => null],

            ['key' => 'send_sms__send_invoice_via_sms', 'value' => 0, 'branch_id' => null],
            ['key' => 'send_sms__send_notification_via_sms', 'value' => 0, 'branch_id' => null],
            ['key' => 'send_sms__customer_due_reminder_via_sms', 'value' => 0, 'branch_id' => null],

            ['key' => 'add_sale__default_tax_ac_id', 'value' => null, 'branch_id' => null],
            ['key' => 'pos__default_tax_ac_id', 'value' => null, 'branch_id' => null],

            ['key' => 'manufacturing__production_voucher_prefix', 'value' => 'MF', 'branch_id' => null],
            ['key' => 'manufacturing__is_edit_ingredients_qty_in_production', 'value' => 1, 'branch_id' => null],
            ['key' => 'manufacturing__is_update_product_cost_and_price_in_production', 'value' => 1, 'branch_id' => null],

            ['key' => 'invoice_layout__add_sale_invoice_layout_id', 'value' => 1, 'branch_id' => null],
            ['key' => 'invoice_layout__pos_sale_invoice_layout_id', 'value' => 1, 'branch_id' => null],
        ];

        return $generalSettings;
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
