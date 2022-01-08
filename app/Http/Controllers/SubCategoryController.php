<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;


class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Get all sub-categories by index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {
            return response()->json('Access Denied');
        }

        $img_url = asset('public/uploads/category/');
        if ($request->ajax()) {
            $subCategories = DB::table('categories')
                ->join('categories as parentcat', 'parentcat.id', 'categories.parent_category_id')
                ->select('parentcat.name as parentname', 'categories.*')
                ->whereNotNull('categories.parent_category_id')->orderBy('id', 'DESC');
            return DataTables::of($subCategories)
                ->addIndexColumn()
                ->editColumn('photo', function ($row) use ($img_url) {
                    return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $img_url . '/' . $row->photo . '">';
                })
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->permission->category['category_edit'] == '1') {
                        $html .= '<a href="javascript:;" class="action-btn c-edit edit_sub_cate" data-id="' . $row->id . '"><span class="fas fa-edit"></span></a>';
                    }

                    if (auth()->user()->permission->category['category_delete'] == '1') {
                        $html .= '<a href="' . route('product.subcategories.delete', [$row->id]) . '" class="action-btn c-delete" id="delete_sub_cate" title="Delete"><span class="fas fa-trash "></span></a>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['photo', 'action'])
                ->make(true);
        }
    }

    //edit
    public function edit($id)
    {
        if (auth()->user()->permission->product['categories'] == '0') {
            return response()->json('Access Denied');
        }

        $data = DB::table('categories')->where('id', $id)->first();
        $category = DB::table('categories')->where('parent_category_id', NULL)->get();
        return view('product.categories.ajax_view.edit_sub_category', compact('category', 'data'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) {
                return $query->where('parent_category_id', '!=', NULL);
            })],
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => 'Parent category field is required']);

        if ($request->file('photo')) {
            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('public/uploads/category/' . $categoryPhotoName);
            Category::insert([
                'name' => $request->name,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : NULL,
                'photo' => $categoryPhotoName
            ]);
        } else {
            Category::insert([
                'name' => $request->name,
                'parent_category_id' => 'required',
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : NULL,
            ]);
        }
        return response()->json('Subcategory created successfully');
    }

    public function update(Request $request)
    {
        if (auth()->user()->permission->product['categories'] == '0') {
            return response()->json('Access Denied');
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) use ($request) {
                return $query->where('parent_category_id', '!=', NULL)->where('id', '!=', $request->id);
            })],
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => 'Parent cateogry field is required']);

        $updateCategory = Category::where('id', $request->id)->first();

        if ($request->file('photo')) {
            if ($updateCategory->photo !== 'default.png') {
                if (file_exists(public_path('uploads/category/' . $updateCategory->photo))) {
                    unlink(public_path('uploads/category/' . $updateCategory->photo));
                }
            }
            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('public/uploads/category/' . $categoryPhotoName);
            $updateCategory->update([
                'name' => $request->name,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : NULL,
                'photo' => $categoryPhotoName
            ]);
        } else {
            $updateCategory->update([
                'name' => $request->name,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : NULL,
            ]);
        }
        return response()->json('Subcategory updated Successfully');
    }

    public function delete(Request $request, $categoryId)
    {
        if (auth()->user()->permission->product['categories'] == '0') {
            return response()->json('Access Denied');
        }
        
        $deleteCategory = Category::find($categoryId);
        if ($deleteCategory->photo !== 'default.png') {
            if (file_exists(public_path('uploads/category/' . $deleteCategory->photo))) {
                unlink(public_path('uploads/category/' . $deleteCategory->photo));
            }
        }

        if (!is_null($deleteCategory)) {
            $deleteCategory->delete();
        }
        return response()->json('Subcategory deleted Successfully');
    }
}
