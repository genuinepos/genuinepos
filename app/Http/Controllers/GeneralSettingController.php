<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Unit;
use App\Services\GeneralSettingServiceInterface;
use App\Utils\TimeZone;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function __construct(
        private GeneralSettingServiceInterface $generalSettingService
    ) {
    }

    public function index()
    {
        if (! auth()->user()->can('g_settings')) {
            abort(403, 'Access Forbidden.');
        }
        $generalSettings = config('generalSettings');
        $currencies = Currency::all();
        $units = Unit::all();
        $timezones = TimeZone::all();

        return view('settings.general_settings.index', compact(
            'generalSettings',
            'currencies',
            'timezones',
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
                if (file_exists(public_path('uploads/business_logo/'.$bLogo))) {
                    unlink(public_path('uploads/business_logo/'.$bLogo));
                }
            }
            $logo = $request->file('business_logo');
            $logoName = uniqid().'-'.'.'.$logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/business_logo/'), $logoName);
            $business_logo = $logoName;
        } else {
            $business_logo = $generalSettings['business__business_logo'] != null ? $generalSettings['business__business_logo'] : null;
        }

        $settings = [
            'business__shop_name' => $request->shop_name,
            'business__address' => $request->address,
            'business__phone' => $request->phone,
            'business__email' => $request->email,
            'business__start_date' => $request->start_date,
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

        return response()->json('Business settings updated successfully');
    }

    // Add tax settings
    public function taxSettings(Request $request)
    {
        $settings = [
            'tax__tax_1_name' => $request->tax_1_name,
            'tax__tax_1_no' => $request->tax_1_no,
            'tax__tax_2_name' => $request->tax_2_name,
            'tax__tax_2_no' => $request->tax_2_no,
            'tax__is_tax_en_purchase_sale' => isset($request->is_tax_en_purchase_sale) ? 1 : 0,
        ];
        $this->generalSettingService->updateAndSync($settings);

        return response()->json('Tax settings updated successfully');
    }

    public function dashboardSettings(Request $request)
    {
        $settings = [
            'dashboard__view_stock_expiry_alert_for' => $request->view_stock_expiry_alert_for,
        ];
        $this->generalSettingService->updateAndSync($settings);

        return response()->json('Dashboard settings updated successfully.');
    }

    public function prefixSettings(Request $request)
    {
        $settings = [
            'prefix__purchase_invoice' => $request->purchase_invoice,
            'prefix__sale_invoice' => $request->sale_invoice,
            'prefix__purchase_return' => $request->purchase_return,
            'prefix__stock_transfer' => $request->stock_transfer,
            'prefix__stock_adjustment' => $request->stock_djustment,
            'prefix__sale_return' => $request->sale_return,
            'prefix__expenses' => $request->expenses,
            'prefix__supplier_id' => $request->supplier_id,
            'prefix__customer_id' => $request->customer_id,
            'prefix__purchase_payment' => $request->purchase_payment,
            'prefix__sale_payment' => $request->sale_payment,
            'prefix__expanse_payment' => $request->expanse_payment,
        ];
        $this->generalSettingService->updateAndSync($settings);

        return response()->json('Prefix settings updated Successfully');
    }

    public function systemSettings(Request $request)
    {
        $settings = [
            'system__theme_color' => $request->theme_color,
            'system__datatables_page_entry' => $request->datatable_page_entry,
        ];
        $this->generalSettingService->updateAndSync($settings);

        return response()->json('System settings updated Successfully.');
    }

    public function moduleSettings(Request $request)
    {
        $settings = [
            'modules__purchases' => isset($request->purchases) ? 1 : 0,
            'modules__add_sale' => isset($request->add_sale) ? 1 : 0,
            'modules__pos' => isset($request->pos) ? 1 : 0,
            'modules__transfer_stock' => isset($request->transfer_stock) ? 1 : 0,
            'modules__stock_adjustment' => isset($request->stock_adjustment) ? 1 : 0,
            'modules__expenses' => isset($request->expenses) ? 1 : 0,
            'modules__accounting' => isset($request->accounting) ? 1 : 0,
            'modules__contacts' => isset($request->contacts) ? 1 : 0,
            'modules__hrms' => isset($request->hrms) ? 1 : 0,
            'modules__requisite' => isset($request->requisite) ? 1 : 0,
            'modules__manufacturing' => isset($request->manufacturing) ? 1 : 0,
            'modules__service' => isset($request->service) ? 1 : 0,
        ];
        $this->generalSettingService->updateAndSync($settings);

        return response()->json('Modules settings updated successfully');
    }

    public function SendEmailSmsSettings(Request $request)
    {
        $settings = [
            'email_settings__send_inv_via_email' => isset($request->send_inv_via_email) ? 1 : 0,
            'email_settings__send_notice_via_sms' => isset($request->send_notice_via_sms) ? 1 : 0,
            'email_settings__customer_due_reminder_via_email' => isset($request->cmr_due_rmdr_via_email) ? 1 : 0,
            'email_settings__customer_due_reminder_via_sms' => isset($request->cmr_due_rmdr_via_sms) ? 1 : 0,
        ];
        $this->generalSettingService->updateAndSync($settings);

        return response()->json('Send Email & SMS settings updated successfully');
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

        return response()->json('Reward point settings updated Successfully');
    }
}
