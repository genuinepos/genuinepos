<?php

namespace App\Http\Controllers\Branches;

use App\Enums\PrintPageSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Branches\BranchService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Accounts\AccountService;
use App\Services\Products\PriceGroupService;
use App\Services\Branches\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;
use App\Services\GeneralSettingServiceInterface;

class BranchSettingController extends Controller
{
    public function __construct(
        private BranchService $branchService,
        private BranchSettingService $branchSettingService,
        private InvoiceLayoutService $invoiceLayoutService,
        private AccountService $accountService,
        private UnitService $unitService,
        private CurrencyService $currencyService,
        private TimezoneService $timezoneService,
        private PriceGroupService $priceGroupService,
        private GeneralSettingServiceInterface $generalSettingService
    ) {
    }

    public function index($id)
    {
        abort_if(!$this->generalSettingService->generalSettingsPermission(), 403);

        $generalSettings = config('generalSettings');
        $currencies = $this->currencyService->currencies();
        $units = $this->unitService->units()->where('base_unit_id', null)->get();
        $priceGroups = $this->priceGroupService->priceGroups()->where('status', 'Active')->get();
        $timezones = $this->timezoneService->all();

        $invoiceLayouts = $this->invoiceLayoutService->invoiceLayouts(branchId: auth()->user()->branch_id);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $branch = $this->branchService->singleBranch(id: auth()->user()->branch_id, with: ['parentBranch']);

        if (!auth()->user()->branch_id) {

            return redirect()->route('settings.general.index');
        }

        return view('branches.settings.index', compact('generalSettings', 'currencies', 'units', 'priceGroups', 'timezones', 'branch', 'taxAccounts', 'invoiceLayouts'));
    }

    public function dashboardSettings($id, Request $request)
    {
        $settings = [
            'dashboard__view_stock_expiry_alert_for' => $request->view_stock_expiry_alert_for,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Dashboard settings updated successfully.'));
    }

    public function productSettings($id, Request $request)
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

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Product settings updated Successfully'));
    }

