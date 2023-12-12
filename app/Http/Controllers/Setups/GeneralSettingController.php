<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Accounts\AccountService;
use App\Services\Products\PriceGroupService;
use App\Services\GeneralSettingServiceInterface;

class GeneralSettingController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private UnitService $unitService,
        private CurrencyService $currencyService,
        private TimezoneService $timezoneService,
        private PriceGroupService $priceGroupService,
        private GeneralSettingServiceInterface $generalSettingService
    ) {
    }

    public function index()
    {
        if (!auth()->user()->can('general_settings')) {

            abort(403, 'Access Forbidden.');
        }

        $generalSettings = config('generalSettings');
        $currencies = $this->currencyService->currencies();
        $units = $this->unitService->units()->where('base_unit_id', null)->get();
        $priceGroups = $this->priceGroupService->priceGroups()->where('status', 'Active')->get();
        $timezones = $this->timezoneService->all();


        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        return view('setups.general_settings.index', compact(
            'generalSettings',
            'currencies',
            'timezones',
            'units',
            'priceGroups',
            'taxAccounts'
        ));
    }

    // Add business settings
    public function businessSettings(Request $request)
    {
        $generalSettings = config('generalSettings');
        $business_logo = null;

        if ($request->hasFile('business_logo')) {

            if ($generalSettings['business__business_logo'] != null) {

                $bLogo = $generalSettings['business__business_logo'];

                if (file_exists(public_path('uploads/business_logo/' . $bLogo))) {

                    unlink(public_path('uploads/business_logo/' . $bLogo));
                }
            }

            $logo = $request->file('business_logo');
            $logoName = uniqid() . '-' . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/business_logo/'), $logoName);
            $business_logo = $logoName;
        } else {

            $business_logo = $generalSettings['business__business_logo'] != null ? $generalSettings['business__business_logo'] : null;
        }

        $settings = [
            'business__business_name' => $request->shop_name,
            'business__address' => $request->address,
            'business__phone' => $request->phone,
            'business__email' => $request->email,
            'business__account_start_date' => $request->account_start_date,
            'business__financial_year_start_month' => $request->financial_year_start_month,
            'business__default_profit' => $request->default_profit ? $request->default_profit : 0,
            'business__currency' => $request->currency,
            'business__currency_placement' => $request->currency_placement,
            'business__date_format' => $request->date_format,
            'business__stock_accounting_method' => $request->stock_accounting_method,
            'business__time_format' => $request->time_format,
            'business__business_logo' => $business_logo,
            'business__timezone' => $request->timezone,
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

        return response()->json(__('Product settings updated Successfully'));
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
            'prefix__purchase_invoice' => $request->purchase_invoice,
            'prefix__sale_invoice' => $request->sale_invoice,
            'prefix__purchase_return' => $request->purchase_return,
            // 'prefix__stock_transfer' => $request->stock_transfer,
            'prefix__stock_adjustment' => $request->stock_adjustment,
            'prefix__sale_return' => $request->sale_return,
            'prefix__expenses' => $request->expenses,
            'prefix__supplier_id' => $request->supplier_id,
            'prefix__customer_id' => $request->customer_id,
            'prefix__payment' => $request->payment,
            'prefix__receipt' => $request->receipt,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('Prefix settings updated Successfully'));
    }

    public function systemSettings(Request $request)
    {
        $settings = [
            'system__theme_color' => $request->theme_color,
            'system__datatables_page_entry' => $request->datatable_page_entry,
        ];

        $this->generalSettingService->updateAndSync($settings);

        return response()->json(__('System settings updated Successfully.'));
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
            'modules__service' => isset($request->service) ? 1 : 0,
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

        return response()->json(__('Reward point settings updated Successfully'));
    }
}
