<?php

namespace App\Http\Controllers\Products;

use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Products\CategoryService;
use App\Services\Users\UserActivityLogService;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('product_category_index')) {

            abort(403, __('Access Forbidden.'));
        }

        if ($request->ajax()) {

            return $this->categoryService->categoriesTable();
        }

        return view('product.categories.index');
    }

    public function create()
    {
        if (!auth()->user()->can('product_category_add')) {

            abort(403, __('Access Forbidden.'));
        }

        return view('product.categories.ajax_view.category.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('product_category_add')) {

            return response()->json(__('Access Forbidden'));
        }

        $this->categoryService->storeValidation(request: $request);

        try {

            DB::beginTransaction();

            $addCategory = $this->categoryService->addCategory($request);

            if ($addCategory) {

                $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Categories->value, dataObj: $addCategory);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCategory;
    }

    public function edit($id)
    {
        if (!auth()->user()->can('product_category_edit')) {

            return response()->json(__('Access Forbidden'));
        }

        $category = $this->categoryService->singleCategory($id);

        return view('product.categories.ajax_view.category.edit', compact('category'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('product_category_edit')) {

            return response()->json(__('Access Forbidden'));
        }

        $this->categoryService->updateValidation(request: $request, id: $id);

        try {

            DB::beginTransaction();

            $updateCategory = $this->categoryService->updateCategory(id: $id, request: $request);

            if ($updateCategory) {

                $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Categories->value, dataObj: $updateCategory);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Category updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('product_category_delete')) {

            return response()->json(__('Access Forbidden'));
        }

        try {

            DB::beginTransaction();

            $deleteCategory = $this->categoryService->deleteCategory(id: $id);

            if ($deleteCategory['pass'] == false) {

                return response()->json(['errorMsg' => $deleteCategory['msg']]);
            }

            if ($deleteCategory['pass'] == true) {

                $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Categories->value, dataObj: $deleteCategory['data']);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Category deleted Successfully'));
    }
}
