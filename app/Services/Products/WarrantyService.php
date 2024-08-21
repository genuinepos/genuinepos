<?php

namespace App\Services\Products;

use App\Models\Products\Warranty;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WarrantyService
{
    public function warrantiesTable()
    {
        $warranties = DB::table('warranties')->orderBy('id', 'desc')->get();

        return DataTables::of($warranties)
            // ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';

                if (auth()->user()->can('product_warranty_edit')) {

                    $html .= '<a href="' . route('warranties.edit', $row->id) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }

                if (auth()->user()->can('product_warranty_delete')) {

                    $html .= '<a href="' . route('warranties.delete', $row->id) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                }
                $html .= '</div>';

                return $html;
            })
            ->editColumn('type', function ($row) {

                $row->type == 1 ? __('Warranty') : __('Guaranty');
            })
            ->editColumn('duration', function ($row) {

                return $row->duration . ' ' . $row->duration_type;
            })
            ->rawColumns(['action', 'type', 'duration_type'])
            ->smart(true)
            ->make(true);
    }

    public function addWarranty(object $request, object $codeGenerator): object
    {
        $code = $codeGenerator->warrantyCode();
        $addWarranty = new Warranty();
        $addWarranty->code = $code;
        $addWarranty->name = $request->name;
        $addWarranty->type = $request->type;
        $addWarranty->duration = $request->duration;
        $addWarranty->duration_type = $request->duration_type;
        $addWarranty->description = $request->description;
        $addWarranty->save();

        return $addWarranty;
    }

    public function updateWarranty(int $id, object $request): ?object
    {
        $updateWarranty = $this->singleWarranty(id: $id);
        $updateWarranty->name = $request->name;
        $updateWarranty->type = $request->type;
        $updateWarranty->duration = $request->duration;
        $updateWarranty->duration_type = $request->duration_type;
        $updateWarranty->description = $request->description;
        $updateWarranty->save();

        return $updateWarranty;
    }

    public function deleteWarranty(int $id): ?object
    {
        $deleteWarranty = $this->singleWarranty(id: $id);

        if (!is_null($deleteWarranty)) {

            $deleteWarranty->delete();
        }

        return $deleteWarranty;
    }

    public function warranties(array $with = null): ?object
    {
        $query = Warranty::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleWarranty(int $id, array $with = null): ?object
    {
        $query = Warranty::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
