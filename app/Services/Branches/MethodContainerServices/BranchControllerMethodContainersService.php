<?php

namespace App\Services\Branches\MethodContainerServices;

use App\Enums\BranchType;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Services\Branches\BranchService;
use App\Services\CacheServiceInterface;
use App\Services\GeneralSettingService;
use App\Services\Setups\CurrencyService;
use App\Services\Setups\TimezoneService;
use App\Services\Setups\CashCounterService;
use App\Services\Branches\BranchSettingService;
use App\Services\Setups\InvoiceLayoutService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Interfaces\Branches\BranchControllerMethodContainersInterface;

class BranchControllerMethodContainersService implements BranchControllerMethodContainersInterface
{
    public function __construct(
        private BranchService $branchService,
        private InvoiceLayoutService $invoiceLayoutService,
        private CashCounterService $cashCounterService,
        private CurrencyService $currencyService,
        private TimezoneService $timezoneService,
        private BranchSettingService $branchSettingService,
        private GeneralSettingService $generalSettingService,
        private CodeGenerationServiceInterface $codeGenerator,
        private CacheServiceInterface $cacheService
    ) {
    }

    public function indexMethodContainer(object $request): array|object
    {
        $data = [];
        if ($request->ajax()) {

            return $this->branchService->branchListTable();
        }

        $data['currentCreatedBranchCount'] = $this->branchService->branches()->count();
        return $data;
    }

    public function createMethodContainer(): array
    {
        $data = [];
        $data['currencies'] = $this->currencyService->currencies();
        $data['timezones'] = $this->timezoneService->all();

        $data['branchCode'] = $this->codeGenerator->branchCode();

        $data['roles'] = DB::table('roles')->select('id', 'name')->get();
        $data['branches'] = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return $data;
    }

    public function storeMethodContainer(object $request): ?array
    {
        $restrictions = $this->branchService->restrictions();

        if ($restrictions['pass'] == false) {

            return ['pass' => false, 'msg' => $restrictions['msg']];
        }

        $addBranch = $this->branchService->addBranch($request);

        $this->branchService->addBranchDefaultAccounts($addBranch->id, $addBranch->parent_branch_id);

        $this->cashCounterService->addCashCounter(branchId: $addBranch->id, cashCounterName: 'Cash Counter 1', shortName: 'CN1');

        $defaultBranchName = $addBranch?->parentBranch ? strtoupper($addBranch?->parentBranch->name . '(' . $addBranch?->area_name . ')') . ' - Default Invoice Layout' :  strtoupper($addBranch?->name . '(' . $addBranch?->area_name . ')') . ' - Default Invoice Layout';

        $addInvoiceLayout = $this->invoiceLayoutService->addInvoiceLayout(request: $request, branchId: $addBranch->id, defaultName: $defaultBranchName);

        $this->branchSettingService->addBranchSettings(branchId: $addBranch->id, parentBranchId: $addBranch->parent_branch_id, defaultInvoiceLayoutId: $addInvoiceLayout->id, branchService: $this->branchService, request: $request);

        if ($request->add_initial_user == BooleanType::True->value) {

            $this->branchService->addBranchInitialUser(request: $request, branchId: $addBranch->id);
        }

        return null;
    }

    public function editMethodContainer(int $id): array
    {
        $data = [];
        $data['currencies'] = $this->currencyService->currencies();
        $data['timezones'] = $this->timezoneService->all();

        $data['branches'] = $this->branchService->branches()->where('parent_branch_id', null)->get();
        $data['branch'] = $this->branchService->singleBranch(id: $id, with: ['parentBranch']);

        $data['branchSettings'] = $this->generalSettingService->generalSettings(branchId: $id, keys: [
            'business_or_shop__date_format',
            'business_or_shop__time_forma',
            'business_or_shop__timezone',
            'business_or_shop__currency_id',
            'business_or_shop__currency_symbol',
            'business_or_shop__account_start_date',
            'business_or_shop__financial_year_start_month',
            'business_or_shop__stock_accounting_method',
        ]);

        return $data;
    }

    public function updateMethodContainer(int $id, object $request): void
    {
        $this->branchService->updateBranch(id: $id, request: $request);

        $settings = [
            'business_or_shop__date_format' => $request->date_format,
            'business_or_shop__time_forma' => $request->time_format,
            'business_or_shop__timezone' => $request->timezone,
            'business_or_shop__currency_id' => $request->currency_id,
            'business_or_shop__currency_symbol' => $request->currency_symbol,
        ];

        if ($request->branch_type == BranchType::DifferentShop->value) {

            $settings['business_or_shop__account_start_date'] = $request->account_start_date;
            $settings['business_or_shop__financial_year_start_month'] = $request->financial_year_start_month;
            $settings['business_or_shop__stock_accounting_method'] = $request->stock_accounting_method;
        } else {

            $this->branchSettingService->deleteUnusedBranchSettings(branchId: $id, keys: [
                'business_or_shop__account_start_date',
                'business_or_shop__financial_year_start_month',
                'business_or_shop__stock_accounting_method',
            ]);
        }

        $this->branchSettingService->updateAndSync(settings: $settings, branchId: $id);
    }

    public function deleteMethodContainer(int $id): ?array
    {
        $deleteBranch = $this->branchService->deleteBranch(id: $id);

        if ($deleteBranch['pass'] == false) {

            return ['pass' => false, 'msg' => $deleteBranch['msg']];
        }

        $this->cacheService->forgetGeneralSettingsCache(branchId: $id);

        return null;
    }

    public function deleteLogoMethodContainer(int $id): void
    {
        $this->branchService->deleteLogo(id: $id);
    }

    public function parentWithChildBranchesMethodContainer($id): ?object
    {
        return $this->branchService->branches(with: ['childBranches'])->where('id', $id)->first();
    }

    public function branchCodeMethodContainer(?int $parentBranchId = null): string
    {
        return $this->codeGenerator->branchCode(parentBranchId: $parentBranchId);
    }
}
