<?php

namespace App\Services\Setups;

use App\Models\Setups\Warehouse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WarehouseService
{
    public function warehouseListTable(object $request)
    {
        $generalSettings = config('generalSettings');
        $warehouses = '';
        $query = DB::table('warehouses')->leftJoin('branches', 'warehouses.branch_id', 'branches.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('warehouses.branch_id', null)->orWhere('warehouses.is_global', 1);
            } else {

                $query->where('warehouses.branch_id', $request->branch_id)->orWhere('warehouses.is_global', 1);
            }
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $warehouses = $query->where('warehouses.branch_id', auth()->user()->branch_id)->orWhere('warehouses.is_global', 1);
        }

        $warehouses = $query->select(
            'warehouses.id',
            'warehouses.is_global',
            'warehouses.warehouse_name as name',
            'warehouses.phone',
            'warehouses.address',
            'warehouses.warehouse_code as code',
            'branches.name as b_name',
            'branches.branch_code as b_code',
        )->orderBy('warehouses.id', 'desc')->get();

        return DataTables::of($warehouses)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                if ($row->is_global == 1 && auth()->user()->role_type == 3) {

                    return;
                }

                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="' . route('warehouses.edit', [$row->id]) . '" class="action-btn c-edit edit" id="edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="' . route('warehouses.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->is_global == 1) {

                    return __('Global Access');
                } else {

                    if ($row->b_name) {

                        return $row->b_name . '/' . $row->b_code;
                    } else {

                        return $generalSettings['business_or_shop__business_name'];
                    }
                }
            })
            ->rawColumns(['branch', 'action'])
            ->make(true);
    }

    public function addWarehouse(object $request): object
    {
        $addWarehouse = new Warehouse();
        $addWarehouse->branch_id = auth()->user()->branch_id;
        $addWarehouse->warehouse_name = $request->name;
        $addWarehouse->warehouse_code = $request->code;
        $addWarehouse->phone = $request->phone;
        $addWarehouse->address = $request->address;
        $addWarehouse->is_global = isset($request->is_global) ? $request->is_global : 0;
        $addWarehouse->save();

        return $addWarehouse;
    }

    public function updateWarehouse(int $id, object $request): void
    {
        $updateWarehouse = $this->singleWarehouse($id);
        $updateWarehouse->warehouse_name = $request->name;
        $updateWarehouse->warehouse_code = $request->code;
        $updateWarehouse->phone = $request->phone;
        $updateWarehouse->address = $request->address;
        $updateWarehouse->is_global = isset($request->is_global) ? $request->is_global : 0;
        $updateWarehouse->save();
    }

    public function deleteWarehouse(int $id): mixed
    {
        $deleteWarehouse = $this->singleWarehouse($id);

        $stock = DB::table('product_stocks')
            ->where('warehouse_id', $id)
            ->select(DB::raw('SUM(stock) as total_stock'))->groupBy('warehouse_id')->get();

        if ($stock->sum('total_stock') > 0) {

            return ['pass' => false, 'msg' => __('The warehouse can not be deleted. Product stocks belong under this warehouse.')];
        }

        if (!is_null($deleteWarehouse)) {

            $deleteWarehouse->delete();
        }
    }

    public function warehouses(array $with = null)
    {
        $query = Warehouse::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleWarehouse(?int $id, array $with = null)
    {
        $query = Warehouse::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
