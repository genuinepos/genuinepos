<?php

namespace App\Http\Controllers\hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hrm\Holiday;
use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

class HolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //holiday page show methods
    public function index()
    {
        $branches = Branch::orderBy('name', 'ASC')->get();
        return view('hrm.holiday.index', compact('branches'));
    }

    //all holidays data get for holiday pages
    public function allHolidays()
    {
        $holidays = Holiday::orderBy('id', 'DESC')->get();
        return view('hrm.holiday.ajax.list', compact('holidays'));
    }

    //store holidays methods
    public function storeHolidays(Request $request)
    {
        $this->validate($request, [
            'holiday_name' => 'required',
        ]);

        Holiday::insert([
            'holiday_name' => $request->holiday_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'shop_name' => $request->shop_name,
            'notes' => $request->notes,
        ]);

        return response()->json('Successfully Holiday Added!');
    }

    //get all branch for edit
    public function GetBranch()
    {
        $branches = Branch::orderBy('name', 'ASC')->get();
        return response()->json($branches);
    }

    //update holiday
    public function updateHoliday(Request $request)
    {
        $this->validate($request, [
            'holiday_name' => 'required',
        ]);
        $updateDesignation = Holiday::where('id', $request->id)->first();
        $updateDesignation->update([
            'holiday_name' => $request->holiday_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'shop_name' => $request->shop_name,
            'notes' => $request->notes,
        ]);

        return response()->json('Successfully Holidays Updated!');
    }

    //destroy holidays
    public function deleteHolidays(Request $request, $id)
    {
        $holiday = Holiday::find($id);
        $holiday->delete();
        return response()->json('Successfully Holiday Deleted');
    }
}
