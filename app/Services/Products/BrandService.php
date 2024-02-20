<?php

namespace App\Services\Products;

use App\Models\Products\Brand;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class BrandService
{
    public function brandsTable()
    {
        $brands = DB::table('brands')->orderBy('id', 'desc')->get();
        $imgUrl = asset('uploads/brand/');

        return DataTables::of($brands)
            ->addIndexColumn()
            ->editColumn('photo', function ($row) use ($imgUrl) {
                return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;" src="'.$imgUrl.'/'.$row->photo.'">';
            })
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="'.route('brands.edit', [$row->id]).'" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="'.route('brands.delete', [$row->id]).'" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                $html .= '</div>';

                return $html;
            })->rawColumns(['photo', 'action'])->make(true);
    }

    public function addBrand(object $request): ?object
    {
        $addBrand = new Brand();
        $addBrand->name = $request->name;

        if ($request->file('photo')) {

            $brandPhoto = $request->file('photo');
            $brandPhotoName = uniqid().'.'.$brandPhoto->getClientOriginalExtension();
            Image::make($brandPhoto)->resize(250, 250)->save('uploads/brand/'.$brandPhotoName);
            $addBrand->photo = $brandPhotoName;
        }

        $addBrand->save();

        return $addBrand;
    }

    public function updateBrand(int $id, object $request): ?object
    {
        $updateBrand = $this->singleBrand($id);
        $updateBrand->name = $request->name;

        if ($request->file('photo')) {

            if ($updateBrand->photo !== 'default.png') {

                if (file_exists(public_path('uploads/brand/'.$updateBrand->photo))) {

                    unlink(public_path('uploads/brand/'.$updateBrand->photo));
                }
            }

            $brandPhoto = $request->file('photo');
            $brandPhotoName = uniqid().'.'.$brandPhoto->getClientOriginalExtension();
            Image::make($brandPhoto)->resize(250, 250)->save('uploads/brand/'.$brandPhotoName);
            $updateBrand->photo = $brandPhotoName;
        }

        $updateBrand->save();

        return $updateBrand;
    }

    public function deleteBrand(int $id): ?object
    {
        $deleteBrand = $this->singleBrand(id: $id);

        if ($deleteBrand->photo !== 'default.png') {

            if (file_exists(public_path('uploads/brand/'.$deleteBrand->photo))) {

                unlink(public_path('uploads/brand/'.$deleteBrand->photo));
            }
        }

        if (! is_null($deleteBrand)) {

            $deleteBrand->delete();
        }

        return $deleteBrand;
    }

    public function brands(array $with = null): ?object
    {
        $query = Brand::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleBrand(int $id, array $with = null): ?object
    {
        $query = Brand::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
