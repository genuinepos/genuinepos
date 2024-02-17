<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\BrandService;
use App\Utils\UserActivityLogUtil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function __construct(
        private BrandService $brandService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('product_brand_index')) {
            abort(403, __('Access Forbidden.'));
        }

        if ($request->ajax()) {
            return $this->brandService->brandsTable();
        }

        return view('product.brands.index');
    }

    public function create()
    {
        if (!auth()->user()->can('product_brand_add')) {
            abort(403, __('Access Forbidden.'));
        }

        return view('product.brands.ajax_view.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('product_brand_add')) {
            abort(403, __('Access Forbidden.'));
        }

        $this->validate($request, [
            'name' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $addBrand = $this->brandService->addBrand($request);
            if ($addBrand) {
                $this->userActivityLogUtil->addLog(action: 1, subject_type: 22, data_obj: $addBrand);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $addBrand;
    }

    public function edit($id)
    {
        if (!auth()->user()->can('product_brand_edit')) {
            abort(403, __('Access Forbidden.'));
        }

        $brand = $this->brandService->singleBrand(id: $id);

        return view('product.brands.ajax_view.edit', compact('brand'));
    }

    // Update Brand method
    public function update($id, Request $request)
    {
        if (!auth()->user()->can('product_brand_edit')) {
            abort(403, __('Access Forbidden.'));
        }

        $this->validate($request, [
            'name' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $updateBrand = $this->brandService->updateBrand(id: $id, request: $request);

            if ($updateBrand) {
                $this->userActivityLogUtil->addLog(action: 2, subject_type: 22, data_obj: $updateBrand);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Brand Updated successfully.'));
    }

    // Delete Brand method//
    public function delete($id, Request $request)
    {
        if (!auth()->user()->can('product_brand_delete')) {
            abort(403, __('Access Forbidden.'));
        }

        try {
            DB::beginTransaction();
            $deleteBrand = $this->brandService->deleteBrand($id);

            if ($deleteBrand) {
                $this->userActivityLogUtil->addLog(action: 3, subject_type: 22, data_obj: $deleteBrand);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return response()->json(__('Brand deleted successfully.'));
    }
}
