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
            ->leftJoin('categories as parentCategory', 'categories.id', 'parentCategory.id')
            ->whereNotNull('categories.parent_category_id')
            ->select(
                'categories.id',
                'categories.name',
                'categories.photo',
                'categories.description',
                'parentCategory.name as parent_category_name'
            )->orderBy('id', 'desc')->get();

        return DataTables::of($subCategories)
            ->addIndexColumn()
            ->editColumn('photo', function ($row) {

                $photo = asset('images/general_default.png');
                if ($row->photo) {

                    $photo = asset('uploads/category/' . $row->photo);
                }
                return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $photo . '">';
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('product_category_edit')) {

                    $html .= '<a href="' . route('subcategories.edit', [$row->id]) . '" class="action-btn c-edit" id="editSubcategory"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('product_category_delete')) {

                    $html .= '<a href="' . route('subcategories.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteSubcategory"><span class="fas fa-trash "></span></a>';
                }
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

            $subcategoryPhoto = $request->file('photo');
            $subcategoryPhotoName = uniqid() . '.' . $subcategoryPhoto->getClientOriginalExtension();
            Image::make($subcategoryPhoto)->resize(250, 250)->save('uploads/category/' . $subcategoryPhotoName);
            $addSubCategory->photo = $subcategoryPhotoName;
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

            if ($updateSubcategory->photo && file_exists(public_path('uploads/category/' . $updateSubcategory->photo))) {

                unlink(public_path('uploads/category/' . $updateSubcategory->photo));
            }

            $subcategoryPhoto = $request->file('photo');
            $subcategoryPhotoName = uniqid() . '.' . $subcategoryPhoto->getClientOriginalExtension();
            Image::make($subcategoryPhoto)->resize(250, 250)->save('uploads/category/' . $subcategoryPhotoName);
            $updateSubcategory->photo = $subcategoryPhotoName;
        }

        $updateSubcategory->save();

        return $updateSubcategory;
    }

    public function deleteSubcategory(int $id, object $request): ?object
    {
        $deleteSubcategory = $this->singleSubcategory(id: $id);

        if (isset($deleteSubcategory)) {

            if ($deleteSubcategory->photo && file_exists(public_path('uploads/category/' . $deleteSubcategory->photo))) {

                unlink(public_path('uploads/category/' . $deleteSubcategory->photo));
            }

            $deleteSubcategory->delete();
        }

        return $deleteSubcategory;
    }

    public function subcategories(array $with = null)
    {
        $query = Category::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
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
