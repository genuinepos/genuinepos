<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;

class UnitController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        
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

        if ($addUnit) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 23, data_obj: $addUnit);
        }
 
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

        if ($updateUnit) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 23, data_obj: $updateUnit);
        }

        return response()->json('Successfully unit is updated');
    }

    public function delete(Request $request, $unitId)
    {
        if (auth()->user()->permission->product['units'] == '0') {

            return response()->json('Access Denied');
        }

        $deleteUnit = Unit::where('id', $unitId)->first();

        if (!is_null($deleteUnit)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 23, data_obj: $deleteUnit);

            $deleteUnit->delete();
        }
        return response()->json('Successfully unit is deleted'); 
    }
}
