<?php

namespace App\Services\Sales;

use App\Enums\DiscountType;
use App\Enums\StatusBooleanType;
use App\Models\Sales\Discount;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DiscountService
{
    public function discountListTable(object $request): ?object
    {
        $generalSettings = config('generalSettings');
        $discounts = DB::table('discounts')->where('branch_id', auth()->user()->branch_id)
            ->leftJoin('brands', 'discounts.brand_id', 'brands.id')
            ->leftJoin('categories', 'discounts.category_id', 'categories.id')
            ->leftJoin('branches', 'discounts.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->select(
                'discounts.*',
                'brands.name as brand_name',
                'categories.name as category_name',
                'branches.name as branch_name',
                'branches.branch_code',
                'branches.area_name',
                'parentBranch.name as parent_branch_name',
            )->orderBy('id', 'desc')->get();

        return DataTables::of($discounts)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $html .= '<a class="dropdown-item" href="' . route('sales.discounts.edit', [$row->id]) . '" id="edit">' . __('Edit') . '</a>';
                $html .= '<a class="dropdown-item" id="delete" href="' . route('sales.discounts.delete', [$row->id]) . '">' . __('Delete') . '</a>';

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('start_at', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->start_at));
            })
            ->editColumn('end_at', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row->end_at));
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('discount_type', function ($row) {

                return DiscountType::tryFrom($row->discount_type)->name;
            })
            ->editColumn('status', function ($row) {

                if ($row->is_active == StatusBooleanType::Active->value) {
                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input"  id="change_status" data-url="' . route('sales.discounts.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important; background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                    $html .= '</div>';

                    return $html;
                } else {
                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input" id="change_status" data-url="' . route('sales.discounts.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    $html .= '</div>';

                    return $html;
                }
            })
            ->editColumn('products', function ($row) {

                $products = DB::table('discount_products')
                    ->where('discount_products.discount_id', $row->id)
                    ->leftJoin('products', 'discount_products.product_id', 'products.id')
                    ->select('products.name', 'products.product_code')->get();

                $list = '';
                foreach ($products as $index => $product) {

                    $list .= ' <p class="p-0 m-0" style="line-height: 1!important;">' . ($index + 1) . '. ' . $product->name . '(' . $product->product_code . '),</p> ';
                }

                return $list;
            })

            ->editColumn('discount_amount', function ($row) {
                $discountType = $row->discount_type == DiscountType::Fixed->value ? '(Fixed)' : '%';

                return \App\Utils\Converter::format_in_bdt($row->discount_amount) . $discountType;
            })
            ->rawColumns(['action', 'start_at', 'end_at', 'branch', 'discount_type', 'is_active', 'status', 'products'])
            ->make(true);
    }

    public function addDiscount(object $request): object
    {
        $addDiscount = new Discount();
        $addDiscount->branch_id = auth()->user()->branch_id;
        $addDiscount->name = $request->name;
        $addDiscount->priority = $request->priority;
        $addDiscount->start_at = date('Y-m-d', strtotime($request->start_at));
        $addDiscount->end_at = date('Y-m-d', strtotime($request->end_at));
        $addDiscount->discount_type = $request->discount_type;
        $addDiscount->discount_amount = $request->discount_amount;
        $addDiscount->price_group_id = $request->price_group_id;
        $addDiscount->is_active = isset($request->is_active) ? 1 : 0;
        $addDiscount->apply_in_customer_group = isset($request->apply_in_customer_group) ? 1 : 0;
        $addDiscount->brand_id = !isset($request->product_ids) ? $request->brand_id : null;
        $addDiscount->category_id = !isset($request->product_ids) ? $request->category_id : null;
        $addDiscount->save();

        return $addDiscount;
    }

    public function updateDiscount(object $request, int $id): object
    {
        $updateDiscount = $this->singleDiscount(id: $id, with: ['discountProducts']);

        foreach ($updateDiscount->discountProducts as $discountProduct) {

            $discountProduct->is_delete_in_update = 1;
            $discountProduct->save();
        }

        $updateDiscount->branch_id = auth()->user()->branch_id;
        $updateDiscount->name = $request->name;
        $updateDiscount->priority = $request->priority;
        $updateDiscount->start_at = date('Y-m-d', strtotime($request->start_at));
        $updateDiscount->end_at = date('Y-m-d', strtotime($request->end_at));
        $updateDiscount->discount_type = $request->discount_type;
        $updateDiscount->discount_amount = $request->discount_amount;
        $updateDiscount->price_group_id = $request->price_group_id;
        $updateDiscount->is_active = isset($request->is_active) ? 1 : 0;
        $updateDiscount->apply_in_customer_group = isset($request->apply_in_customer_group) ? 1 : 0;
        $updateDiscount->brand_id = !isset($request->product_ids) ? $request->brand_id : null;
        $updateDiscount->category_id = !isset($request->product_ids) ? $request->category_id : null;
        $updateDiscount->save();

        return $updateDiscount;
    }

    public function deleteDiscount(int $discountId): void
    {
        $deleteDiscount = $this->singleDiscount(id: $discountId);

        if (!is_null($deleteDiscount)) {

            $deleteDiscount->delete();
        }
    }

    public function changeDiscountStatus(int $id): void
    {
        $discount = $this->singleDiscount(id: $id);

        if ($discount->is_active == 1) {

            $discount->is_active = 0;
            $discount->save();
        } else {

            $discount->is_active = 1;
            $discount->save();
        }
    }

    public function restrictions(object $request): array
    {
        if (!isset($request->product_ids) && $request->brand_id == '' && $request->category_id == '') {

            return ['pass' => false, 'msg' => __('If applicable products field is empty. So please select a brand or category.')];
        }

        return ['pass' => true];
    }

    public function singleDiscount(int $id, array $with = null): ?object
    {
        $query = Discount::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
