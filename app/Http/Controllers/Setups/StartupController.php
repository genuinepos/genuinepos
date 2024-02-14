<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use App\Models\Setups\CashCounter;
use App\Services\Users\RoleService;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Setups\StartupService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Setups\CashCounterService;
use App\Services\Setups\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;
use App\Services\GeneralSettingServiceInterface;

class StartupController extends Controller
{
    public function __construct(
        private StartupService $startupService,
        private CurrencyService $currencyService,
        private TimezoneService $timezoneService,
        private RoleService $roleService,
        private GeneralSettingServiceInterface $generalSettingService,
        private BranchService $branchService,
        private CashCounterService $cashCounterService,
        private InvoiceLayoutService $invoiceLayoutService,
        private BranchSettingService $branchSettingService,
    ) {
        $this->middleware('expireDate');
    }

    public function startupFrom()
    {
        $currencies = $this->currencyService->currencies();
        $timezones = $this->timezoneService->all();
        $roles = $this->roleService->roles()->get();

        $generalSettings = config('generalSettings');
        if ($generalSettings['addons__branch_limit'] > 1) {

            return view('setups.startup.startup_form_with_business_and_branch', compact('currencies', 'timezones', 'roles'));
        } else if ($generalSettings['addons__branch_limit'] == 1) {

            return view('setups.startup.startup_from_with_branch', compact('currencies', 'timezones', 'roles'));
        }
    }

    public function startup(Request $request)
    {
        $this->startupService->startupValidation(request: $request);

        $generalSettings = config('generalSettings');

        if ($generalSettings['addons__branch_limit'] > 1) {

            $business_logo = null;
            if ($request->hasFile('business_logo')) {

                $logo = $request->file('business_logo');
                $logoName = uniqid() . '-' . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('uploads/business_logo/'), $logoName);
                $business_logo = $logoName;
            } else {

                $business_logo = $generalSettings['business_or_shop__business_logo'] != null ? $generalSettings['business_or_shop__business_logo'] : null;
            }

            $settings = [
                'business_or_shop__business_name' => $request->business_name,
                'business_or_shop__address' => $request->business_address,
                'business_or_shop__phone' => $request->business_phone,
                'business_or_shop__email' => $request->business_email,
                'business_or_shop__account_start_date' => $request->business_account_start_date,
                'business_or_shop__financial_year_start_month' => $request->business_financial_year_start_month,
                'business_or_shop__default_profit' => $request->default_profit ? $request->default_profit : 0,
                'business_or_shop__currency_id' => $request->business_currency_id,
                'business_or_shop__currency_symbol' => $request->business_currency_symbol,
                'business_or_shop__date_format' => $request->business_date_format,
                'business_or_shop__stock_accounting_method' => $request->business_stock_accounting_method,
                'business_or_shop__time_format' => $request->business_time_format,
                'business_or_shop__business_logo' => $business_logo,
                'business_or_shop__timezone' => $request->business_timezone,
            ];

            $this->generalSettingService->updateAndSync($settings);
        }

        $preparedAddBranchRequest = $this->startupService->prepareAddBranchRequest($request);

        $addBranch = $this->branchService->addBranch(request: $preparedAddBranchRequest);

        $this->branchService->addBranchDefaultAccounts($addBranch->id);

        $this->cashCounterService->addCashCounter(branchId: $addBranch->id, cashCounterName: 'Cash Counter 1', shortName: 'CN1');

        $defaultBranchName = $addBranch?->parentBranch ? strtoupper($addBranch?->parentBranch->name . '(' . $addBranch?->area_name . ')') . ' - Default Invoice Layout' :  strtoupper($addBranch?->name . '(' . $addBranch?->area_name . ')') . ' - Default Invoice Layout';

        $addInvoiceLayout = $this->invoiceLayoutService->addInvoiceLayout(request: $preparedAddBranchRequest, branchId: $addBranch->id, defaultName: $defaultBranchName);

        $this->branchSettingService->addBranchSettings(branchId: $addBranch->id, parentBranchId: $addBranch->parent_branch_id, defaultInvoiceLayoutId: $addInvoiceLayout->id, branchService: $this->branchService, request: $preparedAddBranchRequest);

        if ($request->add_opening_user) {

            $this->branchService->addBranchOpeningUser($preparedAddBranchRequest, $addBranch->id);
        }

        return route();
    }
}
