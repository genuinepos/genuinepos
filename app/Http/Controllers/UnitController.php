<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index()
    {
        return view('settings.units.index');
    }

    public function getAllUnit()
    {
        $units = Unit::all();
        return view('settings.units.ajax_view.unit_list', compact('units'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
        ]);

        $addUnit = new Unit();
        $addUnit->name = $request->name;
        $addUnit->code_name = $request->code;
        $addUnit->save();
        Cache::forget('all-products');
        Cache::forget('all-unites');
        return response()->json('Successfully branch is added');
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
        ]);

        $updateUnit = Unit::where('id', $request->id)->first();
        $updateUnit->name = $request->name;
        $updateUnit->code_name = $request->code;
        $updateUnit->save();
        Cache::forget('all-products');
        Cache::forget('all-unites');
        return response()->json('Successfully unit is updated');
    }

    public function delete(Request $request, $unitId)
    {
        $deleteUnit = Unit::where('id', $unitId)->first();
        if (!is_null($deleteUnit)) {
            $deleteUnit->delete();
        }
        Cache::forget('all-products');
        Cache::forget('all-unites');
        return response()->json('Successfully unit is deleted'); 
    }
}
