<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    protected $userActivityLogUtil;

    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;

    }

    // Category main page/index page
    public function index(Request $request)
    {
        if (! auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        $img_url = asset('uploads/category/');

        if ($request->ajax()) {

            $categories = DB::table('categories')->where('parent_category_id', null)->orderBy('name', 'asc')->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->editColumn('photo', function ($row) use ($img_url) {

                    return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="'.$img_url.'/'.$row->photo.'">';
                })
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="javascript:;" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('product.categories.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('product.categories.edit', $row->id);
                    },
                ])->rawColumns(['photo', 'action'])->smart(true)->make(true);
        }

        $categories = DB::table('categories')->where('parent_category_id', null)->get();

        return view('product.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->can('categories')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) {
                return $query->where('parent_category_id', null);
            })],
            'photo' => 'sometimes|image|max:2048',
        ]);

        $addCategory = '';

        if ($request->file('photo')) {

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid().'.'.$categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/'.$categoryPhotoName);

            $addCategory = Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $categoryPhotoName,
            ]);
        } else {

            $addCategory = Category::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }

        if ($addCategory) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 20, data_obj: $addCategory);
        }

        return $addCategory;
    }

    public function edit($categoryId)
    {
        if (! auth()->user()->can('categories')) {

            return response()->json('Access Denied');
        }

        $category = DB::table('categories')->where('id', $categoryId)->first();

        return view('product.categories.ajax_view.edit_modal_body', compact('category'));
    }

    public function update(Request $request)
    {
        if (! auth()->user()->can('categories')) {

            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) use ($request) {
                return $query->where('parent_category_id', null)->where('id', '!=', $request->id);
            })],
            'photo' => 'sometimes|image|max:2048',
        ]);

        $updateCategory = Category::where('id', $request->id)->first();

        if ($request->file('photo')) {

            if ($updateCategory->photo !== 'default.png') {

                if (file_exists(public_path('uploads/category/'.$updateCategory->photo))) {

                    unlink(public_path('uploads/category/'.$updateCategory->photo));
                }
            }

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid().'.'.$categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/'.$categoryPhotoName);

            $updateCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $categoryPhotoName,
            ]);
        } else {

            $updateCategory->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 20, data_obj: $updateCategory);

        return response()->json('Category updated successfully');
    }

    public function delete(Request $request, $categoryId)
    {
        if (! auth()->user()->can('categories')) {

            return response()->json('Access Denied');
        }

        $deleteCategory = Category::with(['subCategories'])->where('id', $categoryId)->first();

        if (count($deleteCategory->subCategories) > 0) {

            return response()->json(['errorMsg' => 'Category can not be deleted. One or more sub-categories is belonging under this category.']);
        }

        if ($deleteCategory->photo !== 'default.png') {

            if (file_exists(public_path('uploads/category/'.$deleteCategory->photo))) {

                unlink(public_path('uploads/category/'.$deleteCategory->photo));
            }
        }

        if (! is_null($deleteCategory)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 20, data_obj: $deleteCategory);

            $deleteCategory->delete();
        }

        return response()->json('Category deleted Successfully');
    }
}
