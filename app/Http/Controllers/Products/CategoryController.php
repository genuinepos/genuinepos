<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\CategoryService;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('product_category_index')) {

            abort(403, __('Access Forbidden.'));
        }

        if ($request->ajax()) {

            return $this->categoryService->categoriesTable();
        }

        return view('product.categories.index');
    }

    public function create()
    {
        if (! auth()->user()->can('product_category_add')) {

            abort(403, __('Access Forbidden.'));
        }

        return view('product.categories.ajax_view.category.create');
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('product_category_add')) {

            return response()->json(__('Access Denied'));
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) {
                return $query->where('parent_category_id', null);
            })],
            'photo' => 'sometimes|image|max:2048',
        ]);

        try {

            DB::beginTransaction();

            $addCategory = $this->categoryService->addCategory($request);

            if ($addCategory) {

                $this->userActivityLogUtil->addLog(action: 1, subject_type: 20, data_obj: $addCategory);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCategory;
    }

    public function edit($id)
    {
        if (! auth()->user()->can('product_category_edit')) {

            return response()->json(__('Access Denied'));
        }

        $category = $this->categoryService->singleCategory($id);

        return view('product.categories.ajax_view.category.edit', compact('category'));
    }

    public function update($id, Request $request)
    {
        if (! auth()->user()->can('product_category_edit')) {

            return response()->json(__('Access Denied'));
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) use ($id) {
                return $query->where('parent_category_id', null)->where('id', '!=', $id);
            })],
            'photo' => 'sometimes|image|max:2048',
        ]);

        try {

            DB::beginTransaction();

            $updateCategory = $this->categoryService->updateCategory(id: $id, request: $request);

            if ($updateCategory) {

                $this->userActivityLogUtil->addLog(action: 2, subject_type: 20, data_obj: $updateCategory);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Category updated successfully'));
    }

    public function delete(Request $request, $id)
    {
        if (! auth()->user()->can('product_category_delete')) {

            return response()->json(__('Access Denied'));
        }

        try {

            DB::beginTransaction();

            $deleteCategory = $this->categoryService->deleteCategory(id: $id);

            if ($deleteCategory['pass'] == false) {

                return response()->json(['errorMsg' => $deleteCategory['msg']]);
            }

            if ($deleteCategory['pass'] == true) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 20, data_obj: $deleteCategory['data']);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json(__('Category deleted Successfully'));
    }
}
