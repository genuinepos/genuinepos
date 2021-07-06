<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        return view('settings.warehouses.index');
    }

    public function getAllBranch()
    {
        $warehouses = Warehouse::all();
        return view('settings.warehouses.ajax_view.warehouse_list', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);
        $addWarehouse = new Warehouse();
        $addWarehouse->warehouse_name = $request->name;
        $addWarehouse->warehouse_code = $request->code;
        $addWarehouse->phone = $request->phone;
        $addWarehouse->address = $request->address;
        $addWarehouse->save();
        return response()->json('Successfully warehouse is added');
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'phone' => 'required',
        ]);

        $updateWarehouse = Warehouse::where('id', $request->id)->first();
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