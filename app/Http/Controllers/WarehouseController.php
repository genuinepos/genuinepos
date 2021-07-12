<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $warehouses = '';
            $query = DB::table('warehouses')
            ->leftJoin('branches', 'warehouses.branch_id', 'branches.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('warehouses.branch_id', NULL);
                } else {
                    $query->where('warehouses.branch_id', $request->branch_id);
                }
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $warehouses = $query->select(
                    'warehouses.id',
                    'warehouses.branch_id',
                    'warehouses.warehouse_name as name',
                    'warehouses.phone',
                    'warehouses.address',
                    'warehouses.warehouse_code as code',
                    'branches.name as b_name',
                    'branches.branch_code as b_code',
                )->orderBy('warehouses.id', 'desc')->get();
            } else {
                $warehouses = $query->select(
                    'warehouses.id',
                    'warehouses.branch_id',
                    'warehouses.warehouse_name as name',
                    'warehouses.phone',
                    'warehouses.address',
                    'warehouses.warehouse_code as code',
                    'branches.name as b_name',
                    'branches.branch_code as b_code',
                )->where('branch_id', auth()->user()->branch_id)->orderBy('warehouses.id', 'desc')->get();
            }

            return DataTables::of($warehouses)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if (auth()->user()->branch_id == $row->branch_id) {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="' . route('settings.warehouses.edit', [$row->id]) . '" class="action-btn c-edit edit" id="edit"><span class="fas fa-edit"></span></a>';
                        $html .= '<a href="' . route('settings.warehouses.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                        $html .= '</div>';
                        return $html;
                    }
                })
                ->editColumn('branch',  function ($row) use ($generalSettings) {
                    if ($row->b_name) {
                        return $row->b_name . '/' . $row->b_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HF</b>)';
                    }
                })
                ->rawColumns(['branch', 'action'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('settings.warehouses.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        $addWarehouse = new Warehouse();
        $addWarehouse->branch_id = auth()->user()->branch_id;
        $addWarehouse->warehouse_name = $request->name;
        $addWarehouse->warehouse_code = $request->code;
        $addWarehouse->phone = $request->phone;
        $addWarehouse->address = $request->address;
        $addWarehouse->save();
        return response()->json('Successfully warehouse is added');
    }

    public function edit($id)
    {
        $w = DB::table('warehouses')->where('id', $id)->first();
        return view('settings.warehouses.ajax_view.edit', compact('w'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        $updateWarehouse = Warehouse::where('id', $id)->first();
        $updateWarehouse->warehouse_name = $request->name;
        $updateWarehouse->warehouse_code = $request->code;
        $updateWarehouse->phone = $request->phone;
        $updateWarehouse->address = $request->address;
        $updateWarehouse->save();
        return response()->json('Successfully warehouse is updated');
    }

    public function delete(Request $request, $warehouseId)
    {
        $deleteWarehouse = Warehouse::where('id', $warehouseId)->first();
        if (!is_null($deleteWarehouse)) {
            $deleteWarehouse->delete();
        }
        return response()->json('Successfully warehouse is deleted');
    }
}
