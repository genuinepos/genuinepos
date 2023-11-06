<?php

namespace App\Services\Products;

use App\Enums\IsDeleteInUpdate;
use App\Models\Products\BulkVariantChild;

class BulkVariantChildService
{
    public function addBulkVariantChild(object $request, int $bulkVariantId): void
    {
        foreach ($request->variant_child as $variant_child) {

            $addVariantChild = new BulkVariantChild();
            $addVariantChild->bulk_variant_id = $bulkVariantId;
            $addVariantChild->name = $variant_child;
            $addVariantChild->save();
        }
    }

    public function updateBulkVariantChild(object $request, $bulkVariantId): void
    {
        $index = 0;
        foreach ($request->variant_child_ids as $variant_child_id) {

            $addOrUpdateBulkVariantChild = '';
            $bulkVariantChild = $this->bulkVariantChild()->where('id', $variant_child_id)->where('bulk_variant_id', $bulkVariantId)->first();

            if ($bulkVariantChild) {

                $addOrUpdateBulkVariantChild = $bulkVariantChild;
            } else {

                $addOrUpdateBulkVariantChild = new BulkVariantChild();
            }

            $addOrUpdateBulkVariantChild->bulk_variant_id = $bulkVariantId;
            $addOrUpdateBulkVariantChild->name = $request->variant_child[$index];
            $addOrUpdateBulkVariantChild->is_delete_in_update = IsDeleteInUpdate::No->value;
            $addOrUpdateBulkVariantChild->save();

            $index++;
        }

        $deleteBulkVariantChild = $this->bulkVariantChild()->where('bulk_variant_id', $bulkVariantId)->where('is_delete_in_update', IsDeleteInUpdate::Yes->value)->get();
        if ($deleteBulkVariantChild->count() > 0) {

            foreach ($deleteBulkVariantChild as $deleteBulkVariantChild) {

                $deleteBulkVariantChild->delete();
            }
        }
    }

    public function bulkVariantChild(array $with = null): ?object
    {
        $query = BulkVariantChild::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleBulkVariantChild(int $id, array $with = null): ?object
    {
        $query = BulkVariantChild::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
