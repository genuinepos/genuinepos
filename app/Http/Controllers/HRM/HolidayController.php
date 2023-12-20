<?php

namespace App\Http\Controllers\HRM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Hrm\HolidayService;
use App\Services\Setups\BranchService;
use App\Services\Hrm\HolidayBranchService;

class HolidayController extends Controller
{
    public function __construct(
        private HolidayService $holidayService,
        private HolidayBranchService $holidayBranchService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('holidays_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->holidayService->holidaysTable(request: $request);
        }

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('hrm.holidays.index', compact('branches'));
    }

    public function create()
    {
        if (!auth()->user()->can('holidays_create')) {

            abort(403, 'Access Forbidden.');
        }

        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();
        return view('hrm.holidays.ajax_view.create', compact('branches'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('holidays_create')) {

            abort(403, 'Access Forbidden.');
        }

        $this->holidayService->storeAndUpdateValidation(request: $request);

        try {
            DB::beginTransaction();

            $addHoliday = $this->holidayService->addHoliday(request: $request);
            $this->holidayBranchService->addHolidayBranches(request: $request, holidayId: $addHoliday->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Holiday added successfully.'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('holidays_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $holiday = $this->holidayService->singleHoliday(id: $id, with: ['allowedBranches']);
        $branches = $this->branchService->branches()->where('parent_branch_id', null)->get();

        return view('hrm.holidays.ajax_view.edit', compact('holiday', 'branches'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('holidays_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->holidayService->storeAndUpdateValidation(request: $request);

        try {
            DB::beginTransaction();

            $updateHoliday = $this->holidayService->updateHoliday(request: $request, id: $id);
            $this->holidayBranchService->updateHolidayBranches(request: $request, holiday: $updateHoliday);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Holiday updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('holidays_delete')) {

            abort(403, 'Access Forbidden.');
        }

        $this->holidayService->deleteHoliday(id: $id);

        return response()->json(__('Holiday deletes successfully'));
    }
}