    public function printPageSizeSettings($id, Request $request)
    {
        $settings = [
            'print_page_size__add_sale_page_size' => $request->add_sale_page_size ? $request->add_sale_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__pos_sale_page_size' => $request->pos_sale_page_size ? $request->pos_sale_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__quotation_page_size' => $request->quotation_page_size ? $request->quotation_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__sales_order_page_size' => $request->sales_order_page_size ? $request->sales_order_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__draft_page_size' => $request->draft_page_size ? $request->draft_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__sales_return_page_size' => $request->sales_return_page_size ? $request->sales_return_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__purchase_page_size' => $request->purchase_page_size ? $request->purchase_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__purchase_order_page_size' => $request->purchase_order_page_size ? $request->purchase_order_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__purchase_return_page_size' => $request->purchase_return_page_size ? $request->purchase_return_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__transfer_stock_voucher_page_size' => $request->transfer_stock_voucher_page_size ? $request->transfer_stock_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__stock_adjustment_voucher_page_size' => $request->stock_adjustment_voucher_page_size ? $request->stock_adjustment_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__receipt_voucher_page_size' => $request->receipt_voucher_page_size ? $request->receipt_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__payment_voucher_page_size' => $request->payment_voucher_page_size ? $request->payment_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__expense_voucher_page_size' => $request->payment_voucher_page_size ? $request->payment_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__contra_voucher_page_size' => $request->payment_voucher_page_size ? $request->payment_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__payroll_voucher_page_size' => $request->payroll_voucher_page_size ? $request->payroll_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__payroll_payment_voucher_page_size' => $request->payroll_payment_voucher_page_size ? $request->payroll_payment_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__bom_voucher_page_size' => $request->bom_voucher_page_size ? $request->bom_voucher_page_size : PrintPageSize::AFourPage->value,
            'print_page_size__production_voucher_page_size' => $request->production_voucher_page_size ? $request->production_voucher_page_size : PrintPageSize::AFourPage->value,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Print page size settings updated Successfully'));
    }

    public function purchaseSettings($id, Request $request)
    {
        $settings = [
            'purchase__is_edit_pro_price' => $request->is_edit_pro_price,
            'purchase__is_enable_lot_no' => $request->is_enable_lot_no,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Purchase settings updated successfully.'));
    }

    public function manufacturingSettings($id, Request $request)
    {
        $settings = [
            'manufacturing__production_voucher_prefix' => $request->production_voucher_prefix,
            'manufacturing__is_edit_ingredients_qty_in_production' => $request->is_edit_ingredients_qty_in_production,
            'manufacturing__is_update_product_cost_and_price_in_production' => $request->is_update_product_cost_and_price_in_production,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Manufacturing settings updated successfully.'));
    }

    public function addSaleSettings($id, Request $request)
    {
        $settings = [
            'add_sale__default_sale_discount' => $request->default_sale_discount,
            'add_sale__default_price_group_id' => $request->default_price_group_id,
            'add_sale__default_tax_ac_id' => $request->default_tax_ac_id,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Sale settings updated successfully'));
    }

    public function posSettings($id, Request $request)
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

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('POS settings updated successfully'));
    }

    public function prefixSettings($id, Request $request)
    {
        $settings = [
            'prefix__sales_invoice_prefix' => $request->sales_invoice_prefix,
            'prefix__quotation_prefix' => $request->quotation_prefix,
            'prefix__sales_order_prefix' => $request->sales_order_prefix,
            'prefix__sales_return_prefix' => $request->sales_return_prefix,
            'prefix__payment_voucher_prefix' => $request->payment_voucher_prefix,
            'prefix__receipt_voucher_prefix' => $request->receipt_voucher_prefix,
            'prefix__purchase_invoice_prefix' => $request->purchase_invoice_prefix,
            'prefix__purchase_order_prefix' => $request->purchase_order_prefix,
            'prefix__purchase_return_prefix' => $request->purchase_return_prefix,
            'prefix__payroll_voucher_prefix' => $request->payroll_voucher_prefix,
            'prefix__payroll_payment_voucher_prefix' => $request->payroll_payment_voucher_prefix,
            'prefix__stock_issue_voucher_prefix' => $request->stock_issue_voucher_prefix,
            'prefix__job_card_no_prefix' => $request->job_card_no_prefix,
            'prefix__supplier_id' => $request->supplier_id,
            'prefix__customer_id' => $request->customer_id,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Prefix settings updated Successfully'));
    }

    public function invoiceLayoutSettings($id, Request $request)
    {
        $settings = [
            'invoice_layout__add_sale_invoice_layout_id' => $request->add_sale_invoice_layout_id,
            'invoice_layout__pos_sale_invoice_layout_id' => $request->pos_sale_invoice_layout_id,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Invoice settings updated Successfully'));
    }

    public function rewardPointSettings($id, Request $request)
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

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Reward point settings updated Successfully'));
    }

    public function moduleSettings($id, Request $request)
    {
        $settings = [
            'modules__purchases' => isset($request->purchases) ? 1 : 0,
            'modules__add_sale' => isset($request->add_sale) ? 1 : 0,
            'modules__pos' => isset($request->pos) ? 1 : 0,
            'modules__service' =>  isset($request->service) ? 1 : 0,
            'modules__transfer_stock' => isset($request->transfer_stock) ? 1 : 0,
            'modules__stock_adjustments' => isset($request->stock_adjustments) ? 1 : 0,
            'modules__accounting' => isset($request->accounting) ? 1 : 0,
            'modules__contacts' => isset($request->contacts) ? 1 : 0,
            'modules__hrms' => isset($request->hrms) ? 1 : 0,
            'modules__manage_task' => isset($request->manage_task) ? 1 : 0,
            'modules__manufacturing' => isset($request->manufacturing) ? 1 : 0,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Modules settings updated successfully'));
    }

    public function sendEmailSettings($id, Request $request)
    {
        $settings = [
            'send_email__send_invoice_via_email' => isset($request->send_invoice_via_email) ? 1 : 0,
            'send_email__send_notification_via_email' => isset($request->send_notification_via_email) ? 1 : 0,
            'send_email__customer_due_reminder_via_email' => isset($request->customer_due_reminder_via_email) ? 1 : 0,
            'send_email__user_forget_password_via_email' => isset($request->user_forget_password_via_email) ? 1 : 0,
            'send_email__coupon_offer_via_email' => isset($request->coupon_offer_via_email) ? 1 : 0,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Send Email settings updated successfully'));
    }

    public function sendSmsSettings($id, Request $request)
    {
        $settings = [
            'send_sms__send_invoice_via_sms' => isset($request->send_invoice_via_sms) ? 1 : 0,
            'send_sms__send_notification_via_sms' => isset($request->send_notification_via_sms) ? 1 : 0,
            'send_sms__customer_due_reminder_via_sms' => isset($request->customer_due_reminder_via_sms) ? 1 : 0,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Send SMS settings updated successfully'));
    }

    public function systemSettings($id, Request $request)
    {
        $settings = [
            'system__theme_color' => $request->theme_color,
            'system__datatables_page_entry' => $request->datatable_page_entry,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('System settings updated Successfully.'));
    }

    public function serviceSettings($id, Request $request)
    {
        $settings = [
            'service_settings__default_status_id' => $request->default_status_id,
            'service_settings__default_checklist' => $request->default_checklist,
            'service_settings__product_configuration' => $request->product_configuration,
            'service_settings__default_problems_report' => $request->default_problems_report,
            'service_settings__product_condition' => $request->product_condition,
            'service_settings__terms_and_condition' => $request->terms_and_condition,
            'service_settings__custom_field_1_label' => $request->customer_field_1_label,
            'service_settings__custom_field_2_label' => $request->customer_field_2_label,
            'service_settings__custom_field_3_label' => $request->customer_field_3_label,
            'service_settings__custom_field_4_label' => $request->customer_field_4_label,
            'service_settings__custom_field_5_label' => $request->customer_field_5_label,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Servicing settings updated Successfully.'));
    }

    public function servicePdfAndLabelSettings($id, Request $request)
    {
        $settings = [
            'service_settings_pdf_label__show_customer_info' => $request->show_customer_info,
            'service_settings_pdf_label__customer_label_name' => $request->customer_label_name,
            'service_settings_pdf_label__show_contact_id' => $request->show_contact_id,
            'service_settings_pdf_label__customer_id_label_name' => $request->customer_id_label_name,
            'service_settings_pdf_label__show_customer_tax_no' => $request->show_customer_tax_no,
            'service_settings_pdf_label__customer_tax_no_label_name' => $request->customer_tax_no_label_name,
            'service_settings_pdf_label__show_custom_field_1' => $request->show_custom_field_1,
            'service_settings_pdf_label__show_custom_field_2' => $request->show_custom_field_2,
            'service_settings_pdf_label__show_custom_field_3' => $request->show_custom_field_3,
            'service_settings_pdf_label__show_custom_field_4' => $request->show_custom_field_4,
            'service_settings_pdf_label__show_custom_field_5' => $request->show_custom_field_5,
            'service_settings_pdf_label__label_width' => $request->label_width,
            'service_settings_pdf_label__label_height' => $request->label_height,
            'service_settings_pdf_label__customer_name_in_label' => $request->customer_name_in_label,
            'service_settings_pdf_label__customer_address_in_label' => $request->customer_address_in_label,
            'service_settings_pdf_label__customer_phone_in_label' => $request->customer_phone_in_label,
            'service_settings_pdf_label__customer_alt_phone_in_label' => $request->customer_alt_phone_in_label,
            'service_settings_pdf_label__customer_email_in_label' => $request->customer_email_in_label,
            'service_settings_pdf_label__sales_person_in_label' => $request->sales_person_in_label,
            'service_settings_pdf_label__barcode_in_label' => $request->barcode_in_label,
            'service_settings_pdf_label__status_in_label' => $request->status_in_label,
            'service_settings_pdf_label__due_date_in_label' => $request->due_date_in_label,
            'service_settings_pdf_label__technician_in_label' => $request->technician_in_label,
            'service_settings_pdf_label__problems_in_label' => $request->problems_in_label,
            'service_settings_pdf_label__job_card_no_in_label' => $request->job_card_no_in_label,
            'service_settings_pdf_label__serial_in_label' => $request->serial_in_label,
            'service_settings_pdf_label__model_in_label' => $request->model_in_label,
            'service_settings_pdf_label__password_in_label' => $request->password_in_label,
        ];

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);

        return response()->json(__('Job card print/pdf & label setup updated Successfully.'));
    }
}
