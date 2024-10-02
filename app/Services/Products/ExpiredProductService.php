<?php

namespace App\Services\Products;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpiredProductService
{
    public function expiredProductsTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $expiredProducts = '';
        $query = DB::table('purchase_products')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.variant_id', 'product_variants.id')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->where('products.has_batch_no_expire_date', BooleanType::True->value)
            ->whereNotNull('purchase_products.expire_date')
            ->whereDate('purchase_products.expire_date', '<', date('Y-m-d'));

        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', null);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if (!empty($request->supplier_account_id)) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $purchases = $query->where('purchases.branch_id', auth()->user()->branch_id);
        }

        $expiredProducts = $query->select(
            [
                'products.id',
                'products.name',
                'product_variants.variant_name',
                'purchases.branch_id',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_invoice_id',
                'suppliers.name as supplier_name',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'purchase_products.batch_number',
                'purchase_products.expire_date',
                'purchase_products.net_unit_cost',
            ]
        )->orderBy('purchase_products.expire_date', 'asc');

        return DataTables::of($expiredProducts)
            // ->addColumn('multiple_check', function ($row) {

            //     return '<input id="' . $row->id . '" class="data_id sorting_disabled" type="checkbox" name="product_ids[]" value="' . $row->id . '"/>';
            // })
            ->editColumn('name', function ($row) {

                return $row->name . ($row->variant_name ? ' - ' . $row->variant_name : '');
            })
            ->editColumn('invoice_id', function ($row) {

                return '<a href="' . route('purchases.show', [$row->purchase_id]) . '" id="details_btn">' . $row->purchase_invoice_id . '</a>';
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
            ->editColumn('expire_date', function ($row) use ($generalSettings) {

                return date($generalSettings['business_or_shop__date_format'], strtotime($row->expire_date));
            })
            ->rawColumns(['name', 'invoice_id', 'branch'])
            ->smart(true)->make(true);
    }
}
