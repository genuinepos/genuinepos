<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;


class CategoryControlle extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }
    
    // Category main page/index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->category['category_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        
        $img_url = asset('public/uploads/category/');
        if ($request->ajax()) {
            $categories = Category::where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
           return DataTables::of($categories)
            ->addIndexColumn()
            ->editColumn('photo', function ($row) use($img_url) {
                return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="'.$img_url.'/'.$row->photo.'">';
            })
            ->addColumn('action', function($row) {
                // return $action_btn;
                $html = '<div class="dropdown table-dropdown">';
                if (auth()->user()->permission->category['category_edit'] == '1'){
                    $html .= '<a href="javascript:;" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->permission->category['category_delete'] == '1'){
                    $html .= '<a href="' . route('product.categories.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->setRowAttr([
                'data-href' => function ($row) {
                    return route('product.categories.edit', $row->id);
                }
            ])
            ->rawColumns(['photo', 'action'])
            ->make(true);
        }
        return view('product.categories.index');
    }

    // // Get all category by ajax
    // public function getAllCategory()
    // {
    //     $categories = Category::where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
    //     return view('product.categories.ajax_view.category_list', compact('categories'));
    // }

    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'required|unique:categories,name',
            'photo' => 'sometimes|image|max:2048',
        ]);

        if ($request->file('photo')) {
            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('public/uploads/category/' . $categoryPhotoName);
            Category::insert([
                'name' => $request->name,
                'photo' => $categoryPhotoName
            ]);
        }else {
            Category::insert([
                'name' => $request->name,
            ]);
        }
        return response()->json('Category created Successfully');
    }

    public function edit($categoryId)
    {
        $category = DB::table('categories')->where('id', $categoryId)->first();
        return view('product.categories.ajax_view.edit_modal_body', compact('category'));
    }

    public function update(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'name' => 'required|unique:categories,name,'.$request->id,
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
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('public/uploads/category/' . $categoryPhotoName);
            $updateCategory->update([
                'name' => $request->name,
                'photo' => $categoryPhotoName
            ]);
        }else {
            $updateCategory->update([
                'name' => $request->name,
            ]);
        }
        return response()->json('Category updated successfully');
    }

    public function delete(Request $request, $categoryId)
    {
        //return $categoryId;
        $deleteCategory = Category::find($categoryId);
        if ($deleteCategory->photo !== 'default.png') {
            if (file_exists(public_path('uploads/category/'.$deleteCategory->photo))) {
                unlink(public_path('uploads/category/'.$deleteCategory->photo));
            } 
        }
        if (!is_null($deleteCategory)) {
            $deleteCategory->delete();  
        }
        
        return response()->json('Category deleted Successfully');
    }
}
