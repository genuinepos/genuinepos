<?php

namespace App\Http\Controllers\Products;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Products\CategoryService;
use App\Services\Users\UserActivityLogService;
use App\Interfaces\CodeGenerationServiceInterface;
use App\Http\Requests\Products\CategoryEditRequest;
use App\Http\Requests\Products\CategoryIndexRequest;
use App\Http\Requests\Products\CategoryStoreRequest;
use App\Http\Requests\Products\CategoryCreateRequest;
use App\Http\Requests\Products\CategoryDeleteRequest;
use App\Http\Requests\Products\CategoryUpdateRequest;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService, private UserActivityLogService $userActivityLogService)
    {
    }

    public function index(CategoryIndexRequest $request)
    {
        if ($request->ajax()) {

            return $this->categoryService->categoriesTable();
        }

        return view('product.categories.index');
    }

    public function create(CategoryCreateRequest $request)
    {
        return view('product.categories.ajax_view.category.create');
    }

    public function store(CategoryStoreRequest $request, CodeGenerationServiceInterface $codeGenerator)
    {
        try {
            DB::beginTransaction();

            $addCategory = $this->categoryService->addCategory(request: $request, codeGenerator: $codeGenerator);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Added->value, subjectType: UserActivityLogSubjectType::Categories->value, dataObj: $addCategory);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCategory;
    }

    public function edit($id, CategoryEditRequest $request)
    {
        $category = $this->categoryService->singleCategory($id);

        return view('product.categories.ajax_view.category.edit', compact('category'));
    }

    public function update($id, CategoryUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $updateCategory = $this->categoryService->updateCategory(id: $id, request: $request);

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Updated->value, subjectType: UserActivityLogSubjectType::Categories->value, dataObj: $updateCategory);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Category updated successfully'));
    }

    public function delete($id, CategoryDeleteRequest $request)
    {
        try {
            DB::beginTransaction();

            $deleteCategory = $this->categoryService->deleteCategory(id: $id);

            if ($deleteCategory['pass'] == false) {

                return response()->json(['errorMsg' => $deleteCategory['msg']]);
            }

            $this->userActivityLogService->addLog(action: UserActivityLogActionType::Deleted->value, subjectType: UserActivityLogSubjectType::Categories->value, dataObj: $deleteCategory['data']);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Category deleted Successfully'));
    }
}
