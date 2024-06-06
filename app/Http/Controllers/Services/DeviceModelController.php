<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\BrandService;
use App\Services\Services\DeviceService;
use App\Services\Services\DeviceModelService;
use App\Http\Requests\Services\DeviceModelStoreRequest;
use App\Http\Requests\Services\DeviceModelDeleteRequest;
use App\Http\Requests\Services\DeviceModelUpdateRequest;

class DeviceModelController extends Controller
{
    public function __construct(
        private DeviceModelService $deviceModelService,
        private BrandService $brandService,
        private DeviceService $deviceService
    ) {
    }

    public function deviceModelsTable(Request $request)
    {
        if ($request->ajax()) {

            return $this->deviceModelService->deviceModelsTable();
        }
    }

    public function create()
    {
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $devices = $this->deviceService->devices()->get(['id', 'name']);
        return view('services.settings.ajax_views.device_models.create', compact('brands', 'devices'));
    }

    public function store(DeviceModelStoreRequest $request)
    {
        return $this->deviceModelService->addDeviceModel(request: $request);
    }

    public function edit($id)
    {
        $deviceModel = $this->deviceModelService->singleDeviceModel(id: $id);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $devices = $this->deviceService->devices()->get(['id', 'name']);
        return view('services.settings.ajax_views.device_models.edit', compact('deviceModel', 'brands', 'devices'));
    }

    public function update($id, DeviceModelUpdateRequest $request)
    {
        $this->deviceModelService->updateDeviceModel(id: $id, request: $request);

        return response()->json(__('Device model updated successfully'));
    }

    public function delete($id, DeviceModelDeleteRequest $request)
    {
        $this->deviceModelService->deleteDeviceModel(id: $id);

        return response()->json(__('Device model deleted successfully'));
    }
}
