<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\GeneralSettingServiceInterface;
use App\Services\Manufacturing\ManufacturingSettingService;

class ManufacturingSettingController extends Controller
{
    public function __construct(
        private ManufacturingSettingService $manufacturingSettingService,
    ) {
    }

    public function index()
    {
        if (!auth()->user()->can('manuf_settings')) {

            abort(403, 'Access Forbidden.');
        }

        $manufacturingSetting = $this->manufacturingSettingService->manufacturingSetting()
            ->where('branch_id', auth()->user()->branch_id)->first();

        return view('manufacturing.settings.index', compact('manufacturingSetting'));
    }

    public function storeOrUpdate(Request $request)
    {
        if (!auth()->user()->can('manuf_settings')) {

            return response()->json('Access Forbidden');
        }

        $this->manufacturingSettingService->manufacturingSettingAddOrUpdate(request: $request);

        return response()->json(__("Manufacturing settings updated successfully"));
    }
}
