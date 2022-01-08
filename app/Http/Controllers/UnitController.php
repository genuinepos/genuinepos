<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        if (auth()->user()->permission->product['units'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        return view('settings.units.index');
    }

    public function getAllUnit()
    {
        $units = Unit::all();
        return view('settings.units.ajax_view.unit_list', compact('units'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->permission->product['units'] == '0') {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required|unique:units,name',
            'code' => 'required|unique:units,code_name',
        ]);

        $addUnit = new Unit();
        $addUnit->name = $request->name;
        $addUnit->code_name = $request->code;
        $addUnit->save();
 
        return response()->json('Successfully branch is added');
    }
    
    public function update(Request $request)
    {
        if (auth()->user()->permission->product['units'] == '0') {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required|unique:units,name,'.$request->id,
            'code' => 'required|unique:units,code_name,'.$request->id,
        ]);

        $updateUnit = Unit::where('id', $request->id)->first();
        $updateUnit->name = $request->name;
        $updateUnit->code_name = $request->code;
        $updateUnit->save();
        return response()->json('Successfully unit is updated');
    }

    public function delete(Request $request, $unitId)
    {
        if (auth()->user()->permission->product['units'] == '0') {
            return response()->json('Access Denied');
        }
        
        $deleteUnit = Unit::where('id', $unitId)->first();
        if (!is_null($deleteUnit)) {
            $deleteUnit->delete();
        }
        return response()->json('Successfully unit is deleted'); 
    }
}
