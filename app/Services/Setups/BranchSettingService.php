<?php

namespace App\Services\Setups;

use App\Models\GeneralSetting;
use App\Models\Setups\BranchSetting;

class BranchSettingService
{
    public function addBranchSettings(int $branchId, ?int $parentBranchId = null, int $defaultInvoiceLayoutId, object $branchService, object $request): void
    {
        $branch = $branchService->singleBranch(id: $branchId, with: ['parentBranch', 'childBranches']);

        $numberOfChildBranch = count($branch->childBranches) > 0 ? count($branch->childBranches) : '';

        $branchName = $branch?->parentBranch ? $branch?->parentBranch->name : $branch->name;

        $exp = explode(' ', $branchName);

        $branchPrefix = '';
        foreach ($exp as $ex) {
            $str = str_split($ex);
            $branchPrefix .= $str[0];
        }

        // $str1 = isset($exp[0]) ? str_split($exp[0])[0] : '';
        // $str2 = isset($exp[1]) ? str_split($exp[1])[0] : '';

        // $branchNamePrefix = strtoupper($str1 . $str2);

        // $addBranchSettings = new BranchSetting();
        // $addBranchSettings->branch_id = $branchId;
        // $addBranchSettings->invoice_prefix = $branchNamePrefix;
        // $addBranchSettings->quotation_prefix = $branchNamePrefix . 'Q';
        // $addBranchSettings->sales_order_prefix = $branchNamePrefix . 'SO';
        // $addBranchSettings->sales_return_prefix = $branchNamePrefix . 'SR';
        // $addBranchSettings->payment_voucher_prefix = $branchNamePrefix . 'PV';
        // $addBranchSettings->receipt_voucher_prefix = $branchNamePrefix . 'RV';
        // $addBranchSettings->purchase_invoice_prefix = $branchNamePrefix . 'PI';
        // $addBranchSettings->purchase_order_prefix = $branchNamePrefix . 'PO';
        // $addBranchSettings->purchase_return_prefix = $branchNamePrefix . 'PRV';
        // $addBranchSettings->add_sale_invoice_layout_id = $defaultInvoiceLayoutId;
        // $addBranchSettings->pos_sale_invoice_layout_id = $defaultInvoiceLayoutId;
        // $addBranchSettings->save();

        $generalSettings = [
            ['key' => 'business_or_shop__account_start_date', 'value' => $request->account_start_date, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'business_or_shop__financial_year_start_month', 'value' => $request->financial_year_start_month, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'business_or_shop__default_profit', 'value' => '0', 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'business_or_shop__stock_accounting_method', 'value' => $request->stock_accounting_method, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'business_or_shop__currency_id', 'value' => '2', 'branch_id' => $branchId],
            ['key' => 'business_or_shop__currency_symbol', 'value' => '$', 'branch_id' => $branchId],
            ['key' => 'business_or_shop__date_format', 'value' => $request->date_format, 'branch_id' => $branchId],
            ['key' => 'business_or_shop__time_format', 'value' => $request->time_format, 'branch_id' => $branchId],
            ['key' => 'business_or_shop__timezone', 'value' => $request->timezone, 'branch_id' => $branchId],

            ['key' => 'system__theme_color', 'value' => 'dark-theme', 'branch_id' => $branchId],
            ['key' => 'system__datatables_page_entry', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_multiple_pay', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_draft', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_quotation', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_suspend', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_discount', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_order_tax', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_credit_full_sale', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_hold_invoice', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'email_settings__send_inv_via_email', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'email_settings__send_notice_via_sms', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'email_settings__customer_due_reminder_via_email', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'email_settings__customer_due_reminder_via_sms', 'value' => null, 'branch_id' => $branchId],
            // ['id' => '39', 'key' => 'email_config__MAIL_MAILER', 'value' => null, 'branch_id' => null],
            // ['id' => '40', 'key' => 'email_config__MAIL_HOST', 'value' => null, 'branch_id' => null],
            // ['id' => '41', 'key' => 'email_config__MAIL_PORT', 'value' => null, 'branch_id' => null],
            // ['id' => '42', 'key' => 'email_config__MAIL_USERNAME', 'value' => null, 'branch_id' => null],
            // ['id' => '43', 'key' => 'email_config__MAIL_PASSWORD', 'value' => null, 'branch_id' => null],
            // ['id' => '44', 'key' => 'email_config__MAIL_ENCRYPTION', 'value' => null, 'branch_id' => null],
            // ['id' => '45', 'key' => 'email_config__MAIL_FROM_ADDRESS', 'value' => null, 'branch_id' => null],
            // ['id' => '46', 'key' => 'email_config__MAIL_FROM_NAME', 'value' => null, 'branch_id' => null],
            // ['id' => '47', 'key' => 'email_config__MAIL_ACTIVE', 'value' => null, 'branch_id' => null],
            ['key' => 'modules__manufacturing', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'modules__service', 'value' => null, 'branch_id' => $branchId],
            // ['id' => '53', 'key' => 'sms__SMS_URL', 'value' => null, 'branch_id' => null],
            // ['id' => '54', 'key' => 'sms__API_KEY', 'value' => null, 'branch_id' => null],
            // ['id' => '55', 'key' => 'sms__SENDER_ID', 'value' => null, 'branch_id' => null],
            // ['id' => '56', 'key' => 'sms__SMS_ACTIVE', 'value' => null, 'branch_id' => null],
            ['key' => 'product__product_code_prefix', 'value' => $branchPrefix . $numberOfChildBranch, 'branch_id' => $branchId],
            ['key' => 'product__default_unit_id', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'product__is_enable_brands', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'product__is_enable_categories', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'product__is_enable_sub_categories', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'product__is_enable_price_tax', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'product__is_enable_warranty', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'add_sale__default_sale_discount', 'value' => '0.00', 'branch_id' => $branchId],
            ['key' => 'add_sale__default_price_group_id', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'add_sale__default_tax_ac_id', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_disable_draft', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_disable_quotation', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_disable_challan', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_disable_hold_invoice', 'value' => '0', 'branch_id' => $branchId],
            ['key' => 'pos__is_disable_multiple_pay', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_show_recent_transactions', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_disable_discount', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_disable_order_tax', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_show_credit_sale_button', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_show_partial_sale_button', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__default_tax_ac_id', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'purchase__is_edit_pro_price', 'value' => '0', 'branch_id' => $branchId],
            ['key' => 'purchase__is_enable_status', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'purchase__is_enable_lot_no', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'dashboard__view_stock_expiry_alert_for', 'value' => '31', 'branch_id' => $branchId],
            ['key' => 'prefix__sales_invoice_prefix', 'value' => $branchPrefix . $numberOfChildBranch.'SI', 'branch_id' => $branchId, 'branch_id' => $branchId],
            ['key' => 'prefix__quotation_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'Q', 'branch_id' => $branchId],
            ['key' => 'prefix__sales_order_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'SO', 'branch_id' => $branchId],
            ['key' => 'prefix__sales_return_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'SR', 'branch_id' => $branchId],
            ['key' => 'prefix__payment_voucher_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'PV', 'branch_id' => $branchId],
            ['key' => 'prefix__receipt_voucher_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'RV', 'branch_id' => $branchId],
            ['key' => 'prefix__expense_voucher_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'EX', 'branch_id' => $branchId],
            ['key' => 'prefix__contra_voucher_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'CO', 'branch_id' => $branchId],
            ['key' => 'prefix__purchase_invoice_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'PI', 'branch_id' => $branchId],
            ['key' => 'prefix__purchase_order_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'PO', 'branch_id' => $branchId],
            ['key' => 'prefix__purchase_return_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'PR', 'branch_id' => $branchId],
            ['key' => 'prefix__stock_adjustment_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'SA', 'branch_id' => $branchId],
            ['key' => 'prefix__payroll_voucher_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'PRL', 'branch_id' => $branchId],
            ['key' => 'prefix__payroll_payment_voucher_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'PRLP', 'branch_id' => $branchId],
            ['key' => 'prefix__supplier_id', 'value' => 'S-', 'branch_id' => $branchId],
            ['key' => 'prefix__customer_id', 'value' => 'C-', 'branch_id' => $branchId],
            // ['id' => '103', 'key' => 'email_setting__MAIL_MAILER', 'value' => 'smtp', 'branch_id' => null],
            // ['id' => '104', 'key' => 'email_setting__MAIL_HOST', 'value' => 'smtp.gmail.com', 'branch_id' => null],
            // ['id' => '105', 'key' => 'email_setting__MAIL_PORT', 'value' => '587', 'branch_id' => null],
            // ['id' => '106', 'key' => 'email_setting__MAIL_USERNAME', 'value' => 's1@gmail.com', 'branch_id' => null],
            // ['id' => '107', 'key' => 'email_setting__MAIL_PASSWORD', 'value' => 'speeddigit@54321', 'branch_id' => null],
            // ['id' => '108', 'key' => 'email_setting__MAIL_ENCRYPTION', 'value' => 'tls', 'branch_id' => null],
            // ['id' => '109', 'key' => 'email_setting__MAIL_FROM_ADDRESS', 'value' => 's1@gmail.com', 'branch_id' => null],
            // ['id' => '110', 'key' => 'email_setting__MAIL_FROM_NAME', 'value' => 'SpeedDigit', 'branch_id' => null],
            // ['id' => '111', 'key' => 'email_setting__MAIL_ACTIVE', 'value' => '1', 'branch_id' => null],
            ['key' => 'modules__purchases', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'modules__add_sale', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'modules__pos', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'modules__transfer_stock', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'modules__stock_adjustments', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'modules__accounting', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'modules__contacts', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'modules__hrms', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'modules__manage_task', 'value' => '1', 'branch_id' => $branchId],

            ['key' => 'reward_point_settings__enable_cus_point', 'value' => '0', 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'reward_point_settings__point_display_name', 'value' => 'Reward Point', 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'reward_point_settings__amount_for_unit_rp', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'reward_point_settings__min_order_total_for_rp', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'reward_point_settings__max_rp_per_order', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'reward_point_settings__redeem_amount_per_unit_rp', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'reward_point_settings__min_order_total_for_redeem', 'value' => '', 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'reward_point_settings__min_redeem_point', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'reward_point_settings__max_redeem_point', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],

            ['key' => 'send_email__send_invoice_via_email', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'send_email__send_notification_via_email', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'send_email__customer_due_reminder_via_email', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'send_email__user_forget_password_via_email', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'send_email__coupon_offer_via_email', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],

            ['key' => 'send_sms__send_invoice_via_sms', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'send_sms__send_notification_via_sms', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'send_sms__customer_due_reminder_via_sms', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],

            ['key' => 'manufacturing__production_voucher_prefix', 'value' =>  $branchPrefix . $numberOfChildBranch . 'MF', 'branch_id' => $branchId],
            ['key' => 'manufacturing__is_edit_ingredients_qty_in_production', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'manufacturing__is_update_product_cost_and_price_in_production', 'value' => 1, 'branch_id' => $branchId],

            ['key' => 'invoice_layout__add_sale_invoice_layout_id', 'value' => $defaultInvoiceLayoutId, 'branch_id' => $branchId],
            ['key' => 'invoice_layout__pos_sale_invoice_layout_id', 'value' => $defaultInvoiceLayoutId, 'branch_id' => $branchId],
        ];

        foreach ($generalSettings as $setting) {

            if (isset($setting['branch_id'])) {

                GeneralSetting::insert([
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'branch_id' => $setting['branch_id'],
                ]);
            }
        }
    }

    public function updateAndSync(array $settings, int $branchId): bool
    {
        if (is_array($settings)) {

            foreach ($settings as $key => $value) {

                if (isset($key) && isset($value)) {

                    $branchSetting = GeneralSetting::where('branch_id', $branchId)->where('key', $key)->first();
                    if ($branchSetting) {

                        $branchSetting->key = $key;
                        $branchSetting->value = $value;
                        $branchSetting->save();
                    } else {

                        $addBranchSetting = new GeneralSetting();
                        $addBranchSetting->key = $key;
                        $addBranchSetting->value = $value;
                        $addBranchSetting->branch_id = $branchId;
                        $addBranchSetting->save();
                    }
                }
            }

            return true;
        }

        return false;
    }

    public function singleBranchSetting(?int $branchId, array $with = null)
    {
        $query = BranchSetting::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('branch_id', $branchId)->first();
    }
}
