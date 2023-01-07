<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    protected $userActivityLogUtil;
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;

    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('units')) {

            abort(403, 'Access Forbidden.');
        }
        if ($request->ajax()) {
            $units = DB::table('units')->orderBy('id', 'desc')->get();
            return DataTables::of($units)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="' . route('product.units.edit', [$row->id]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                        $html .= '<a href="' . route('product.units.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash"></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('product.units.index');
    }

    public function getAllUnit()
    {
        $units = Unit::all();
        return view('product.units.ajax_view.unit_list', compact('units'));
    }
    public function edit($id)
    {
        $units = Unit::where('id', $id)->first();
        return view('product.units.ajax_view.edit', compact('units'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('units')) {

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

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('units')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required|unique:units,name,'.$request->id,
            'code' => 'required|unique:units,code_name,'.$request->id,
        ]);

        // $updateUnit = Unit::where('id', $request->id)->first();
        $updateUnit = Unit::find($id);
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
        if (!auth()->user()->can('units')) {

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
