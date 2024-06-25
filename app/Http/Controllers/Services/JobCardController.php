<?php

namespace App\Http\Controllers\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use App\Services\Products\BrandService;
use App\Services\Services\DeviceService;
use App\Services\Services\StatusService;
use App\Services\Accounts\AccountService;
use App\Services\Services\JobCardService;
use App\Services\Products\PriceGroupService;
use App\Services\Services\DeviceModelService;
use App\Services\Accounts\AccountFilterService;
use App\Services\Services\JobCardProductService;
use App\Http\Requests\Services\JobCardEditRequest;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Services\Products\ManagePriceGroupService;
use App\Http\Requests\Services\JobCardIndexRequest;
use App\Http\Requests\Services\JobCardStoreRequest;
use App\Http\Requests\Services\JobCardCreateRequest;
use App\Http\Requests\Services\JobCardDeleteRequest;
use App\Http\Requests\Services\JobCardUpdateRequest;
use App\Http\Requests\Services\JobCardChangeStatusRequest;

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
        private BranchService $branchService,
    ) {
    }

    public function index(JobCardIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->jobCardService->jobCardsTable(request: $request);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $brands = $this->brandService->brands()->get(['id', 'name']);

        $devices = $this->deviceService->devices()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name']);

        $deviceModels = $this->deviceModelService->deviceModels()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name', 'service_checklist']);

        $status = $this->statusService->allStatus()->where('service_status.branch_id', $ownBranchIdOrParentBranchId)->orderByRaw('ISNULL(sort_order), sort_order ASC')->get(['id', 'name', 'color_code']);

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('services.job_cards.index', compact('customerAccounts', 'brands', 'devices', 'deviceModels', 'status', 'branches'));
    }

    public function show($id)
    {
        $jobCard = $this->jobCardService->singleJobCard(id: $id, with: [
            'branch',
            'sale',
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

        return view('services.job_cards.ajax_view.show', compact('jobCard'));
    }

    public function print($id)
    {
        $jobCard = $this->jobCardService->singleJobCard(id: $id, with: [
            'branch',
            'sale',
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

        return view('services.print_templates.print_job', compact('jobCard'));
    }

    public function generatePdf($id)
    {
        $jobCard = $this->jobCardService->singleJobCard(id: $id, with: [
            'branch',
            'sale',
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

        $pdf = Pdf::loadView('services.pdf.job_card_pdf', compact('jobCard'))->setBasePath(public_path())->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream('services.pdf.job_card_pdf');
    }

    public function generateLabel($id)
    {
        $jobCard = $this->jobCardService->singleJobCard(id: $id, with: [
            'branch',
            'branch.parentBranch',
            'brand',
            'device',
            'deviceModel',
            'status',
            'customer',
            'createdBy:id,prefix,name,last_name',
        ]);

        return view('services.print_templates.print_label', compact('jobCard'));
    }

    public function create(JobCardCreateRequest $request, CodeGenerationServiceInterface $codeGenerator)
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
        $productConfigurationItems = isset($generalSettings['service_settings__product_configuration']) ? explode(',', $generalSettings['service_settings__product_configuration']) : [];

        $defaultProblemsReportItems = isset($generalSettings['service_settings__default_problems_report']) ? explode(',', $generalSettings['service_settings__default_problems_report']) : [];

        $defaultProductConditionItems = isset($generalSettings['service_settings__product_condition']) ? explode(',', $generalSettings['service_settings__product_condition']) : [];

        $defaultChecklist = isset($generalSettings['service_settings__default_checklist']) ? $generalSettings['service_settings__default_checklist'] : null;

        $jobCardNoPrefix = isset($generalSettings['prefix__job_card_no_prefix']) ? $generalSettings['prefix__job_card_no_prefix'] : 'JOB';

        $jobCardNo = $codeGenerator->generateMonthWise(table: 'service_job_cards', column: 'job_no', prefix: $jobCardNoPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        return view('services.job_cards.create', compact('customerAccounts', 'brands', 'devices', 'deviceModels', 'status', 'taxAccounts', 'priceGroupProducts', 'priceGroups', 'productConfigurationItems', 'defaultProblemsReportItems', 'defaultProductConditionItems', 'defaultChecklist', 'jobCardNo'));
    }

    public function store(JobCardStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();
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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('services.print_templates.print_job', compact('jobCard'));
        } else {

            return response()->json(['successMsg' => __('Job card created successfully')]);
        }
    }

    public function edit($id, JobCardEditRequest $request)
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $jobCard = $this->jobCardService->singleJobCard(id: $id, with: [
            'jobCardProducts',
            'jobCardProducts.product',
            'jobCardProducts.product.unit',
            'jobCardProducts.variant',
            'jobCardProducts.unit:id,name,code_name,base_unit_id,base_unit_multiplier',
            'jobCardProducts.unit.baseUnit:id,base_unit_id,name,code_name',
        ]);

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $brands = $this->brandService->brands()->get(['id', 'name']);

        $devices = $this->deviceService->devices()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name']);

        $deviceModels = '';

        $deviceModelsQuery = $this->deviceModelService->deviceModels()->where('branch_id', $ownBranchIdOrParentBranchId);

        if ($jobCard->device_id) {

            $deviceModelsQuery->where('device_id', $jobCard->device_id);
        } else if ($jobCard->brand_id) {

            $deviceModelsQuery->where('brand_id', $jobCard->brand_id);
        }

        $deviceModels = $deviceModelsQuery->get(['id', 'name', 'service_checklist']);

        $status = $this->statusService->allStatus()->where('service_status.branch_id', $ownBranchIdOrParentBranchId)->orderByRaw('ISNULL(sort_order), sort_order ASC')->get(['id', 'name', 'color_code']);

        $priceGroupProducts = $this->managePriceGroupService->priceGroupProducts();

        $priceGroups = $this->priceGroupService->priceGroups()->get(['id', 'name']);

        $taxAccounts = $this->accountService->accounts()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 8)
            ->get(['accounts.id', 'accounts.name', 'tax_percent']);

        $generalSettings = config('generalSettings');
        $productConfigurationItems = isset($generalSettings['service_settings__product_configuration']) ? explode(',', $generalSettings['service_settings__product_configuration']) : [];

        $defaultProblemsReportItems = isset($generalSettings['service_settings__default_problems_report']) ? explode(',', $generalSettings['service_settings__default_problems_report']) : [];

        $defaultProductConditionItems = isset($generalSettings['service_settings__product_condition']) ? explode(',', $generalSettings['service_settings__product_condition']) : [];

        $defaultChecklist = isset($generalSettings['service_settings__default_checklist']) ? $generalSettings['service_settings__default_checklist'] : null;

        return view('services.job_cards.edit', compact('jobCard', 'customerAccounts', 'brands', 'devices', 'deviceModels', 'status', 'taxAccounts', 'priceGroupProducts', 'priceGroups', 'productConfigurationItems', 'defaultProblemsReportItems', 'defaultProductConditionItems', 'defaultChecklist'));
    }

    public function update($id, JobCardUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateJobCard = $this->jobCardService->updateJobCard(id: $id, request: $request);

            if (isset($request->product_ids)) {

                $this->jobCardProductService->updateJobCardProducts(request: $request, jobCardId: $updateJobCard->id);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Job card updated successfully'));
    }

    public function changeStatusModal($id)
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $status = $this->statusService->allStatus()->where('service_status.branch_id', $ownBranchIdOrParentBranchId)->orderByRaw('ISNULL(sort_order), sort_order ASC')->get(['id', 'name', 'color_code']);

        $jobCard = $this->jobCardService->singleJobCard(id: $id);
        return view('services.job_cards.ajax_view.change_status', compact('jobCard', 'status'));
    }

    public function changeStatus($id, JobCardChangeStatusRequest $request)
    {
        $this->jobCardService->updateJobCardStatus(id: $id, request: $request);

        return response()->json(__('Job card status updated successfully'));
    }

    public function delete($id, JobCardDeleteRequest $request)
    {
        $deleteJobCard = $this->jobCardService->deleteJobCard(id: $id);
        if ($deleteJobCard['pass'] == false) {

            return response()->json(['errorMsg' => $deleteJobCard['msg']]);
        }

        return response()->json(__('Job card updated successfully'));
    }

    public function jobCardNo(CodeGenerationServiceInterface $codeGenerator)
    {
        $generalSettings = config('generalSettings');
        $jobCardNoPrefix = isset($generalSettings['prefix__job_card_no_prefix']) ? $generalSettings['prefix__job_card_no_prefix'] : 'JOB';

        return $codeGenerator->generateMonthWise(table: 'service_job_cards', column: 'job_no', prefix: $jobCardNoPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
    }
}
