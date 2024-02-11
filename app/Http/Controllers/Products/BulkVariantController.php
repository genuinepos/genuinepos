<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\BulkVariantChildService;
use App\Services\Products\BulkVariantService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkVariantController extends Controller
{
    public function __construct(
        private BulkVariantService $bulkVariantService,
        private BulkVariantChildService $bulkVariantChildService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('product_variant_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->bulkVariantService->bulkVariantListTable();
        }

        return view('product.bulk_variants.index');
    }

    public function create()
    {
        return view('product.bulk_variants.ajax_view.create');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('product_variant_add')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, ['name' => 'required']);

        try {
            DB::beginTransaction();

            $addBulkVariant = $this->bulkVariantService->addBulkVariant(request: $request);
            $this->bulkVariantChildService->addBulkVariantChild(request: $request, bulkVariantId: $addBulkVariant->id);
            $this->userActivityLogUtil->addLog(action: 1, subject_type: 24, data_obj: $addBulkVariant);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Bulk Variant created Successfully'));
    }

    public function edit($id)
    {
        $bulkVariant = $this->bulkVariantService->singleBulkVariant(id: $id, with: ['bulkVariantChild']);

        return view('product.bulk_variants.ajax_view.edit', compact('bulkVariant'));
    }

    public function update($id, Request $request)
    {
        if (! auth()->user()->can('product_variant_edit')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, ['name' => 'required']);

        try {
            DB::beginTransaction();

            $updateBulkVariant = $this->bulkVariantService->updateBulkVariant(request: $request, id: $id);
            $this->bulkVariantChildService->updateBulkVariantChild(request: $request, bulkVariantId: $updateBulkVariant->id);
            $this->userActivityLogUtil->addLog(action: 2, subject_type: 24, data_obj: $updateBulkVariant);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Bulk Variant is updated successfully'));
    }

    public function delete($id, Request $request)
    {
        if (! auth()->user()->can('product_variant_delete')) {

            return response()->json('Access Denied');
        }

        try {
            DB::beginTransaction();

            $deleteBulkVariant = $this->bulkVariantService->deleteBulkVariant(id: $id);
            $this->userActivityLogUtil->addLog(action: 3, subject_type: 24, data_obj: $deleteBulkVariant);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Bulk Variant deleted successfully'));
    }
}
