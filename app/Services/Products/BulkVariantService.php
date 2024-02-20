<?php

namespace App\Services\Products;

use App\Enums\IsDeleteInUpdate;
use App\Models\Products\BulkVariant;
use Yajra\DataTables\Facades\DataTables;

class BulkVariantService
{
    public function bulkVariantListTable(): object
    {
        $bulkVariants = $this->bulkVariants(with: ['bulkVariantChild', 'createdBy:id,prefix,name,last_name'])->orderBy('id', 'desc')->get();

        return DataTables::of($bulkVariants)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('Action').'</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="'.route('product.bulk.variants.edit', [$row->id]).'" class="dropdown-item" id="edit">'.__('Edit').'</a>';
                $html .= '<a href="'.route('product.bulk.variants.delete', [$row->id]).'" class="dropdown-item" id="delete">'.__('Delete').'</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('bulk_variant_child', function ($row) {

                $html = '<p class="m-0 p-0">';
                foreach ($row->bulkVariantChild as $bulkVariantChild) {

                    $html .= $bulkVariantChild->name.', ';
                }

                $html .= '</p>';

                return $html;
            })

            ->editColumn('created_by', function ($row) {

                return $row?->createdBy?->prefix.' '.$row?->createdBy?->name.' '.$row?->createdBy?->last_name;
            })

            ->rawColumns(['action', 'bulk_variant_child', 'created_by'])
            ->make(true);
    }

    public function addBulkVariant(object $request): object
    {
        $addBulkVariant = new BulkVariant();
        $addBulkVariant->name = $request->name;
        $addBulkVariant->created_by_id = auth()->user()->id;
        $addBulkVariant->save();

        return $addBulkVariant;
    }

    public function deleteBulkVariant(int $id): object
    {
        $deleteBulkVariant = $this->singleBulkVariant(id: $id);

        if (! is_null($deleteBulkVariant)) {

            $deleteBulkVariant->delete();
        }

        return $deleteBulkVariant;
    }

    public function updateBulkVariant(object $request, int $id): object
    {
        $updateBulkVariant = $this->singleBulkVariant(id: $id, with: ['bulkVariantChild']);

        $updateBulkVariant->name = $request->name;
        $updateBulkVariant->save();

        foreach ($updateBulkVariant->bulkVariantChild as $bulkVariantChild) {

            $bulkVariantChild->is_delete_in_update = IsDeleteInUpdate::Yes->value;
            $bulkVariantChild->save();
        }

        return $updateBulkVariant;
    }

    public function bulkVariants(array $with = null): ?object
    {
        $query = BulkVariant::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleBulkVariant(int $id, array $with = null): ?object
    {
        $query = BulkVariant::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
