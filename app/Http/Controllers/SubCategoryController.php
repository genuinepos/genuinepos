<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use DB;
class SubCategoryController extends Controller
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
          $categories = Category::join('categories as parentcat','parentcat.id','categories.parent_category_id')->select('parentcat.name as parentname','categories.*')->whereNotNull('categories.parent_category_id')->orderBy('id', 'DESC')->get();
           return DataTables::of($categories)
            ->addIndexColumn()
            ->editColumn('photo', function ($row) use($img_url) {
                return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="'.$img_url.'/'.$row->photo.'">';
            })
            ->addColumn('action', function($row) {
                // return $action_btn;
                $html = '<div class="dropdown table-dropdown">';
                if (auth()->user()->permission->category['category_edit'] == '1'){
                    $html .= '<a href="javascript:;" class="action-btn c-edit edit" data-id="'.$row->id.'"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->permission->category['category_delete'] == '1'){
                    $html .= '<a href="' . route('product.subcategories.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['photo', 'action'])
            ->make(true);
        }
        $category=DB::table('categories')->where('parent_category_id',NULL)->get();
        return view('product.sub_categories.index',compact('category'));

        // if (auth()->user()->permission->category['category_all'] == '0') {
        //     abort(403, 'Access Forbidden.');
        // }
        // return view('product.sub_categories.index');
    }

    // Get all category by ajax
    public function getAllSubCategory()
    {
        $sub_categories = Category::with('parent_category')->where('parent_category_id', '!=', NULL)->orderBy('id', 'DESC')->get();
        return view('product.sub_categories.ajax_view.sub_category_list', compact('sub_categories'));
    }

    public function getAllFormCategory()
    {
        $categories = Category::where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
        return response()->json($categories);
    }

    //edit
    public function edit($id)
    {
        $data = DB::table('categories')->where('id',$id)->first();
        $category = DB::table('categories')->where('parent_category_id',NULL)->get();
        return view('product.sub_categories.ajax_view.edit',compact('category','data'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'required|unique:categories,name',
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => 'Parent cateogry field is required']);

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
        return response()->json('Successfully category is added');
    }

    public function update(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'name' => 'required|unique:categories,name,'.$request->id,
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
        return response()->json('Successfully category is updated');
    }

    public function delete(Request $request, $categoryId)
    {
        //return $categoryId;
        $deleteCategory = Category::find($categoryId);
        if ($deleteCategory->photo !== 'default.png') {
            if (file_exists(public_path('uploads/category/' . $deleteCategory->photo))) {
                unlink(public_path('uploads/category/' . $deleteCategory->photo));
            }
        }

        if (!is_null($deleteCategory)) {
            $deleteCategory->delete();
        }
        return response()->json('Successfully category is deleted');
    }
}
