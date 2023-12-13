<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

Artisan::command('db:v001', function () {
    echo 'DB v001 migrating...' . PHP_EOL;
    
    Schema::disableForeignKeyConstraints();
    DB::statement(defineNewSQLForShoptSettings());
    Schema::enableForeignKeyConstraints();

    Schema::table('general_settings', function (Blueprint $table) {
        if (!Schema::hasColumn('general_settings', 'parent_branch_id')) {
            $table->foreignId('parent_branch_id')->nullable()->references('id')->on('branches')->onDelete('CASCADE');
        }
    });
});

function defineNewSQLForShoptSettings()
{
    $sql = <<<SQL
        INSERT INTO `general_settings` (`key`, `value`, `branch_id`) VALUES
        ('dashboard__view_stock_expiry_alert_for', '31', 28),
        ('product__product_code_prefix', 'GP', 28),
        ('product__default_unit_id', '2', 28),
        ('product__is_enable_brands', '1', 28),
        ('product__is_enable_categories', '1', 28),
        ('product__is_enable_sub_categories', '1', 28),
        ('product__is_enable_price_tax', '1', 28),
        ('product__is_enable_warranty', '1', 28),
        ('purchase__is_edit_pro_price', '1', 28),
        ('purchase__is_enable_lot_no', '1', 28),
        ('add_sale__default_sale_discount', '20', 28),
        ('add_sale__default_price_group_id', 'null', 28),
        ('add_sale__default_tax_ac_id', '81', 28),
        ('pos__is_enabled_multiple_pay', '1', 28),
        ('pos__is_enabled_draft', '1', 28),
        ('pos__is_enabled_quotation', '1', 28),
        ('pos__is_enabled_suspend', '1', 28),
        ('pos__is_enabled_discount', '1', 28),
        ('pos__is_enabled_order_tax', '1', 28),
        ('pos__is_show_recent_transactions', '1', 28),
        ('pos__is_enabled_credit_full_sale', '1', 28),
        ('pos__is_enabled_hold_invoice', '1', 28),
        ('pos__default_tax_ac_id', '159', 28),
        ('prefix__invoice_prefix', 'SI', 28),
        ('prefix__quotation_prefix', 'Q', 28),
        ('prefix__sales_order_prefix', 'SO', 28),
        ('prefix__sales_return_prefix', 'SRV', 28),
        ('prefix__payment_voucher_prefix', 'PV', 28),
        ('prefix__receipt_voucher_prefix', 'RV', 28),
        ('prefix__purchase_invoice_prefix', 'PI', 28),
        ('prefix__purchase_order_prefix', 'PO', 28),
        ('prefix__purchase_return_prefix', 'PRV', 28),
        ('prefix__stock_adjustment_prefix', 'SAV', 28),
        ('invoice_layout__add_sale_invoice_layout_id', '10', 28),
        ('invoice_layout__pos_sale_invoice_layout_id', '10', 28),
        ('system__theme_color', 'dark-theme', 28),
        ('system__datatables_page_entry', '50', 28),
        ('reward_point_settings__enable_cus_point', '1', 28),
        ('reward_point_settings__point_display_name', 'Reward Point', 28),
        ('reward_point_settings__amount_for_unit_rp', '10', 28),
        ('reward_point_settings__min_order_total_for_rp', '100', 28),
        ('reward_point_settings__max_rp_per_order', '', 28),
        ('reward_point_settings__redeem_amount_per_unit_rp', '0.10', 28),
        ('reward_point_settings__min_order_total_for_redeem', '', 28),
        ('reward_point_settings__min_redeem_point', '', 28),
        ('reward_point_settings__max_redeem_point', '', 28),
        ('modules__purchases', '1', 28),
        ('modules__add_sale', '1', 28),
        ('modules__pos', '1', 28),
        ('modules__transfer_stock', '1', 28),
        ('modules__stock_adjustments', '1', 28),
        ('modules__accounting', '1', 28),
        ('modules__contacts', '1', 28),
        ( 'modules__hrms', '1', 28),
        ( 'modules__manage_task', '1', 28),
        ( 'modules__manufacturing', '1', 28),
        ( 'modules__service', '1', 28),
        ( 'send_email__send_invoice_via_email', '1', 28),
        ( 'send_email__send_notification_via_email', '1', 28),
        ( 'send_email__customer_due_reminder_via_email', '1', 28),
        ( 'send_email__user_forget_password_via_email', '1', 28),
        ( 'send_email__coupon_offer_via_email', '1', 28),
        ( 'send_sms__send_invoice_via_sms', '1', 28),
        ( 'send_sms__send_notification_via_sms', '1', 28),
        ( 'send_sms__customer_due_reminder_via_sms', '1', 28)
        SQL;

    return $sql;
}
