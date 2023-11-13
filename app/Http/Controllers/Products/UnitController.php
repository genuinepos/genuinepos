<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function __construct(
        private UnitService $unitService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('product_unit_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->unitService->unitsTable();
        }

        return view('product.units.index');
    }

    public function create($isAllowedMultipleUnit = 0)
    {
        if (!auth()->user()->can('product_unit_add')) {

            abort(403, 'Access Forbidden.');
        }

        $baseUnits = $this->unitService->units()->where('base_unit_id', null)->get();

        return view('product.units.ajax_view.create', compact('baseUnits', 'isAllowedMultipleUnit'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('product_unit_add')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'short_name' => 'required',
        ]);

        if ($request->as_a_multiplier_of_other_unit == 1) {

            $this->validate($request, [
                'base_unit_multiplier' => 'required|numeric',
                'base_unit_id' => 'required',
            ], [
                'base_unit_multiplier.required' => 'Amount field is required',
                'base_unit_id.required' => 'Base unit field is required',
            ]);
        }

        try {
            DB::beginTransaction();

            $addUnit = $this->unitService->addUnit($request);

            if ($addUnit) {

                $this->userActivityLogUtil->addLog(action: 1, subject_type: 23, data_obj: $addUnit);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $addUnit;
    }

    public function edit($id)
    {
        if (!auth()->user()->can('product_unit_edit')) {

            return response()->json('Access Denied');
        }

        $unit = $this->unitService->singleUnit(id: $id);
        $baseUnits = $this->unitService->units()->where('base_unit_id', null)->get();

        return view('product.units.ajax_view.edit', compact('baseUnits', 'unit'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('product_unit_edit')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => 'required',
            'short_name' => 'required',
        ]);

        if ($request->as_a_multiplier_of_other_unit == 1) {
            $this->validate($request, [
                'base_unit_multiplier' => 'required|numeric',
                'base_unit_id' => 'required',
            ], [
                'base_unit_multiplier.required' => 'Amount field is required',
                'base_unit_id.required' => 'Base unit field is required',
            ]);
        }

        try {

            DB::beginTransaction();

            $updateUnit = $this->unitService->updateUnit(id: $id, request: $request);

            if ($updateUnit) {

                $this->userActivityLogUtil->addLog(action: 2, subject_type: 23, data_obj: $updateUnit);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Unit is updated Successfully'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('product_unit_delete')) {

            return response()->json('Access Denied');
        }

        try {

            DB::beginTransaction();

            $deleteUnit = $this->unitService->deleteUnit(id: $id);

            if ($deleteUnit['pass'] == false) {

                return response()->json(['errorMsg' => $deleteUnit['msg']]);
            }

            if ($deleteUnit) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 23, data_obj: $deleteUnit['data']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Unit is deleted successfully'));
    }
}
