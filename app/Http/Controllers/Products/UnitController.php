<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\UnitService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\Products\UnitEditRequest;
use App\Http\Requests\Products\UnitIndexRequest;
use App\Http\Requests\Products\UnitStoreRequest;
use App\Http\Requests\Products\UnitCreateRequest;
use App\Http\Requests\Products\UnitDeleteRequest;
use App\Http\Requests\Products\UnitUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;

class UnitController extends Controller
{
    public function __construct(private UnitService $unitService, private UserActivityLogService $userActivityLogService)
    {
    }

    public function index(UnitIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->unitService->unitsTable();
        }

        return view('product.units.index');
    }

    public function create($isAllowedMultipleUnit = 0, UnitCreateRequest $request)
    {
        $baseUnits = $this->unitService->units()->where('base_unit_id', null)->get();

        return view('product.units.ajax_view.create', compact('baseUnits', 'isAllowedMultipleUnit'));
    }

    public function store(UnitStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $addUnit = $this->unitService->addUnit(request: $request, codeGenerator: $codeGenerator);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Units->value, dataObj: $addUnit);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $addUnit;
    }

    public function edit($id, UnitEditRequest $request)
    {
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

    public function delete($id, UnitDeleteRequest $request)
    {
        try {
            DB::beginTransaction();

            $deleteUnit = $this->unitService->deleteUnit(id: $id);

            if ($deleteUnit['pass'] == false) {

                return response()->json(['errorMsg' => $deleteUnit['msg']]);
            }

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Units->value, dataObj: $deleteUnit['data']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Unit is deleted successfully'));
    }
}
