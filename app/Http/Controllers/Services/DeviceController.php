<?php

namespace App\Http\Controllers\Services;

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
        if ($request->ajax()) {

            return $this->deviceService->devicesTable();
        }
    }

    public function create()
    {
        return view('services.settings.ajax_views.devices.create');
    }

    public function store(DeviceStoreRequest $request)
    {
        return $this->deviceService->addDevice(request: $request);
    }

    public function edit($id)
    {
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
