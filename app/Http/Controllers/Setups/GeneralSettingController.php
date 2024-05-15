<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Accounts\AccountService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\InvoiceLayoutService;
use App\Services\GeneralSettingServiceInterface;
use App\Http\Requests\Setups\GeneralSettingsRequest;
use Intervention\Image\Facades\Image;

class GeneralSettingController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private UnitService $unitService,
        private CurrencyService $currencyService,
        private TimezoneService $timezoneService,
        private PriceGroupService $priceGroupService,
        private InvoiceLayoutService $invoiceLayoutService,
        private GeneralSettingServiceInterface $generalSettingService
    ) {
    }

    public function index()
    {
        abort_if(!$this->generalSettingService->generalSettingsPermission(), 403);

        $generalSettings = config('generalSettings');
        $currencies = $this->currencyService->currencies();
        $units = $this->unitService->units()->where('base_unit_id', null)->get();
        $priceGroups = $this->priceGroupService->priceGroups()->where('status', 'Active')->get();
        $timezones = $this->timezoneService->all();
        $invoiceLayouts = $this->invoiceLayoutService->invoiceLayouts(branchId: null);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        if (auth()->user()->branch_id) {

            return redirect()->route('branches.settings.index', auth()->user()->branch_id);
        }

        return view('setups.general_settings.index', compact(
            'generalSettings',
            'currencies',
            'timezones',
            'units',
            'priceGroups',
            'taxAccounts',
            'invoiceLayouts',
        ));
    }

    public function businessSettings(GeneralSettingsRequest $request)
    {
        $generalSettings = config('generalSettings');
        $businessLogo = null;

        if ($request->hasFile('business_logo')) {

            $dir = public_path('uploads/business_logo/');

            if (isset($generalSettings['business_or_shop__business_logo'])) {

                $businessLogo = $generalSettings['business_or_shop__business_logo'];

                if (file_exists($dir . $businessLogo)) {

                    unlink($dir . $businessLogo);
                }
            }

            if (!\File::isDirectory($dir)) {

                \File::makeDirectory($dir, 493, true);
            }

            $logo = $request->file('business_logo');
            $logoName = uniqid() . '-' . '.' . $logo->getClientOriginalExtension();
            Image::make($logo)->resize(200, 60)->save($dir . $logoName);
            $businessLogo = $logoName;
        } else {

            $businessLogo = isset($generalSettings['business_or_shop__business_logo']) ? $generalSettings['business_or_shop__business_logo'] : null;
        }

        $settings = [
            'business_or_shop__business_name' => $request->business_name,
            'business_or_shop__address' => $request->address,
            'business_or_shop__phone' => $request->phone,
            'business_or_shop__email' => $request->email,
            'business_or_shop__account_start_date' => $request->account_start_date,
            'business_or_shop__financial_year_start_month' => $request->financial_year_start_month,
            'business_or_shop__default_profit' => $request->default_profit ? $request->default_profit : 0,
            'business_or_shop__currency_id' => $request->currency_id,
            'business_or_shop__currency_symbol' => $request->currency_symbol,
            'business_or_shop__date_format' => $request->date_format,
            'business_or_shop__stock_accounting_method' => $request->stock_accounting_method,
            'business_or_shop__time_format' => $request->time_format,
            'business_or_shop__business_logo' => $businessLogo,
            'business_or_shop__timezone' => $request->timezone,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Business settings updated successfully'));
    }

    public function dashboardSettings(Request $request)
    {
        $settings = [
            'dashboard__view_stock_expiry_alert_for' => $request->view_stock_expiry_alert_for,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Dashboard settings updated successfully.'));
    }

    public function productSettings(Request $request)
    {
        $settings = [
            'product__product_code_prefix' => $request->product_code_prefix,
            'product__default_unit_id' => $request->default_unit_id,
            'product__is_enable_brands' => $request->is_enable_brands,
            'product__is_enable_categories' => $request->is_enable_categories,
            'product__is_enable_sub_categories' => $request->is_enable_sub_categories,
            'product__is_enable_price_tax' => $request->is_enable_price_tax,
            'product__is_enable_warranty' => $request->is_enable_warranty,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Product settings updated successfully'));
    }

    public function purchaseSettings(Request $request)
    {
        $settings = [
            'purchase__is_edit_pro_price' => $request->is_edit_pro_price,
            'purchase__is_enable_lot_no' => $request->is_enable_lot_no,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Purchase settings updated successfully.'));
    }

    public function manufacturingSettings(Request $request)
    {
        $settings = [
            'manufacturing__production_voucher_prefix' => $request->production_voucher_prefix,
            'manufacturing__is_edit_ingredients_qty_in_production' => $request->is_edit_ingredients_qty_in_production,
            'manufacturing__is_update_product_cost_and_price_in_production' => $request->is_update_product_cost_and_price_in_production,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Manufacturing settings updated successfully.'));
    }

    public function addSaleSettings(Request $request)
    {
        $settings = [
            'add_sale__default_sale_discount' => $request->default_sale_discount,
            'add_sale__sales_commission' => $request->sales_commission,
            'add_sale__default_tax_ac_id' => $request->default_tax_ac_id,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Sale settings updated successfully'));
    }

    public function posSettings(Request $request)
    {
        $settings = [
            'pos__is_enabled_multiple_pay' => $request->is_enabled_multiple_pay,
            'pos__is_enabled_draft' => $request->is_enabled_draft,
            'pos__is_enabled_quotation' => $request->is_enabled_quotation,
            'pos__is_enabled_suspend' => $request->is_enabled_suspend,
            'pos__is_enabled_discount' => $request->is_enabled_discount,
            'pos__is_enabled_order_tax' => $request->is_enabled_order_tax,
            'pos__is_show_recent_transactions' => $request->is_show_recent_transactions,
            'pos__is_enabled_credit_full_sale' => $request->is_enabled_credit_full_sale,
            'pos__is_enabled_hold_invoice' => $request->is_enabled_hold_invoice,
            'pos__default_tax_ac_id' => $request->default_tax_ac_id,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('POS settings updated successfully'));
    }

    public function prefixSettings(Request $request)
    {
        $settings = [
            'prefix__sales_invoice_prefix' => $request->sales_invoice_prefix,
            'prefix__quotation_prefix' => $request->quotation_prefix,
            'prefix__sales_order_prefix' => $request->sales_order_prefix,
            'prefix__sales_return_prefix' => $request->sales_return_prefix,
            'prefix__payment_voucher_prefix' => $request->payment_voucher_prefix,
            'prefix__receipt_voucher_prefix' => $request->receipt_voucher_prefix,
            'prefix__expense_voucher_prefix' => $request->expense_voucher_prefix,
            'prefix__contra_voucher_prefix' => $request->contra_voucher_prefix,
            'prefix__purchase_invoice_prefix' => $request->purchase_invoice_prefix,
            'prefix__purchase_order_prefix' => $request->purchase_order_prefix,
            'prefix__purchase_return_prefix' => $request->purchase_return_prefix,
            'prefix__stock_adjustment_prefix' => $request->stock_adjustment_prefix,
            'prefix__payroll_voucher_prefix' => $request->payroll_voucher_prefix ? $request->payroll_voucher_prefix : 'PRL',
            'prefix__payroll_payment_voucher_prefix' => $request->payroll_payment_voucher_prefix ? $request->payroll_payment_voucher_prefix : 'RRLP',
            'prefix__stock_issue_voucher_prefix' => $request->stock_issue_voucher_prefix ? $request->stock_issue_voucher_prefix : 'ST',
            'prefix__supplier_id' => $request->supplier_id,
            'prefix__customer_id' => $request->customer_id,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Prefix settings updated successfully'));
    }

    public function invoiceLayoutSettings(Request $request)
    {
        $settings = [
            'invoice_layout__add_sale_invoice_layout_id' => $request->add_sale_invoice_layout_id,
            'invoice_layout__pos_sale_invoice_layout_id' => $request->pos_sale_invoice_layout_id,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Invoice Layout settings updated successfully'));
    }

    public function printPageSizeSettings(Request $request)
    {
        $settings = [
            'print_page_size__add_sale_page_size' => $request->add_sale_page_size,
            'print_page_size__pos_sale_page_size' => $request->pos_sale_page_size,
            'print_page_size__quotation_page_size' => $request->quotation_page_size,
            'print_page_size__sales_order_page_size' => $request->sales_order_page_size,
            'print_page_size__draft_page_size' => $request->draft_page_size,
            'print_page_size__sales_return_page_size' => $request->sales_return_page_size,
            'print_page_size__purchase_page_size' => $request->purchase_page_size,
            'print_page_size__purchase_order_page_size' => $request->purchase_order_page_size,
            'print_page_size__purchase_return_page_size' => $request->purchase_return_page_size,
            'print_page_size__transfer_stock_voucher_page_size' => $request->transfer_stock_voucher_page_size,
            'print_page_size__stock_adjustment_voucher_page_size' => $request->stock_adjustment_voucher_page_size,
            'print_page_size__receipt_voucher_page_size' => $request->receipt_voucher_page_size,
            'print_page_size__payment_voucher_page_size' => $request->payment_voucher_page_size,
            'print_page_size__expense_voucher_page_size' => $request->payment_voucher_page_size,
            'print_page_size__contra_voucher_page_size' => $request->payment_voucher_page_size,
            'print_page_size__payroll_voucher_page_size' => $request->payroll_voucher_page_size,
            'print_page_size__payroll_payment_voucher_page_size' => $request->payroll_payment_voucher_page_size,
            'print_page_size__bom_voucher_page_size' => $request->bom_voucher_page_size,
            'print_page_size__production_voucher_page_size' => $request->production_voucher_page_size,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Print page size settings updated successfully'));
    }

    public function systemSettings(Request $request)
    {
        $settings = [
            'system__theme_color' => $request->theme_color,
            'system__datatables_page_entry' => $request->datatable_page_entry,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('System settings updated successfully.'));
    }

    public function moduleSettings(Request $request)
    {
        $settings = [
            'modules__purchases' => isset($request->purchases) ? 1 : 0,
            'modules__add_sale' => isset($request->add_sale) ? 1 : 0,
            'modules__pos' => isset($request->pos) ? 1 : 0,
            'modules__transfer_stock' => isset($request->transfer_stock) ? 1 : 0,
            'modules__stock_adjustments' => isset($request->stock_adjustments) ? 1 : 0,
            'modules__accounting' => isset($request->accounting) ? 1 : 0,
            'modules__contacts' => isset($request->contacts) ? 1 : 0,
            'modules__hrms' => isset($request->hrms) ? 1 : 0,
            'modules__manage_task' => isset($request->manage_task) ? 1 : 0,
            'modules__manufacturing' => isset($request->manufacturing) ? 1 : 0,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Modules settings updated successfully'));
    }

    public function sendEmailSettings(Request $request)
    {
        $settings = [
            'send_email__send_invoice_via_email' => isset($request->send_invoice_via_email) ? 1 : 0,
            'send_email__send_notification_via_email' => isset($request->send_notification_via_email) ? 1 : 0,
            'send_email__customer_due_reminder_via_email' => isset($request->customer_due_reminder_via_email) ? 1 : 0,
            'send_email__user_forget_password_via_email' => isset($request->user_forget_password_via_email) ? 1 : 0,
            'send_email__coupon_offer_via_email' => isset($request->coupon_offer_via_email) ? 1 : 0,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Send Email settings updated successfully'));
    }

    public function sendSmsSettings(Request $request)
    {
        $settings = [
            'send_sms__send_invoice_via_sms' => isset($request->send_invoice_via_sms) ? 1 : 0,
            'send_sms__send_notification_via_sms' => isset($request->send_notification_via_sms) ? 1 : 0,
            'send_sms__customer_due_reminder_via_sms' => isset($request->customer_due_reminder_via_sms) ? 1 : 0,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Send SMS settings updated successfully'));
    }

    public function rewardPointSettings(Request $request)
    {
        $settings = [
            'reward_point_settings__enable_cus_point' => isset($request->enable_cus_point) ? 1 : 0,
            'reward_point_settings__point_display_name' => $request->point_display_name ? $request->point_display_name : 0,
            'reward_point_settings__amount_for_unit_rp' => $request->amount_for_unit_rp ? $request->amount_for_unit_rp : 0,
            'reward_point_settings__min_order_total_for_rp' => $request->min_order_total_for_rp ? $request->min_order_total_for_rp : 0,
            'reward_point_settings__max_rp_per_order' => $request->max_rp_per_order ? $request->max_rp_per_order : '',
            'reward_point_settings__redeem_amount_per_unit_rp' => $request->redeem_amount_per_unit_rp ? $request->redeem_amount_per_unit_rp : 0,
            'reward_point_settings__min_order_total_for_redeem' => $request->min_order_total_for_redeem ? $request->min_order_total_for_redeem : '',
            'reward_point_settings__min_redeem_point' => $request->min_redeem_point ? $request->min_redeem_point : '',
            'reward_point_settings__max_redeem_point' => $request->max_redeem_point ? $request->max_redeem_point : '',
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Reward point settings updated successfully'));
    }

    public function deleteBusinessLogo(Request $request)
    {
        $this->generalSettingService->deleteBusinessLogo();

        return response()->json(__('Business logo is removed successfully'));
    }
}
