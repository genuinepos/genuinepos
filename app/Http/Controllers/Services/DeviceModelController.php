<?php

namespace App\Http\Controllers\Services;

use App\Enums\BooleanType;
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
        abort_if(!auth()->user()->can('device_models_index') || (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::False->value), 403);

        if ($request->ajax()) {

            return $this->deviceModelService->deviceModelsTable();
        }
    }

    public function create()
    {
        abort_if(!auth()->user()->can('device_models_create') || (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::False->value), 403);

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
        abort_if(!auth()->user()->can('device_models_edit') || (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::False->value), 403);

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

    public function deviceModelsByBrand(Request $request)
    {
        return $this->deviceModelService->deviceModelsByBrand(request: $request);
    }

    public function deviceModelsByDevice(Request $request)
    {
        return $this->deviceModelService->deviceModelsByDevice(request: $request);
    }
}
