<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use App\Services\Products\CategoryService;
use App\Services\Users\UserActivityLogService;
use App\Http\Requests\Products\CategoryStoreRequest;
use App\Http\Requests\Products\CategoryUpdateRequest;
use App\Interfaces\CodeGenerationServiceInterface;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private UserActivityLogService $userActivityLogService,
    ) {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('product_category_index'), 403);

        if ($request->ajax()) {

            return $this->categoryService->categoriesTable();
        }

        return view('product.categories.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->can('product_category_index'), 403);

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

    public function edit($id)
    {
        abort_if(!auth()->user()->can('product_category_edit'), 403);

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

    public function delete(Request $request, $id)
    {
        abort_if(!auth()->user()->can('product_category_delete'), 403);

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
