<?php

namespace App\Services\Products;

use App\Utils\FileUploader;
use App\Models\Products\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BrandService
{
    public function brandsTable()
    {
        $brands = DB::table('brands')->orderBy('id', 'desc');

        return DataTables::of($brands)
            ->addIndexColumn()
            ->editColumn('photo', function ($row) {

                $photo = asset('images/general_default.png');

                if ($row->photo) {

                    $photo = file_link(fileType: 'brand', fileName: $row->photo);
                }

                return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $photo . '">';
            })
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('product_brand_edit')) {

                    $html .= '<a href="' . route('brands.edit', [$row->id]) . '" class="action-btn c-edit" id="editBrand" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('product_brand_delete')) {

                    $html .= '<a href="' . route('brands.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteBrand" title="Delete"><span class="fas fa-trash "></span></a>';
                }
                $html .= '</div>';

                return $html;
            })->rawColumns(['photo', 'action'])->make(true);
    }

    public function addBrand(object $request, object $codeGenerator): ?object
    {
        $code = $codeGenerator->brandCode();
        $addBrand = new Brand();
        $addBrand->code = $code;
        $addBrand->name = $request->name;

        if ($request->file('photo')) {

            $addBrand->photo = FileUploader::uploadWithResize(fileType: 'brand', uploadableFile: $request->file('photo'), height: 250, width: 250);
        }

        $addBrand->save();

        return $addBrand;
    }

    public function updateBrand(int $id, object $request): ?object
    {
        $updateBrand = $this->singleBrand($id);
        $updateBrand->name = $request->name;

        if ($request->file('photo')) {

            $uploadedFile = FileUploader::uploadWithResize(fileType: 'brand', uploadableFile: $request->file('photo'), height: 250, width: 250, deletableFile: $updateBrand->photo);

            $updateBrand->photo = $uploadedFile;
        }

        $updateBrand->save();

        return $updateBrand;
    }

    public function deleteBrand(int $id): object
    {
        $deleteBrand = $this->singleBrand(id: $id);

        if (isset($deleteBrand)) {

            $uploadedFile = FileUploader::deleteFile(fileType: 'brand', deletableFile: $deleteBrand->photo);

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
