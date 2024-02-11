<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\CategoryService;
use App\Services\Products\SubCategoryService;
use App\Utils\UserActivityLogUtil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private SubCategoryService $subCategoryService,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
        $this->middleware('expireDate');
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('product_category_index')) {

            return response()->json(__('Access Denied'));
        }

        if ($request->ajax()) {

            return $this->subCategoryService->subcategoriesTable();
        }
    }

    public function create($fixedParentCategoryId = null)
    {
        if (!auth()->user()->can('product_category_add')) {

            return response()->json(__('Access Denied'));
        }

        $fixedParentCategory = '';
        if (isset($fixedParentCategoryId)) {

            $fixedParentCategory = $this->categoryService->singleCategory(id: $fixedParentCategoryId);
        }

        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get();

        return view('product.categories.ajax_view.subcategory.create', compact('categories', 'fixedParentCategory'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('product_category_add')) {

            return response()->json(__('Access Denied'));
        }

        $this->validate($request, [
            'name' => 'required',
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => __('Parent category field is required.')]);

        try {

            DB::beginTransaction();

            $addSubCategory = $this->subCategoryService->addSubcategory($request);

            if ($addSubCategory) {

                $this->userActivityLogUtil->addLog(action: 1, subject_type: 21, data_obj: $addSubCategory);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addSubCategory;
    }

    public function edit($id)
    {
        if (!auth()->user()->can('product_category_edit')) {

            return response()->json(__('Access Denied'));
        }

        $subcategory = $this->subCategoryService->singleSubcategory(id: $id);
        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get();

        return view('product.categories.ajax_view.subcategory.edit', compact('categories', 'subcategory'));
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->can('product_category_edit')) {

            return response()->json(__('Access Denied'));
        }

        $this->validate($request, [
            'name' => 'required',
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => __('Parent category field is required')]);

        try {

            DB::beginTransaction();

            $updateCategory = $this->subCategoryService->updateSubcategory(id: $id, request: $request);

            if ($updateCategory) {

                $this->userActivityLogUtil->addLog(action: 2, subject_type: 21, data_obj: $updateCategory);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Subcategory updated Successfully'));
    }

    public function subcategoriesByCategoryId($categoryId)
    {
        return $this->subCategoryService->subcategories()->where('parent_category_id', $categoryId)->get();
    }

    public function delete(Request $request, $id)
    {
        if (!auth()->user()->can('product_category_delete')) {

            return response()->json(__('Access Denied'));
        }

        try {

            DB::beginTransaction();

            $deleteSubcategory = $this->subCategoryService->deleteSubcategory(id: $id, request: $request);

            if (!is_null($deleteSubcategory)) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 21, data_obj: $deleteSubcategory);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Subcategory deleted Successfully'));
    }
}
