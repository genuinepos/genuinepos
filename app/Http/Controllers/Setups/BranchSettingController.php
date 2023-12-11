<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Services\Setups\BranchService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Accounts\AccountService;
use App\Services\Products\PriceGroupService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;

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
    ) {
    }

    public function index($id = null)
    {
        $generalSettings = config('generalSettings');
        $currencies = $this->currencyService->currencies();
        $units = $this->unitService->units()->where('base_unit_id', null)->get();
        $priceGroups = $this->priceGroupService->priceGroups()->where('status', 'Active')->get();
        $timezones = $this->timezoneService->all();

        $invoiceLayouts = $this->invoiceLayoutService->invoiceLayouts(branchId: $id);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $branch = $this->branchService->singleBranch(id: $id, with: ['parentBranch']);

        return view('setups.branches.settings.index', compact('generalSettings', 'currencies', 'units', 'priceGroups', 'timezones', 'branch', 'taxAccounts', 'invoiceLayouts'));
    }

    public function dashboardSettings(Request $request)
    {
        $settings = [
            'dashboard__view_stock_expiry_alert_for' => $request->view_stock_expiry_alert_for,
        ];

        $this->branchSettingService->updateAndSync($settings);

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

        $this->branchSettingService->updateAndSync($settings);

        return response()->json(__('Product settings updated Successfully'));
    }

    public function purchaseSettings(Request $request)
    {
        $settings = [
            'purchase__is_edit_pro_price' => $request->is_edit_pro_price,
            'purchase__is_enable_lot_no' => $request->is_enable_lot_no,
        ];

        $this->branchSettingService->updateAndSync($settings);

        return response()->json(__('Purchase settings updated successfully.'));
    }

    public function addSaleSettings(Request $request)
    {
        $settings = [
            'add_sale__default_sale_discount' => $request->default_sale_discount,
            'add_sale__default_price_group_id' => $request->default_price_group_id,
            'add_sale__default_tax_ac_id' => $request->default_tax_ac_id,
        ];

        $this->branchSettingService->updateAndSync($settings);

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

        $this->branchSettingService->updateAndSync($settings);

        return response()->json('POS settings updated successfully');
    }

    public function prefixSettings(Request $request)
    {
        $settings = [
            'prefix__invoice_prefix' => $request->invoice_prefix,
            'prefix__quotation_prefix' => $request->quotation_prefix,
            'prefix__sales_order_prefix' => $request->sales_order_prefix,
            'prefix__sales_return_prefix' => $request->sales_return_prefix,
            'prefix__payment_voucher_prefix' => $request->payment_voucher_prefix,
            'prefix__receipt_voucher_prefix' => $request->receipt_voucher_prefix,
            'prefix__purchase_invoice_prefix' => $request->purchase_invoice_prefix,
            'prefix__purchase_order_prefix' => $request->purchase_order_prefix,
            'prefix__purchase_return_prefix' => $request->purchase_return_prefix,
            'prefix__stock_adjustment_prefix' => $request->stock_adjustment_prefix,
        ];

        $this->branchSettingService->updateAndSync($settings);

        return response()->json(__('Prefix settings updated Successfully'));
    }

    public function edit($branchId)
    {
        if (!auth()->user()->can('branch')) {

            abort(403, 'Access Forbidden.');
        }

        $branchSetting = $this->branchSettingService->singleBranchSetting(branchId: $branchId, with: ['branch', 'branch.parentBranch']);
        $invoiceLayouts = $this->invoiceLayoutService->invoiceLayouts(branchId: $branchId);

        $taxAccounts = DB::table('accounts')->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('accounts.branch_id', $branchId)
            ->where('account_groups.is_default_tax_calculator', 1)
            ->select('accounts.id', 'accounts.name')
            ->get();

        return view('setups.branches.settings.edit', compact('branchSetting', 'invoiceLayouts', 'taxAccounts'));
    }

    public function update(Request $request, $branchId)
    {
        if (!auth()->user()->can('branch')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $this->branchSettingService->updateBranchSettings(branchId: $branchId, request: $request);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Shop settings updated successfully'));
    }
}
