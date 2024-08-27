<?php

namespace App\Services\Startup\MethodContainerServices;

use App\Enums\BooleanType;
use App\Utils\FileUploader;
use App\Services\Users\RoleService;
use App\Services\Branches\BranchService;
use Illuminate\Support\Facades\Session;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Startup\StartupService;
use App\Services\Setups\CashCounterService;
use App\Services\Branches\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;
use App\Services\GeneralSettingServiceInterface;
use App\Services\Subscriptions\SubscriptionService;
use App\Interfaces\Startup\StartupControllerMethodContainerInterface;

class StartupControllerMethodContainerService implements StartupControllerMethodContainerInterface
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
        private SubscriptionService $subscriptionService,
    ) {}

    public function startupFromContainer(): array
    {
        $data = [];
        if (!Session::get('startupType')) {

            return ['pass' => false];
            // return redirect()->back();
        }

        $data['currencies'] = $this->currencyService->currencies();
        $data['timezones'] = $this->timezoneService->all();
        $data['roles'] = $this->roleService->roles()->get();

        $direction = '';
        if (Session::get('startupType') == 'business_and_branch') {

            $direction = 'business_and_branch';
        } else if (Session::get('startupType') == 'branch') {

            $direction = 'branch';
        } else if (Session::get('startupType') == 'business') {

            $direction = 'business';
        }

        $data['direction'] = $direction;

        return $data;
    }

    public function finishMethodContainer(object $request): void
    {
        $generalSettings = config('generalSettings');

        if (Session::get('startupType') == 'business_and_branch' || Session::get('startupType') == 'business') {

            $business_logo = null;
            if ($request->hasFile('business_logo')) {

                $uploadedFile = FileUploader::uploadWithResize(
                    fileType: 'businessLogo',
                    uploadableFile: $request->file('business_logo'),
                    height: 40,
                    width: 100,
                );

                $business_logo = $uploadedFile;
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
            $this->subscriptionService->updateBusinessStartUpCompletingStatus();
        }

        if (Session::get('startupType') == 'business_and_branch' || Session::get('startupType') == 'branch') {

            $preparedAddBranchRequest = $this->startupService->prepareAddBranchRequest($request);

            $addBranch = $this->branchService->addBranch(request: $preparedAddBranchRequest);

            $this->branchService->addBranchDefaultAccounts($addBranch->id);

            $this->cashCounterService->addCashCounter(branchId: $addBranch->id, cashCounterName: 'Cash Counter 1', shortName: 'CN1');

            $defaultBranchName = $addBranch?->parentBranch ? strtoupper($addBranch?->parentBranch->name . '(' . $addBranch?->area_name . ')') . ' - Default Invoice Layout' :  strtoupper($addBranch?->name . '(' . $addBranch?->area_name . ')') . ' - Default Invoice Layout';

            $addInvoiceLayout = $this->invoiceLayoutService->addInvoiceLayout(request: $preparedAddBranchRequest, branchId: $addBranch->id, defaultName: $defaultBranchName);

            $this->branchSettingService->addBranchSettings(branchId: $addBranch->id, parentBranchId: $addBranch->parent_branch_id, defaultInvoiceLayoutId: $addInvoiceLayout->id, branchService: $this->branchService, request: $preparedAddBranchRequest);

            if ($request->add_initial_user) {

                $this->branchService->addBranchInitialUser($preparedAddBranchRequest, $addBranch->id);
            }

            $this->subscriptionService->updateBranchStartUpCompletingStatus();

            if ($generalSettings['subscription']->current_shop_count == 1 && $generalSettings['subscription']->has_business == BooleanType::False->value) {

                auth()->user()->branch_id = $addBranch->id;
                auth()->user()->is_belonging_an_area = BooleanType::True->value;
                auth()->user()->save();
            }
        }

        Session::forget('chooseBusinessOrShop');
        Session::forget('startupType');
    }
}
