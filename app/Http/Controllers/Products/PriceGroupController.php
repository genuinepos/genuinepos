<?php

namespace App\Http\Controllers\Products;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Products\PriceGroupService;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\Products\PriceGroupEditRequest;
use App\Http\Requests\Products\PriceGroupIndexRequest;
use App\Http\Requests\Products\PriceGroupStoreRequest;
use App\Http\Requests\Products\PriceGroupCreateRequest;
use App\Http\Requests\Products\PriceGroupDeleteRequest;
use App\Http\Requests\Products\PriceGroupUpdateRequest;
use App\Http\Requests\Products\PriceGroupChangeStatusRequest;

class PriceGroupController extends Controller
{
    public function __construct(private PriceGroupService $priceGroupService, private UserActivityLogService $userActivityLogService)
    {
    }

    public function index(PriceGroupIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->priceGroupService->priceGroupsTable();
        }

        return view('product.price_group.index');
    }

    public function create(PriceGroupCreateRequest $request)
    {
        return view('product.price_group.ajax_view.create');
    }

    public function store(PriceGroupStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $addPriceGroup = $this->priceGroupService->addPriceGroup($request);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::SellingPriceGroups->value, dataObj: $addPriceGroup);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addPriceGroup;
    }

    public function edit($id, PriceGroupEditRequest $request)
    {
        $priceGroup = $this->priceGroupService->singlePriceGroup(id: $id);

        return view('product.price_group.ajax_view.edit', compact('priceGroup'));
    }

    public function update($id, PriceGroupUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updatePriceGroup = $this->priceGroupService->updatePriceGroup(id: $id, request: $request);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::SellingPriceGroups->value, dataObj: $updatePriceGroup);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Price group updated Successfully'));
    }

    public function delete($id, PriceGroupDeleteRequest $request)
    {
        try {
            DB::beginTransaction();

            $deletePriceGroup = $this->priceGroupService->deletePriceGroup(id: $id);

            if (isset($deletePriceGroup['pass']) && $deletePriceGroup['pass'] == false) {

                return response()->json(['errorMsg' => $deletePriceGroup['msg']]);
            }

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::SellingPriceGroups->value, dataObj: $deletePriceGroup);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Price group delete Successfully.'));
    }

    public function changeStatus($id, PriceGroupChangeStatusRequest $request)
    {
        $changeStatus = $this->priceGroupService->changeStatus(id: $id);

        return response()->json($changeStatus['msg']);
    }
}
