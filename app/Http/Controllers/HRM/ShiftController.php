<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Hrm\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    public function __construct()
    {

    }

    //shift page shown
    public function index(Request $request)
    {
        if (! auth()->user()->can('shift')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $assetTypes = DB::table('hrm_shifts')->orderBy('id', 'desc')->get();

            return DataTables::of($assetTypes)
                ->addIndexColumn()
                ->editColumn('start_time', fn ($row) => date('h:ia', \strtotime($row->start_time)))
                ->editColumn('endtime', fn ($row) => date('h:ia', \strtotime($row->endtime)))
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('hrm.shift.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('hrm.shift.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('hrm.shift.index');
    }

    public function shiftEdit($id)
    {
        if (! auth()->user()->can('shift')) {

            abort(403, 'Access Forbidden.');
        }
        $type = DB::table('hrm_shifts')->where('id', $id)->first();

        return view('hrm.shift.ajax.edit', compact('type'));
    }

    //all shift ajax call
    public function allShift()
    {
        // $shift = Shift::orderBy('id', 'DESC')->get();
        // return view('hrm.shift.ajax.list', compact('shift'));
    }

    //shift store method
    public function storeShift(Request $request)
    {
        if (! auth()->user()->can('shift')) {

            abort(403, 'Access Forbidden.');
        }
        $this->validate($request, [
            'shift_name' => 'required',
            'start_time' => 'required',
            'endtime' => 'required',
        ]);

        Shift::insert([
            'shift_name' => $request->shift_name,
            'start_time' => $request->start_time,
            'endtime' => $request->endtime,
        ]);

        return response()->json('Shift created successfully');
    }

    //update shift
    public function updateShift(Request $request)
    {
        if (! auth()->user()->can('shift')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'shift_name' => 'required',
            'start_time' => 'required',
            'endtime' => 'required',
        ]);

        $updateShift = Shift::where('id', $request->id)->first();
        if (isset($updateShift)) {
            $updateShift->update([
                'shift_name' => $request->shift_name,
                'start_time' => $request->start_time,
                'endtime' => $request->endtime,
            ]);

            return response()->json('Shift updated successfully.');
        }

        return response()->json('Shift update failed!');
    }

    public function deleteShift(Request $request, $id)
    {
        if (! auth()->user()->can('shift')) {

            abort(403, 'Access Forbidden.');
        }

        $shift = Shift::find($id);
        if (! is_null($shift)) {
            $shift->delete();
        }

        return response()->json('Shift deleted successfully.');
    }
}
