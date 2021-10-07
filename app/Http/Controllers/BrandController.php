<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use DB;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Brand main page/index page
    public function index(Request $request)
    {
        if (auth()->user()->permission->brand['brand_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $img_url = asset('public/uploads/brand/');
        if ($request->ajax()) {
            $brands = DB::table('brands')->orderBy('id', 'DESC')->get();
            return DataTables::of($brands)
                ->addIndexColumn()
                ->editColumn('photo', function ($row) use ($img_url) {
                    return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $img_url . '/' . $row->photo . '">';
                })
                ->addColumn('action', function ($row) {
                    // return $action_btn;
                    $html = '<div class="dropdown table-dropdown">';
                    if (auth()->user()->permission->brand['brand_delete'] == '1') {
                        $html .= '<a href="javascript:;" class="action-btn c-edit edit" data-id="' . $row->id . '"><span class="fas fa-edit"></span></a>';
                    }

                    if (auth()->user()->permission->brand['brand_delete'] == '1') {
                        $html .= '<a href="' . route('product.brands.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
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
        return view('product.brands.index');
    }

    // Add Brand method
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ]);

        if ($request->file('photo')) {
            $brandPhoto = $request->file('photo');
            $brandPhotoName = uniqid() . '.' . $brandPhoto->getClientOriginalExtension();
            Image::make($brandPhoto)->resize(250, 250)->save('public/uploads/brand/' . $brandPhotoName);
            Brand::insert([
                'name' => $request->name,
                'photo' => $brandPhotoName
            ]);
        } else {
            Brand::insert([
                'name' => $request->name,
            ]);
        }

        return response()->json(__('brand.add_success'));
    }

    //edit method
    public function edit($id)
    {
        $data = DB::table('brands')->where('id', $id)->first();
        return view('product.brands.ajax_view.edit', compact('data'));
    }

    // Update Brand method
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ]);

        $updateBrand = Brand::where('id', $request->id)->first();
        if ($request->file('photo')) {
            if ($updateBrand->photo !== 'default.png') {
                if (file_exists(public_path('uploads/brand/' . $updateBrand->photo))) {
                    unlink(public_path('uploads/brand/' . $updateBrand->photo));
                }
            }
            $brandPhoto = $request->file('photo');
            $brandPhotoName = uniqid() . '.' . $brandPhoto->getClientOriginalExtension();
            Image::make($brandPhoto)->resize(250, 250)->save('public/uploads/brand/' . $brandPhotoName);
            $updateBrand->update([
                'name' => $request->name,
                'photo' => $brandPhotoName
            ]);
        } else {
            $updateBrand->update([
                'name' => $request->name,
            ]);
        }
        return response()->json(__('brand.update_success'));
    }

    // Delete Brand method
    public function delete(Request $request, $brandId)
    {
        return response()->json('Feature is disabled in this demo');
        $deleteBrand = Brand::find($brandId);
        if ($deleteBrand->photo !== 'default.png') {
            if (file_exists(public_path('uploads/brand/' . $deleteBrand->photo))) {
                unlink(public_path('uploads/brand/' . $deleteBrand->photo));
            }
        }
        if (!is_null($deleteBrand)) {
            $deleteBrand->delete();
        }
        return response()->json(__('brand.delete_success'));
    }
}
