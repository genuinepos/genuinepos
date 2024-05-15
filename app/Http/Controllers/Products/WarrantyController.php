<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Products\WarrantyService;
use App\Services\Users\UserActivityLogService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Products\WarrantyStoreRequest;
use App\Http\Requests\Products\WarrantyUpdateRequest;

class WarrantyController extends Controller
{
    public function __construct(
        private WarrantyService $warrantyService,
        private UserActivityLogService $userActivityLogService
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('product_warranty_index'), 403);

        if ($request->ajax()) {

            return $this->warrantyService->warrantiesTable();
        }

        return view('product.warranties.index');
    }

    public function create()
    {
        return view('product.warranties.ajax_view.create');
    }

    public function store(WarrantyStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $addWarranty = $this->warrantyService->addWarranty(request: $request, codeGenerator: $codeGenerator);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Warranties->value, dataObj: $addWarranty);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addWarranty;
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('product_warranty_edit'), 403);

        $warranty = $this->warrantyService->singleWarranty(id: $id);

        return view('product.warranties.ajax_view.edit', compact('warranty'));
    }

    public function update($id, WarrantyUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateWarranty = $this->warrantyService->updateWarranty(id: $id, request: $request);

            if ($updateWarranty) {

                $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Warranties->value, dataObj: $updateWarranty);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Warranty updated successfully'));
    }

    public function delete($id, Request $request)
    {
        abort_if(!auth()->user()->can('product_warranty_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteWarranty = $this->warrantyService->deleteWarranty(id: $id);

            if (isset($deleteWarranty)) {

                $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Warranties->value, dataObj: $deleteWarranty);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Warranty deleted successfully'));
    }
}
