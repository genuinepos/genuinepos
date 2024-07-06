<?php

namespace App\Services\Products;

use App\Enums\CategoryType;
use App\Utils\CloudFileUploader;
use App\Models\Products\Category;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CategoryService
{
    public function categoriesTable()
    {
        $categories = DB::table('categories')->where('parent_category_id', null)->orderBy('name', 'asc');

        return DataTables::of($categories)
            // ->addIndexColumn()
            ->editColumn('photo', function ($row) {

                $photo = asset('images/general_default.png');
                if ($row->photo) {

                    $photo = Storage::disk('s3')->url(tenant('id') . '/' . 'categories/' . $row->photo);
                }

                return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $photo . '">';
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('product_category_edit')) {

                    $html .= '<a href="' . route('categories.edit', [$row->id]) . '" class="action-btn c-edit" id="editCategory" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('product_category_delete')) {

                    $html .= '<a href="' . route('categories.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteCategory" title="Delete"><span class="fas fa-trash "></span></a>';
                }
                $html .= '</div>';

                return $html;
            })
            // ->setRowAttr([
            //     'data-href' => function ($row) {
            //         return route('categories.edit', $row->id);
            //     },
            // ])
            ->rawColumns(['photo', 'action'])->smart(true)->make(true);
    }

    public function addCategory(object $request, object $codeGenerator): ?object
    {
        $code = $codeGenerator->categoryCode(type: CategoryType::MainCategory->value);
        $addCategory = new Category();
        $addCategory->code = $code;
        $addCategory->name = $request->name;
        $addCategory->description = $request->description;

        if ($request->file('photo')) {

            $dir = tenant('id') . '/' . 'categories/';
            $addCategory->photo = CloudFileUploader::uploadWithResize(path: $dir, uploadableFile: $request->file('photo'), height: 250, width: 250);
        }

        $addCategory->save();

        return $addCategory;
    }

    public function updateCategory(int $id, object $request): object
    {
        $updateCategory = $this->singleCategory(id: $id);
        $updateCategory->name = $request->name;
        $updateCategory->description = $request->description;

        if ($request->file('photo')) {

            $dir = tenant('id') . '/' . 'categories/';
            $uploadedFile = CloudFileUploader::uploadWithResize(
                path: $dir,
                uploadableFile: $request->file('photo'),
                height: 250,
                width: 250,
                deletableFile: $updateCategory->photo,
            );

            $updateCategory->photo = $uploadedFile;
        }

        $updateCategory->save();

        return $updateCategory;
    }

    public function deleteCategory(int $id): array
    {
        $deleteCategory = $this->singleCategory(id: $id, with: ['subCategories']);

        if (count($deleteCategory->subCategories) > 0) {

            return ['pass' => false, 'msg' => 'Category can not be deleted. One or more sub-categories is belonging under this category.'];
        }

        if ($deleteCategory->photo) {

            $dir = tenant('id') . '/' . 'categories/';
            $uploadedFile = CloudFileUploader::deleteFile(path: $dir, deletableFile: $deleteCategory->photo);
        }

        if (!is_null($deleteCategory)) {

            $deleteCategory->delete();
        }

        return ['pass' => true, 'data' => $deleteCategory];
    }

    public function categories(array $with = null)
    {
        $query = Category::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleCategory(int $id, array $with = null)
    {
        $query = Category::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function categoryByConditionSingleOrCollection(array $with = null)
    {
        $query = Category::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
