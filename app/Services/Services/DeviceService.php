<?php

namespace App\Services\Services;

use App\Models\Services\Device;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DeviceService
{
    public function devicesTable(): object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $devices = DB::table('service_devices')
            ->leftJoin('users', 'service_devices.created_by_id', 'users.id')
            ->where('service_devices.branch_id', $ownBranchIdOrParentBranchId)
            ->select('service_devices.*', 'users.prefix as user_prefix', 'users.name as user_name', 'users.last_name as user_last_name')
            ->orderBy('id', 'desc');

        return DataTables::of($devices)
            // ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                // if (auth()->user()->can('product_brand_edit')) {

                $html .= '<a href="' . route('services.settings.devices.edit', [$row->id]) . '" class="action-btn c-edit" id="editDevice" title="Edit"><span class="fas fa-edit"></span></a>';
                // }

                // if (auth()->user()->can('product_brand_delete')) {

                $html .= '<a href="' . route('services.settings.devices.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteDevice" title="Delete"><span class="fas fa-trash "></span></a>';
                // }
                $html .= '</div>';

                return $html;
            })->editColumn('created_by', function ($row) {

                return $row->user_prefix . ' ' . $row->user_name . ' ' . $row->user_last_name;
            })
            ->rawColumns(['created_by', 'action'])->make(true);
    }

    public function addDevice(object $request): object
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $addDevice = new Device();
        $addDevice->branch_id = $ownBranchIdOrParentBranchId;
        $addDevice->name = $request->name;
        $addDevice->short_description = $request->short_description;
        $addDevice->created_by_id = auth()->user()->id;
        $addDevice->save();

        return $addDevice;
    }

    public function updateDevice(int $id, object $request): void
    {
        $updateDevice = $this->singleDevice(id: $id);
        $updateDevice->name = $request->name;
        $updateDevice->short_description = $request->short_description;
        $updateDevice->save();
    }

    public function deleteDevice(int $id): void
    {
        $deleteDevice = $this->singleDevice(id: $id);

        if (isset($deleteDevice)) {

            $deleteDevice->delete();
        }
    }

    public function singleDevice(int $id, array $with = null): ?object
    {
        $query = Device::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function devices(array $with = null): ?object
    {
        $query = Device::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
