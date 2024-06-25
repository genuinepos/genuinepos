<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\BrandService;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\Products\BrandEditRequest;
use App\Http\Requests\Products\BrandIndexRequest;
use App\Http\Requests\Products\BrandStoreRequest;
use App\Http\Requests\Products\BrandCreateRequest;
use App\Http\Requests\Products\BrandDeleteRequest;
use App\Http\Requests\Products\BrandUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;

class BrandController extends Controller
{
    public function __construct(private BrandService $brandService, private UserActivityLogService $userActivityLogService)
    {
    }

    public function index(BrandIndexRequest $request)
    {
        if ($request->ajax()) {
            return $this->brandService->brandsTable();
        }

        return view('product.brands.index');
    }

    public function create(BrandCreateRequest $request)
    {
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

    public function edit($id, BrandEditRequest $request)
    {
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

    public function delete($id, BrandDeleteRequest $request)
    {
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
