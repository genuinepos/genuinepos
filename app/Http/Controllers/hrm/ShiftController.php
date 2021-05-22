<?php

namespace App\Http\Controllers\hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Shift;
use Illuminate\Support\Facades\Cache;

class ShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //shift page shown
    public function index()
    {
        return view('hrm.shift.index');
    }

    //all shift ajax call
    public function allShift()
    {
        $shift = Shift::orderBy('id', 'DESC')->get();
        return view('hrm.shift.ajax.list', compact('shift'));
    }

    //shift store method
    public function storeShift(Request $request)
    {
        $this->validate($request, [
            'shift_name' => 'required',
            'shift_type' => 'required',
        ]);

        Shift::insert([
            'shift_name' => $request->shift_name,
            'shift_type' => $request->shift_type,
            'start_time' => $request->start_time,
            'endtime' => $request->endtime,
            'holiday' => implode(", ", $request->holiday),
        ]);
        return response()->json('Successfully Shift Added!');
    }

    //update shift
    public function updateShift(Request $request)
    {
        $this->validate($request, [
            'shift_name' => 'required',
            'shift_type' => 'required'
        ]);
        $updateShift = Shift::where('id', $request->id)->first();
        $updateShift->update([
            'shift_name' => $request->shift_name,
            'shift_type' => $request->shift_type,
            'start_time' => $request->start_time,
            'endtime' => $request->endtime,
            'holiday' => implode(", ", $request->holiday)
        ]);
        return response()->json('Successfully Shift Updated!');
    }
}
