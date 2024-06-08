<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\BrandService;
use App\Services\Services\DeviceService;
use App\Services\Services\StatusService;
use App\Services\Accounts\AccountService;
use App\Services\Services\DeviceModelService;
use App\Services\Accounts\AccountFilterService;

class JobCardController extends Controller
{
    public function __construct(
        private BrandService $brandService,
        private DeviceService $deviceService,
        private DeviceModelService $deviceModelService,
        private StatusService $statusService,
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
    ) {
    }

    public function create()
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        $brands = $this->brandService->brands()->get(['id', 'name']);

        $devices = $this->deviceService->devices()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name']);

        $deviceModels = $this->deviceModelService->deviceModels()->where('branch_id', $ownBranchIdOrParentBranchId)->get(['id', 'name', 'service_checklist']);

        $status = $this->statusService->allStatus()->where('service_status.branch_id', $ownBranchIdOrParentBranchId)->orderByRaw('ISNULL(sort_order), sort_order ASC')->get(['id', 'name', 'color_code']);

        $generalSettings = config('generalSettings');
        $productConfigurationItems = isset($generalSettings['service_settings__product_configuration']) ? explode(',', $generalSettings['service_settings__product_configuration']) : null;

        $defaultProblemsReportItems = isset($generalSettings['service_settings__default_problems_report']) ? explode(',', $generalSettings['service_settings__default_problems_report']) : null;

        $defaultProductConditionItems = isset($generalSettings['service_settings__product_condition']) ? explode(',', $generalSettings['service_settings__product_condition']) : null;

        $defaultChecklist = isset($generalSettings['service_settings__default_checklist']) ? $generalSettings['service_settings__default_checklist'] : null;

        return view('services.job_cards.create', compact('customerAccounts', 'brands', 'devices', 'deviceModels', 'status', 'productConfigurationItems', 'defaultProblemsReportItems', 'defaultProductConditionItems', 'defaultChecklist'));
    }
}
