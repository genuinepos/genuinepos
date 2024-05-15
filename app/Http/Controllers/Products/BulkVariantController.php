<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Products\BulkVariantService;
use App\Services\Users\UserActivityLogService;
use App\Services\Products\BulkVariantChildService;
use App\Http\Requests\Products\BulkVariantStoreRequest;
use App\Http\Requests\Products\BulkVariantUpdateRequest;

class BulkVariantController extends Controller
{
    public function __construct(
        private BulkVariantService $bulkVariantService,
        private BulkVariantChildService $bulkVariantChildService,
        private UserActivityLogService $userActivityLogService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('product_variant_index'), 403);

        if ($request->ajax()) {

            return $this->bulkVariantService->bulkVariantListTable();
        }

        return view('product.bulk_variants.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('product_variant_add'), 403);

        return view('product.bulk_variants.ajax_view.create');
    }

    public function store(BulkVariantStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $addBulkVariant = $this->bulkVariantService->addBulkVariant(request: $request);
            $this->bulkVariantChildService->addBulkVariantChild(request: $request, bulkVariantId: $addBulkVariant->id);
            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Variants->value, dataObj: $addBulkVariant);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Bulk Variant created Successfully'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('product_variant_edit'), 403);

        $bulkVariant = $this->bulkVariantService->singleBulkVariant(id: $id, with: ['bulkVariantChild']);

        return view('product.bulk_variants.ajax_view.edit', compact('bulkVariant'));
    }

    public function update($id, BulkVariantUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateBulkVariant = $this->bulkVariantService->updateBulkVariant(request: $request, id: $id);
            $this->bulkVariantChildService->updateBulkVariantChild(request: $request, bulkVariantId: $updateBulkVariant->id);
            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Variants->value, dataObj: $updateBulkVariant);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Bulk Variant is updated successfully'));
    }

    public function delete($id, Request $request)
    {
        abort_if(!auth()->user()->can('product_variant_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteBulkVariant = $this->bulkVariantService->deleteBulkVariant(id: $id);
            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Variants->value, dataObj: $updateBulkVariant);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Bulk Variant deleted successfully'));
    }
}
