<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Services\Hrm\ShiftService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function __construct(private ShiftService $shiftService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('shifts_index'), 403);

        if ($request->ajax()) {

            return $this->shiftService->shiftsTable();
        }

        return view('hrm.shifts.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('shifts_create'), 403);

        return view('hrm.shifts.ajax.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('shifts_create'), 403);

        $this->shiftService->addValidation(request: $request);
        return $this->shiftService->addShift($request);
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('shifts_edit'), 403);

        $shift = DB::table('hrm_shifts')->where('id', $id)->first();

        return view('hrm.shifts.ajax.edit', compact('shift'));
    }

    public function update($id, Request $request)
    {
        abort_if(!auth()->user()->can('shifts_edit'), 403);

        $this->shiftService->updateValidation(request: $request, id: $id);
        $this->shiftService->updateShift(request: $request, id: $id);

        return response()->json(__('Shift updated successfully.'));
    }

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('shifts_delete'), 403);

        $this->shiftService->deleteShift(id: $id);

        return response()->json(__('Shift deleted successfully.'));
    }
}
