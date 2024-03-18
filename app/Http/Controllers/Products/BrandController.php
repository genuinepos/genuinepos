<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\BrandService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\Products\BrandStoreRequest;
use App\Http\Requests\Products\BrandUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;

class BrandController extends Controller
{
    public function __construct(
        private BrandService $brandService,
        private UserActivityLogService $userActivityLogService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('product_brand_index'), 403);

        if ($request->ajax()) {
            return $this->brandService->brandsTable();
        }

        return view('product.brands.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('product_brand_add'), 403);

        return view('product.brands.ajax_view.create');
    }

    public function store(BrandStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $addBrand = $this->brandService->addBrand(request: $request, codeGenerator: $codeGenerator);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Brands->value, dataObj: $addBrand);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $addBrand;
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->can('product_brand_edit'), 403);

        $brand = $this->brandService->singleBrand(id: $id);

        return view('product.brands.ajax_view.edit', compact('brand'));
    }

    public function update($id, BrandUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateBrand = $this->brandService->updateBrand(id: $id, request: $request);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Brands->value, dataObj: $updateBrand);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Brand Updated successfully.'));
    }

    public function delete($id, Request $request)
    {
        abort_if(!auth()->user()->can('product_brand_delete'), 403);

        try {
            DB::beginTransaction();

            $deleteBrand = $this->brandService->deleteBrand($id);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Brands->value, dataObj: $deleteBrand);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Brand deleted successfully.'));
    }
}
