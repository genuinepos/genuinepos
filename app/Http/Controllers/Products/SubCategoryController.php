<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Products\CategoryService;
use App\Services\Products\SubCategoryService;
use App\Services\Users\UserActivityLogService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Products\SubcategoryEditRequest;
use App\Http\Requests\Products\SubcategoryIndexRequest;
use App\Http\Requests\Products\SubcategoryStoreRequest;
use App\Http\Requests\Products\SubcategoryCreateRequest;
use App\Http\Requests\Products\SubcategoryDeleteRequest;
use App\Http\Requests\Products\SubcategoryUpdateRequest;

class SubCategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private SubCategoryService $subCategoryService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function index(SubcategoryIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->subCategoryService->subcategoriesTable();
        }
    }

    public function create(SubcategoryCreateRequest $request, $fixedParentCategoryId = null)
    {
        $fixedParentCategory = '';
        if (isset($fixedParentCategoryId)) {

            $fixedParentCategory = $this->categoryService->singleCategory(id: $fixedParentCategoryId);
        }

        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get();

        return view('product.categories.ajax_view.subcategory.create', compact('categories', 'fixedParentCategory'));
    }

    public function store(SubcategoryStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $addSubCategory = $this->subCategoryService->addSubcategory(request: $request, codeGenerator: $codeGenerator);
            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::SubCategories->value, dataObj: $addSubCategory);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addSubCategory;
    }

    public function edit($id, SubcategoryEditRequest $request)
    {
        $subcategory = $this->subCategoryService->singleSubcategory(id: $id);
        $categories = $this->categoryService->categories()->where('parent_category_id', null)->get();

        return view('product.categories.ajax_view.subcategory.edit', compact('categories', 'subcategory'));
    }

    public function update($id, SubcategoryUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateCategory = $this->subCategoryService->updateSubcategory(id: $id, request: $request);
            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::SubCategories->value, dataObj: $updateCategory);

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

    public function delete($id, SubcategoryDeleteRequest $request)
    {
        try {
            DB::beginTransaction();

            $deleteSubcategory = $this->subCategoryService->deleteSubcategory(id: $id, request: $request);
            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::SubCategories->value, dataObj: $deleteSubcategory);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Subcategory deleted Successfully'));
    }
}
