<?php

namespace App\Services\Products;

use Illuminate\Support\Facades\DB;
use App\Models\Products\PriceGroup;
use Yajra\DataTables\Facades\DataTables;

class PriceGroupService
{
    public function priceGroupsTable()
    {
        $priceGroups = DB::table('price_groups')->get(['id', 'name', 'description', 'status']);

        return DataTables::of($priceGroups)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="' . route('selling.price.groups.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="' . route('selling.price.groups.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';

                // if ($row->status == "Active") {
                //     $html .= '<a href="'.route('product.selling.price.groups.change.status', [$row->id]).'" class="btn btn-sm btn-danger ms-1" id="change_status">Deactivate</a>';
                // }else {
                //     $html .= '<a href="'.route('product.selling.price.groups.change.status', [$row->id]).'" class="btn btn-sm btn-info text-white ms-1" id="change_status">Active</a>';
                // }

                $html .= '</div>';

                return $html;
            })
            ->addColumn('status', function ($row) {

                $html = '';
                if ($row->status == 'Active') {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input"  id="change_status" data-url="' . route('selling.price.groups.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                    $html .= '</div>';

                    return $html;
                } else {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input" id="change_status" data-url="' . route('selling.price.groups.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    $html .= '</div>';

                    return $html;
                }

                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function addPriceGroup(object $request): ?object
    {
        $addPriceGroup = new PriceGroup();
        $addPriceGroup->name = $request->name;
        $addPriceGroup->description = $request->description;
        $addPriceGroup->save();

        return $addPriceGroup;
    }

    public function updatePriceGroup(int $id, object $request): void
    {
        $updatePriceGroup = $this->singlePriceGroup(id: $id);
        $updatePriceGroup->name = $request->name;
        $updatePriceGroup->description = $request->description;
        $updatePriceGroup->save();
    }

    function changeStatus(int $id) : array
    {
        $statusChange = $this->singlePriceGroup(id: $id);

        if ($statusChange->status == 'Active') {

            $statusChange->status = 'Deactivate';
            $statusChange->save();

            return ['msg' => __("Successfully Price group is deactivated")];
        } else {

            $statusChange->status = 'Active';
            $statusChange->save();

            return ['msg' => __("Successfully Price group is activated")];
        }
    }

    public function priceGroups(array $with = null): ?object
    {
        $query = PriceGroup::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singlePriceGroup(int $id, array $with = null): ?object
    {
        $query = PriceGroup::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
