<?php

namespace App\Services\Products;

use App\Models\Products\Category;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryService
{
    public function subcategoriesTable()
    {
        $subCategories = DB::table('categories')
            ->leftJoin('categories as parentcat', 'parentcat.id', 'categories.parent_category_id')
            ->select('parentcat.name as parentname', 'categories.*')
            ->whereNotNull('categories.parent_category_id')
            ->orderBy('id', 'DESC');

        $imgUrl = asset('uploads/category/');
        return DataTables::of($subCategories)
            ->addIndexColumn()
            ->editColumn('photo', function ($row) use ($imgUrl) {

                return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $imgUrl . '/' . $row->photo . '">';
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';
                $html .= '<a href="' . route('subcategories.edit', [$row->id]) . '" class="action-btn c-edit" id="editSubcategory"><span class="fas fa-edit"></span></a>';
                $html .= '<a href="' . route('subcategories.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteSubcategory"><span class="fas fa-trash "></span></a>';
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['photo', 'action'])
            ->make(true);
    }

    public function addSubCategory($request): ?object
    {
        $addSubCategory = new Category();
        $addSubCategory->name = $request->name;
        $addSubCategory->description = $request->description;
        $addSubCategory->parent_category_id = $request->parent_category_id ? $request->parent_category_id : null;

        if ($request->file('photo')) {

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/' . $categoryPhotoName);
            $addSubCategory = $categoryPhotoName;
        }

        $addSubCategory->save();

        return $addSubCategory;
    }

    public function updateSubcategory(int $id, object $request): ?object
    {
        $updateSubcategory = $this->singleSubcategory(id: $id);
        $updateSubcategory->name = $request->name;
        $updateSubcategory->description = $request->description;
        $updateSubcategory->parent_category_id = $request->parent_category_id ? $request->parent_category_id : null;

        if ($request->file('photo')) {

            if ($updateSubcategory->photo !== 'default.png') {

                if (file_exists(public_path('uploads/category/' . $updateSubcategory->photo))) {

                    unlink(public_path('uploads/category/' . $updateSubcategory->photo));
                }
            }

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/' . $categoryPhotoName);
            $updateSubcategory->photo = $categoryPhotoName;
        }

        $updateSubcategory->save();

        return $updateSubcategory;
    }

    function deleteSubcategory(int $id, object $request): ?object
    {
        $deleteSubcategory = $this->singleSubcategory(id: $id);

        if ($deleteSubcategory->photo !== 'default.png') {

            if (file_exists(public_path('uploads/category/' . $deleteSubcategory->photo))) {

                unlink(public_path('uploads/category/' . $deleteSubcategory->photo));
            }
        }

        $deleteSubcategory->delete();

        return $deleteSubcategory;
    }

    public function singleSubcategory(int $id, array $with = null)
    {
        $query = Category::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
