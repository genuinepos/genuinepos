<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Utils\UserActivityLogUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\WarrantyService;

class WarrantyController extends Controller
{
    public function __construct(
        private WarrantyService $warrantyService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('product_warranty_index')) {

            abort(403, __("Access Forbidden."));
        }

        if ($request->ajax()) {

            return $this->warrantyService->warrantiesTable();
        }

        return view('product.warranties.index');
    }

    public function create()
    {
        return view('product.warranties.ajax_view.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('product_warranty_add')) {

            return response()->json(__("Access Denied"));
        }

        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $addWarranty = $this->warrantyService->addWarranty(request: $request);

            if ($addWarranty) {

                $this->userActivityLogUtil->addLog(action: 1, subject_type: 25, data_obj: $addWarranty);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addWarranty;
    }

    public function edit($id)
    {
        $warranty = $this->warrantyService->singleWarranty(id: $id);
        return view('product.warranties.ajax_view.edit', compact('warranty'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('product_warranty_edit')) {

            return response()->json(__("Access Denied"));
        }

        $this->validate($request, [
            'name' => 'required',
            'duration' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $updateWarranty = $this->warrantyService->updateWarranty(id: $id, request: $request);

            if ($updateWarranty) {

                $this->userActivityLogUtil->addLog(action: 2, subject_type: 25, data_obj: $updateWarranty);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Warranty updated successfully"));
    }

    public function delete($id, Request $request)
    {
        if (!auth()->user()->can('product_warranty_delete')) {

            return response()->json(__("Access Denied"));
        }

        try {

            DB::beginTransaction();

            $deleteWarranty = $this->warrantyService->deleteWarranty(id: $id);

            if (!is_null($deleteWarranty)) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 25, data_obj: $deleteWarranty);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__("Warranty deleted successfully"));
    }
}
