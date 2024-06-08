<?php

namespace App\Services\Services;

use App\Models\Services\DeviceModel;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DeviceModelService
{
    public function deviceModelsTable(): object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $deviceModels = DB::table('service_device_models')
            ->leftJoin('users', 'service_device_models.created_by_id', 'users.id')
            ->leftJoin('brands', 'service_device_models.brand_id', 'brands.id')
            ->leftJoin('service_devices', 'service_device_models.device_id', 'service_devices.id')
            ->where('service_device_models.branch_id', $ownBranchIdOrParentBranchId)
            ->select(
                'service_device_models.*',
                'brands.name as brand_name',
                'service_devices.name as device_name',
                'users.prefix as user_prefix',
                'users.name as user_name',
                'users.last_name as user_last_name'
            )->orderBy('id', 'desc');

        return DataTables::of($deviceModels)
            // ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                // if (auth()->user()->can('product_brand_edit')) {

                $html .= '<a href="' . route('services.settings.device.models.edit', [$row->id]) . '" class="action-btn c-edit" id="editDeviceModel" title="Edit"><span class="fas fa-edit"></span></a>';
                // }

                // if (auth()->user()->can('product_brand_delete')) {

                $html .= '<a href="' . route('services.settings.device.models.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteDeviceModel" title="Delete"><span class="fas fa-trash "></span></a>';
                // }
                $html .= '</div>';

                return $html;
            })->editColumn('created_by', function ($row) {

                return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name;
            })
            ->rawColumns(['created_by', 'action'])->make(true);
    }

    public function addDeviceModel(object $request): object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $addDeviceModel = new DeviceModel();
        $addDeviceModel->branch_id = $ownBranchIdOrParentBranchId;
        $addDeviceModel->name = $request->name;
        $addDeviceModel->brand_id = $request->brand_id;
        $addDeviceModel->device_id = $request->device_id;
        $addDeviceModel->service_checklist = $request->service_checklist;
        $addDeviceModel->created_by_id = auth()->user()->id;
        $addDeviceModel->save();

        return $addDeviceModel;
    }

    public function updateDeviceModel(int $id, object $request): void
    {
        $updateDeviceModel = $this->singleDeviceModel(id: $id);
        $updateDeviceModel->name = $request->name;
        $updateDeviceModel->brand_id = $request->brand_id;
        $updateDeviceModel->device_id = $request->device_id;
        $updateDeviceModel->service_checklist = $request->service_checklist;
        $updateDeviceModel->save();
    }

    public function deleteDeviceModel(int $id): void
    {
        $deleteDeviceModel = $this->singleDeviceModel(id: $id);

        if (isset($deleteDeviceModel)) {

            $deleteDeviceModel->delete();
        }
    }

    public function deviceModels(array $with = null): ?object
    {
        $query = DeviceModel::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleDeviceModel(int $id, array $with = null): ?object
    {
        $query = DeviceModel::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function deviceModelsByBrand(object $request, array $with = null): ?object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $query = DeviceModel::query();

        if (isset($with)) {

            $query->with($with);
        }

        if ($request->brand_id) {

            $query->where('brand_id', $request->brand_id);
        }

        return $query->where('branch_id', $ownBranchIdOrParentBranchId)->get();
    }

    public function deviceModelsByDevice(object $request, array $with = null): ?object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $query = DeviceModel::query();

        if (isset($with)) {

            $query->with($with);
        }

        if ($request->device_id) {

            dd($request->device_id);

            $query->where('device_id', $request->device_id);
        }

        return $query->where('branch_id', $ownBranchIdOrParentBranchId)->get();
    }
}
