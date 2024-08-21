<?php

namespace App\Http\Controllers\HRM;

use App\Services\Hrm\ShiftService;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\ShiftEditRequest;
use App\Http\Requests\HRM\ShiftIndexRequest;
use App\Http\Requests\HRM\ShiftStoreRequest;
use App\Http\Requests\HRM\ShiftCreateRequest;
use App\Http\Requests\HRM\ShiftDeleteRequest;
use App\Http\Requests\HRM\ShiftUpdateRequest;

class ShiftController extends Controller
{
    public function __construct(private ShiftService $shiftService)
    {
    }

    public function index(ShiftIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->shiftService->shiftsTable();
        }

        return view('hrm.shifts.index');
    }

    public function create(ShiftCreateRequest $request)
    {
        return view('hrm.shifts.ajax.create');
    }

    public function store(ShiftStoreRequest $request)
    {
        return $this->shiftService->addShift($request);
    }

    public function edit($id, ShiftEditRequest $request)
    {
        $shift = $this->shiftService->singleShift(id: $id);
        return view('hrm.shifts.ajax.edit', compact('shift'));
    }

    public function update($id, ShiftUpdateRequest $request)
    {
        $this->shiftService->updateShift(request: $request, id: $id);
        return response()->json(__('Shift updated successfully.'));
    }

    public function delete(ShiftDeleteRequest $request, $id)
    {
        $this->shiftService->deleteShift(id: $id);
        return response()->json(__('Shift deleted successfully.'));
    }
}
