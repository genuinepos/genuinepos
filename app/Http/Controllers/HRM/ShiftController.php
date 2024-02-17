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
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('shifts_index')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->shiftService->shiftsTable();
        }

        return view('hrm.shifts.index');
    }

    public function create()
    {
        if (!auth()->user()->can('shifts_create')) {
            abort(403, 'Access Forbidden.');
        }

        return view('hrm.shifts.ajax.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('shifts_create')) {
            abort(403, 'Access Forbidden.');
        }

        $this->shiftService->addValidation(request: $request);
        return $this->shiftService->addShift($request);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('shifts_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $shift = DB::table('hrm_shifts')->where('id', $id)->first();

        return view('hrm.shifts.ajax.edit', compact('shift'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('shifts_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $this->shiftService->updateValidation(request: $request, id: $id);
        $this->shiftService->updateShift(request: $request, id: $id);

        return response()->json(__('Shift updated successfully.'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('shifts_delete')) {
            abort(403, 'Access Forbidden.');
        }

        $this->shiftService->deleteShift(id: $id);

        return response()->json(__('Shift deleted successfully.'));
    }
}
