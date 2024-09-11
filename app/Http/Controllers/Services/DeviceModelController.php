<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Products\BrandService;
use App\Services\Services\DeviceService;
use App\Services\Services\DeviceModelService;
use App\Http\Requests\Services\DeviceModelEditRequest;
use App\Http\Requests\Services\DeviceModelStoreRequest;
use App\Http\Requests\Services\DeviceModelTableRequest;
use App\Http\Requests\Services\DeviceModelCreateRequest;
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

    public function deviceModelsTable(DeviceModelTableRequest $request)
    {
        if ($request->ajax()) {

            return $this->deviceModelService->deviceModelsTable();
        }
    }

    public function create(DeviceModelCreateRequest $request)
    {
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $devices = $this->deviceService->devices()->get(['id', 'name']);
        return view('services.settings.ajax_views.device_models.create', compact('brands', 'devices'));
    }

    public function store(DeviceModelStoreRequest $request)
    {
        return $this->deviceModelService->addDeviceModel(request: $request);
    }

    public function edit($id, DeviceModelEditRequest $request)
    {
        $deviceModel = $this->deviceModelService->singleDeviceModel(id: $id);
        $brands = $this->brandService->brands()->get(['id', 'name']);
        $devices = $this->deviceService->devices()->get(['id', 'name']);
        return view('services.settings.ajax_views.device_models.edit', compact('deviceModel', 'brands', 'devices'));
    }

    public function update($id, DeviceModelUpdateRequest $request)
    {
        $generalSettings = config('generalSettings');

        $this->deviceModelService->updateDeviceModel(id: $id, request: $request);

        $updateMsg = isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] . ' ' . __('updated successfully') : __('Device model updated successfully');

        return response()->json($updateMsg);
    }

    public function delete($id, DeviceModelDeleteRequest $request)
    {
        $generalSettings = config('generalSettings');

        $deleteDeviceModel = $this->deviceModelService->deleteDeviceModel(id: $id);

        if ($deleteDeviceModel['pass'] == false) {

            return response()->json(['errorMsg' => $deleteDeviceModel['msg']]);
        }

        $deleteMsg = isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] . ' ' . __('deleted successfully') : __('Device model delete successfully');

        return response()->json($deleteMsg);
    }

    public function deviceModelsByBrand(Request $request)
    {
        return $this->deviceModelService->deviceModelsByBrand(request: $request);
    }

    public function deviceModelsByDevice(Request $request)
    {
        return $this->deviceModelService->deviceModelsByDevice(request: $request);
    }
}
