<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\Products\UnitStoreRequest;
use App\Http\Requests\Products\UnitUpdateRequest;

class UnitController extends Controller
{
    public function __construct(
        private UnitService $unitService,
        private UserActivityLogService $userActivityLogService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('product_unit_index'), 403);

        if ($request->ajax()) {

            return $this->unitService->unitsTable();
        }

        return view('product.units.index');
    }

    public function create($isAllowedMultipleUnit = 0)
    {
        abort_if(!auth()->user()->can('product_unit_add'), 403);

        $baseUnits = $this->unitService->units()->where('base_unit_id', null)->get();

        return view('product.units.ajax_view.create', compact('baseUnits', 'isAllowedMultipleUnit'));
    }

    public function store(UnitStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $addUnit = $this->unitService->addUnit($request);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Units->value, dataObj: $addUnit);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $addUnit;
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('product_unit_edit'), 403);

        $unit = $this->unitService->singleUnit(id: $id);
        $baseUnits = $this->unitService->units()->where('base_unit_id', null)->get();

        return view('product.units.ajax_view.edit', compact('baseUnits', 'unit'));
    }

    public function update(UnitUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $updateUnit = $this->unitService->updateUnit(id: $id, request: $request);

            if (isset($updateUnit)) {

                $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Units->value, dataObj: $updateUnit);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Unit is updated Successfully'));
    }

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('product_unit_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteUnit = $this->unitService->deleteUnit(id: $id);

            if ($deleteUnit['pass'] == false) {

                return response()->json(['errorMsg' => $deleteUnit['msg']]);
            }

            if ($deleteUnit) {

                $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Units->value, dataObj: $deleteUnit);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Unit is deleted successfully'));
    }
}
