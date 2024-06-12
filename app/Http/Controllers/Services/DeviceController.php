<?php

namespace App\Http\Controllers\Services;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Services\DeviceService;
use App\Http\Requests\Services\DeviceStoreRequest;
use App\Http\Requests\Services\DeviceDeleteRequest;
use App\Http\Requests\Services\DeviceUpdateRequest;

class DeviceController extends Controller
{
    public function __construct(private DeviceService $deviceService)
    {
    }

    public function devicesTable(Request $request)
    {
        abort_if(!auth()->user()->can('devices_index') || (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::False->value), 403);

        if ($request->ajax()) {

            return $this->deviceService->devicesTable();
        }
    }

    public function create()
    {
        abort_if(!auth()->user()->can('devices_create') || (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::False->value), 403);

        return view('services.settings.ajax_views.devices.create');
    }

    public function store(DeviceStoreRequest $request)
    {
        return $this->deviceService->addDevice(request: $request);
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('devices_edit') || (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::False->value), 403);

        $device = $this->deviceService->singleDevice(id: $id);
        return view('services.settings.ajax_views.devices.edit', compact('device'));
    }

    public function update($id, DeviceUpdateRequest $request)
    {
        $this->deviceService->updateDevice(id: $id, request: $request);

        return response()->json(__('Device updated successfully'));
    }

    public function delete($id, DeviceDeleteRequest $request)
    {
        $this->deviceService->deleteDevice(id: $id);

        return response()->json(__('Device deleted successfully'));
    }
}
