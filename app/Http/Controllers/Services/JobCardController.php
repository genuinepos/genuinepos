<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\BrandService;
use App\Services\Services\DeviceService;
use App\Services\Services\StatusService;
use App\Services\Accounts\AccountService;
use App\Services\Services\JobCardService;
use App\Services\Products\PriceGroupService;
use App\Services\Services\DeviceModelService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Services\JobCardProductService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Products\ManagePriceGroupService;
use App\Http\Requests\Services\JobCardStoreRequest;

class JobCardController extends Controller
{
    public function __construct(
        private JobCardService $jobCardService,
        private JobCardProductService $jobCardProductService,
        private BrandService $brandService,
        private DeviceService $deviceService,
        private DeviceModelService $deviceModelService,
        private StatusService $statusService,
        private PriceGroupService $priceGroupService,
        private ManagePriceGroupService $managePriceGroupService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
    ) {
    }

    public function index(Request $request) {

        if ($request->ajax()) {

            $this->jobCardService->jobCardsTable(request: $request);
        }
    }

    public function create(CodeGenerationServiceInterface $codeGenerator)
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $brands = $this->brandService->brands()->get(['id', 'name']);

        $devices = $this->deviceService->devices()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name']);

        $deviceModels = $this->deviceModelService->deviceModels()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name', 'service_checklist']);

        $status = $this->statusService->allStatus()->where('service_status.branch_id', $ownBranchIdOrParentBranchId)->orderByRaw('ISNULL(sort_order), sort_order ASC')->get(['id', 'name', 'color_code']);

        $priceGroupProducts = $this->managePriceGroupService->priceGroupProducts();

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $generalSettings = config('generalSettings');
        $productConfigurationItems = isset($generalSettings['service_settings__product_configuration']) ? explode(',', $generalSettings['service_settings__product_configuration']) : null;

        $defaultProblemsReportItems = isset($generalSettings['service_settings__default_problems_report']) ? explode(',', $generalSettings['service_settings__default_problems_report']) : null;

        $defaultProductConditionItems = isset($generalSettings['service_settings__product_condition']) ? explode(',', $generalSettings['service_settings__product_condition']) : null;

        $defaultChecklist = isset($generalSettings['service_settings__default_checklist']) ? $generalSettings['service_settings__default_checklist'] : null;

        $jobCardNoPrefix = isset($generalSettings['prefix__job_card_no_prefix']) ? $generalSettings['prefix__job_card_no_prefix'] : 'JOB';

        $jobCardNo = $codeGenerator->generateMonthWise(table: 'service_job_cards', column: 'job_no', prefix: $jobCardNoPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        return view('services.job_cards.create', compact('customerAccounts', 'brands', 'devices', 'deviceModels', 'status', 'taxAccounts', 'priceGroupProducts', 'priceGroups', 'productConfigurationItems', 'defaultProblemsReportItems', 'defaultProductConditionItems', 'defaultChecklist', 'jobCardNo'));
    }

    function store(JobCardStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        $generalSettings = config('generalSettings');
        $jobCardNoPrefix = isset($generalSettings['prefix__job_card_no_prefix']) ? $generalSettings['prefix__job_card_no_prefix'] : 'JOB';

        $addJobCard = $this->jobCardService->addJobCard(request: $request, codeGenerator: $codeGenerator, jobCardNoPrefix: $jobCardNoPrefix);

        if (isset($request->product_ids)) {

            $this->jobCardProductService->addJobCardProducts(request: $request, jobCardId: $addJobCard->id);
        }

        $jobCard = $this->jobCardService->singleJobCard(id: $addJobCard->id, with: [
            'branch',
            'branch.parentBranch',
            'brand',
            'device',
            'deviceModel',
            'status',
            'customer',
            'jobCardProducts',
            'jobCardProducts.product',
            'jobCardProducts.variant',
            'jobCardProducts.unit:id,code_name,base_unit_id,base_unit_multiplier',
            'jobCardProducts.unit.baseUnit:id,base_unit_id,code_name',
        ]);

        if ($request->action == 'save_and_print') {

            return view('services.print_templates.print_job', compact('jobCard'));
        } else {

            return response()->json(['successMsg' => __('Job card created successfully')]);
        }
    }

    public function jobCardNo(CodeGenerationServiceInterface $codeGenerator)
    {
        $generalSettings = config('generalSettings');
        $jobCardNoPrefix = isset($generalSettings['prefix__job_card_no_prefix']) ? $generalSettings['prefix__job_card_no_prefix'] : 'JOB';

        return $codeGenerator->generateMonthWise(table: 'service_job_cards', column: 'job_no', prefix: $jobCardNoPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
    }
}
