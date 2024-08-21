<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Services\Services\StatusService;
use App\Http\Requests\Services\SettingIndexRequest;

class SettingController extends Controller
{
    public function __construct(private StatusService $statusService)
    {
    }

    public function index(SettingIndexRequest $request) {

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $status = $this->statusService->allStatus()->where('service_status.branch_id', $ownBranchIdOrParentBranchId)->orderByRaw('ISNULL(sort_order), sort_order ASC')->get(['id', 'name', 'color_code']);
        return view('services.settings.index', compact('ownBranchIdOrParentBranchId', 'status'));
    }
}
