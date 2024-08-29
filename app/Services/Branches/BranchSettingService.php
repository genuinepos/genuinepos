<?php

namespace App\Services\Branches;

use App\Enums\BooleanType;
use App\Models\GeneralSetting;
use App\Services\CacheServiceInterface;

class BranchSettingService
{
    public function __construct(
        private CacheServiceInterface $cacheService
    ) {
    }

    public function addBranchSettings(int $branchId, ?int $parentBranchId = null, int $defaultInvoiceLayoutId, object $branchService, object $request): void
    {
        $branch = $branchService->singleBranch(id: $branchId, with: ['parentBranch', 'childBranches']);

        $numberOfChildBranch = $branch?->parentBranch && count($branch?->parentBranch?->childBranches) > 0 ? count($branch->parentBranch->childBranches) : '';

        $branchName = $branch?->parentBranch ? $branch?->parentBranch->name : $branch->name;

        $exp = explode(' ', $branchName);

        $branchPrefix = '';
        foreach ($exp as $ex) {
            $str = str_split($ex);
            $branchPrefix .= strtoupper($str[0]);
        }

        $generalSettings = [
            ['key' => 'business_or_shop__account_start_date', 'value' => $request->account_start_date, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'business_or_shop__financial_year_start_month', 'value' => $request->financial_year_start_month, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'business_or_shop__default_profit', 'value' => '0', 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'business_or_shop__stock_accounting_method', 'value' => $request->stock_accounting_method, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'business_or_shop__currency_id', 'value' => $request->currency_id, 'branch_id' => $branchId],
            ['key' => 'business_or_shop__currency_symbol', 'value' => $request->currency_symbol, 'branch_id' => $branchId],
            ['key' => 'business_or_shop__date_format', 'value' => $request->date_format, 'branch_id' => $branchId],
            ['key' => 'business_or_shop__time_format', 'value' => $request->time_format, 'branch_id' => $branchId],
            ['key' => 'business_or_shop__timezone', 'value' => $request->timezone, 'branch_id' => $branchId],
            ['key' => 'business_or_shop__auto_repayment_sales_and_purchase_return', 'value' => $request->auto_repayment_sales_and_purchase_return, 'branch_id' => $branchId],
            ['key' => 'business_or_shop__auto_repayment_purchase_and_sales_return', 'value' => $request->auto_repayment_purchase_and_sales_return, 'branch_id' => $branchId],

            ['key' => 'system__theme_color', 'value' => 'dark-theme', 'branch_id' => $branchId],
            ['key' => 'system__datatables_page_entry', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_multiple_pay', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_draft', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_quotation', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_suspend', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_discount', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_order_tax', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_credit_full_sale', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'pos__is_enabled_hold_invoice', 'value' => '1', 'branch_id' => $branchId],
            ['key' => 'email_settings__send_inv_via_email', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'email_settings__send_notice_via_sms', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'email_settings__customer_due_reminder_via_email', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'email_settings__customer_due_reminder_via_sms', 'value' => null, 'branch_id' => $branchId],
            ['key' => 'modules__manufacturing', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'modules__service', 'value' => 1, 'branch_id' => $branchId],
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
            ['key' => 'pos__is_disable_delivery_note', 'value' => '1', 'branch_id' => $branchId],
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
            ['key' => 'prefix__sales_invoice_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'SI', 'branch_id' => $branchId, 'branch_id' => $branchId],
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
            ['key' => 'prefix__stock_issue_voucher_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'STI', 'branch_id' => $branchId],
            ['key' => 'prefix__job_card_no_prefix', 'value' => $branchPrefix . $numberOfChildBranch . 'JOB', 'branch_id' => $branchId],
            ['key' => 'prefix__supplier_id', 'value' => 'S', 'branch_id' => $branchId],
            ['key' => 'prefix__customer_id', 'value' => $branchPrefix . 'C', 'branch_id' => $branchId],
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

            ['key' => 'print_page_size__add_sale_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__pos_sale_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__quotation_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__sales_order_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__draft_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__sales_return_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__purchase_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__purchase_order_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__purchase_return_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__transfer_stock_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__stock_adjustment_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__receipt_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__payment_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__expense_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__contra_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__payroll_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__payroll_payment_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__bom_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],
            ['key' => 'print_page_size__production_voucher_page_size', 'value' => 1, 'branch_id' => $branchId],

            ['key' => 'service_settings__default_status_id', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__default_checklist', 'value' => 'Display | Camera | Buttery', 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__product_configuration', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__default_problems_report', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__product_condition', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__terms_and_condition', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__custom_field_1_label', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__custom_field_2_label', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__custom_field_3_label', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__custom_field_4_label', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings__custom_field_5_label', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],

            ['key' => 'service_settings_pdf_label__show_customer_info', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__customer_label_name', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__show_contact_id', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__customer_id_label_name', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__show_customer_tax_no', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__customer_tax_no_label_name', 'value' => null, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__show_custom_field_1', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__show_custom_field_2', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__show_custom_field_3', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__show_custom_field_4', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__show_custom_field_5', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__label_width', 'value' => 75, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__label_height', 'value' => 55, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__customer_name_in_label', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__customer_address_in_label', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__customer_phone_in_label', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__customer_alt_phone_in_label', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__customer_email_in_label', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__sales_person_in_label', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__barcode_in_label', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__status_in_label', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__due_date_in_label', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__technician_in_label', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__problems_in_label_in_label', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__job_card_no_in_label', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__serial_in_label', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__model_in_label', 'value' => 1, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
            ['key' => 'service_settings_pdf_label__password_in_label', 'value' => 0, 'branch_id' => !isset($parentBranchId) ? $branchId : null],
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

    public function updateAndSync(array $settings, ?int $branchId): void
    {
        if (is_array($settings)) {

            foreach ($settings as $key => $value) {

                // if (isset($key) && isset($value)) {
                if (isset($key)) {

                    if (
                        ($key == 'prefix__payroll_voucher_prefix' || $key == 'prefix__payroll_payment_voucher_prefix') &&
                        config('generalSettings')['subscription']->features['hrm'] == BooleanType::False->value
                    ) {
                        continue;
                    }

                    if (
                        $key == 'prefix__job_card_no_prefix' &&
                        (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::False->value)
                    ) {
                        continue;
                    }

                    if (
                        ($key == 'prefix__sales_invoice_prefix' || $key == 'prefix__quotation_prefix' || $key == 'prefix__sales_order_prefix' || $key == 'prefix__sales_return_prefix') &&
                        config('generalSettings')['subscription']->features['sales'] == BooleanType::False->value
                    ) {
                        continue;
                    }

                    if (
                        ($key == 'prefix__purchase_invoice_prefix' || $key == 'prefix__purchase_order_prefix' || $key == 'prefix__purchase_return_prefix') &&
                        config('generalSettings')['subscription']->features['purchase'] == BooleanType::False->value
                    ) {
                        continue;
                    }

                    if (
                        ($key == 'prefix__supplier_id' || $key == 'prefix__customer_id') &&
                        config('generalSettings')['subscription']->features['contacts'] == BooleanType::False->value
                    ) {
                        continue;
                    }

                    if (
                        $key == 'prefix__stock_adjustment_prefix' &&
                        config('generalSettings')['subscription']->features['stock_adjustments'] == BooleanType::False->value
                    ) {
                        continue;
                    }

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
        }

        $this->cacheService->forgetGeneralSettingsCache();
    }

    public function deleteUnusedBranchSettings(?int $branchId, array $keys = []): void
    {
        if (count($keys) > 0) {

            foreach ($keys as $key) {

                $deleteBranchSetting = GeneralSetting::where('key', $key)->where('branch_id', $branchId)->first();
                if (isset($deleteBranchSetting)) {

                    $deleteBranchSetting->delete();
                }
            }
        }
    }

    public function singleBranchSetting(?int $branchId, string $key): ?object
    {
        $branchSetting = null;
        if (isset($key)) {

            $query = DB::table('general_settings')->where('key', $key)->where('branch_id', $branchId)->first();
        }

        return $branchSetting;
    }
}
