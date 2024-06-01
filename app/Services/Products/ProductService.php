<?php

namespace App\Services\Products;

use App\Enums\BooleanType;
use App\Enums\IsDeleteInUpdate;
use App\Enums\RoleType;
use App\Models\Products\Product;
use App\Models\Products\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ProductService
{
    public function productListTable(object $request, int $isForCreatePage = 0): object
    {
        $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $generalSettings = config('generalSettings');
        $countPriceGroup = DB::table('price_groups')->where('status', 'Active')->count();

        $products = '';

        $query = Product::query()->with([
            'productAccessBranches',
            'productAccessBranches.branch:id,name,area_name,branch_code,parent_branch_id',
            'productAccessBranches.branch.parentBranch:id,name,area_name,branch_code',
            'ownBranchAllStocks:id,branch_id,stock,product_id,variant_id',
        ])
            ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id')
            ->leftJoin('accounts as tax', 'products.tax_ac_id', 'tax.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id')
            ->leftJoin('units', 'products.unit_id', 'units.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('product_access_branches.branch_id', null);
            } else {

                $query->where('product_access_branches.branch_id', $request->branch_id);
            }
        }

        if ($request->type == 1) {

            $query->where('products.type', 1)->where('products.is_variant', 0);
        }

        if ($request->type == 1) {

            $query->where('products.type', 1)->where('products.is_variant', 0);
        }

        if ($request->type == 2) {

            $query->where('products.is_variant', 1)->where('products.type', 1);
        }

        if ($request->type == 3) {

            $query->where('products.type', 2)->where('products.is_combo', 1);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->sub_category_id) {

            $query->where('products.sub_category_id', $request->sub_category_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_ac_id) {

            $query->where('products.tax_ac_id', $request->tax_ac_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->status != '') {

            $query->where('products.status', $request->status);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId);
        }

        $products = $query->select(
            [
                'products.id',
                'products.name',
                'products.product_code',
                'products.status',
                'products.is_variant',
                'products.type',
                'products.product_cost_with_tax',
                'products.product_price',
                'products.is_manage_stock',
                'products.thumbnail_photo',
                'products.expire_date',
                'products.is_combo',
                'products.quantity',
                'units.name as unit_name',
                'tax.name as tax_name',
                'categories.name as cate_name',
                'sub_cate.name as sub_cate_name',
                'brands.name as brand_name',
            ]
        )->distinct('product_access_branches.branch_id')->orderBy('products.id', 'desc');

        return DataTables::of($products)
            ->addColumn('multiple_delete', function ($row) {

                return '<input id="' . $row->id . '" class="data_id sorting_disabled" type="checkbox" name="data_ids[]" value="' . $row->id . '"/>';
            })->editColumn('photo', function ($row) {

                $photo = asset('images/general_default.png');

                if ($row->thumbnail_photo) {

                    $photo = asset('uploads/product/thumbnail/' . $row->thumbnail_photo);
                }

                return '<img loading="lazy" class="rounded" style="height:30px; width:30px; padding:2px 0px;" src="' . $photo . '">';
            })->addColumn('action', function ($row) use ($countPriceGroup, $isForCreatePage) {

                if ($isForCreatePage == BooleanType::False->value) {

                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ' . __('Action') . '</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a href="' . route('products.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                    if (auth()->user()->can('product_edit')) {

                        $html .= '<a class="dropdown-item" href="' . route('products.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                    }

                    if (auth()->user()->can('product_delete')) {

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('products.delete', [$row->id]) . '">' . __('Delete') . '</a>';
                    }

                    // if ($row->status == 1) {

                    //     $html .= '<a class="dropdown-item" id="change_status" href="' . route('products.change.status', [$row->id]) . '"><i class="far fa-thumbs-up text-success"></i> Change Status</a>';
                    // } else {

                    //     $html .= '<a class="dropdown-item" id="change_status" href="' . route('products.change.status', [$row->id]) . '"><i class="far fa-thumbs-down text-danger"></i> Change Status</a>';
                    // }

                    $html .= '<a href="' . route('products.ledger.index', [$row->id]) . '" class="dropdown-item">' . __('Product Ledger') . '</a>';

                    if ($countPriceGroup > 0) {

                        $html .= '<a href="' . route('selling.price.groups.manage.index', [$row->id, $row->is_variant]) . '" class="dropdown-item"> ' . __('Manage Price Group') . '</a>';
                    }

                    if (auth()->user()->can('openingStock_add')) {

                        $html .= '<a href="' . route('product.opening.stocks.create', [$row->id]) . '" class="dropdown-item" id="openingStock">' . __('Add or edit opening stock') . '</a>';
                    }

                    if (auth()->user()->can('product_add')) {

                        $html .= '<a class="dropdown-item" href="' . route('products.create', [$row->id]) . '">' . __('Duplicate Product') . '</a>';
                    }

                    $html .= ' </div>';
                    $html .= '</div>';

                    return $html;
                } elseif ($isForCreatePage == BooleanType::True->value) {

                    return '<a class="action-btn c-edit" href="' . route('products.edit', [$row->id]) . '">' . __('Edit') . '</a>';
                }
            })->editColumn('name', function ($row) {
                $html = '';
                $html .= $row->name;
                $html .= $row->is_manage_stock == 0 ? ' <span class="badge bg-primary pt-1"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '';

                return $html;
            })->editColumn('type', function ($row) {

                if ($row->type == 1 && $row->is_variant == 1) {

                    return '<span class="text-primary">' . __('Variant') . '</span>';
                } elseif ($row->type == 1 && $row->is_variant == 0) {

                    return '<span class="text-success">' . __('Single') . '</span>';
                } elseif ($row->type == 2) {

                    return '<span class="text-info">' . __('Combo') . '</span>';
                } elseif ($row->type == 3) {

                    return '<span class="text-info">' . __('Digital') . '</span>';
                }
            })
            ->editColumn('cate_name', fn ($row) => '<p class="p-0">' . ($row->cate_name ? $row->cate_name : '...') . '</p><p class="p-0">' . ($row->sub_cate_name ? ' --- ' . $row->sub_cate_name : '') . '</p>')

            ->editColumn('status', function ($row) {

                if ($row->status == BooleanType::True->value) {
                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input"  id="change_status" data-url="' . route('products.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                    $html .= '</div>';

                    return $html;
                } else {
                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input" id="change_status" data-url="' . route('products.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    $html .= '</div>';

                    return $html;
                }
            })
            ->editColumn('access_branches', function ($row) use ($generalSettings, $request, $ownBranchIdOrParentBranchId) {

                $productAccessBranches = $row->productAccessBranches;

                if ($request->branch_id) {

                    if ($request->branch_id == 'NULL') {

                        $productAccessBranches->where('branch_id', null);
                    } else {

                        // $query->where('product_branches.branch_id', $request->branch_id);
                        $productAccessBranches->where('branch_id', $request->branch_id);
                    }
                }

                // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                //     $productAccessBranches->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId);
                // }

                if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $productAccessBranches->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId);
                }

                $text = '';
                foreach ($productAccessBranches as $productAccessBranch) {

                    if ($productAccessBranch?->branch_id == null) {

                        continue;
                    }

                    $branchName = $productAccessBranch?->branch?->parent_branch_id ? $productAccessBranch?->branch?->name : $productAccessBranch?->branch?->name;

                    $__branchName = isset($branchName) ? $branchName : $generalSettings['business_or_shop__business_name'];

                    $areaName = $productAccessBranch?->branch?->area_name ? '(' . $productAccessBranch?->branch?->area_name . ')' : '';

                    $text .= '<p class="m-0 p-0" style="font-size: 9px; line-height: 11px; font-weight: 600; letter-spacing: 1px;">' . $__branchName . ',</p>';
                }

                return $text;
            })
            ->editColumn('product_cost_with_tax', function ($row) {

                // $quantity = $productStock->branchWiseSingleProductStock($row->id, $request->branch_id);

                return \App\Utils\Converter::format_in_bdt($row->product_cost_with_tax);
            })
            ->editColumn('product_price', function ($row) {

                // $quantity = $productStock->branchWiseSingleProductStock($row->id, $request->branch_id);

                return \App\Utils\Converter::format_in_bdt($row->product_price);
            })
            ->editColumn('quantity', function ($row) {

                // $quantity = $productStock->branchWiseSingleProductStock($row->id, $request->branch_id);

                if (auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $branchAllStock = 0;
                    foreach ($row->ownBranchAllStocks as $stock) {

                        $branchAllStock += $stock->stock;
                    }

                    return \App\Utils\Converter::format_in_bdt($branchAllStock) . '/' . $row->unit_name;
                }

                return \App\Utils\Converter::format_in_bdt($row->quantity) . '/' . $row->unit_name;
            })
            ->editColumn('brand_name', fn ($row) => $row->brand_name ? $row->brand_name : '...')
            ->editColumn('tax_name', fn ($row) => $row->tax_name ? $row->tax_name : '...')
            ->rawColumns(['multiple_delete', 'photo', 'product_cost_with_tax', 'product_cost_with_tax', 'quantity', 'action', 'name', 'type', 'cate_name', 'status', 'expire_date', 'tax_name', 'brand_name', 'access_branches'])
            ->smart(true)->make(true);
    }

    public function productShowQueries(int $id): array
    {
        $data = [];

        $data['product'] = $this->singleProduct(id: $id, with: [
            'brand',
            'category',
            'subcategory',
            'warranty',
            'variants',
            'variants.priceGroups',
            'tax',
            'unit',
            'priceGroups',
        ]);

        $ownBranchAndWarehouseStocksQ = DB::table('product_ledgers')
            ->where('product_ledgers.branch_id', auth()->user()->branch_id)
            ->where('product_ledgers.product_id', $data['product']->id)
            ->leftJoin('product_variants', 'product_ledgers.variant_id', 'product_variants.id')
            ->leftJoin('branches', 'product_ledgers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('warehouses', 'product_ledgers.warehouse_id', 'warehouses.id')
            ->select(
                'branches.name as branch_name',
                'branches.area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'warehouses.warehouse_name',
                'warehouses.is_global',
                'product_ledgers.product_id',
                'product_ledgers.variant_id',
                'product_ledgers.branch_id',
                'product_ledgers.warehouse_id',
                'product_variants.variant_name',
                DB::raw('IFNULL(SUM(product_ledgers.in - product_ledgers.out), 0) as stock'),
                DB::raw('IFNULL(SUM(case when voucher_type = 0 then product_ledgers.in end), 0) as total_opening_stock'),
                DB::raw('IFNULL(SUM(case when voucher_type = 7 then product_ledgers.out end), 0) as total_transferred'),
                DB::raw('IFNULL(SUM(case when voucher_type = 8 then product_ledgers.in end), 0) as total_received'),
                DB::raw('IFNULL(SUM(case when voucher_type = 6 then product_ledgers.in end), 0) as total_production'),
                DB::raw('IFNULL(SUM(case when voucher_type = 6 then product_ledgers.out end), 0) as total_used_in_production'),
                DB::raw('IFNULL(SUM(case when voucher_type = 3 then product_ledgers.in end), 0) as total_purchase'),
                DB::raw('IFNULL(SUM(case when voucher_type = 4 then product_ledgers.out end), 0) as total_purchase_return'),
                DB::raw('IFNULL(SUM(case when voucher_type = 5 then product_ledgers.out end), 0) as total_stock_adjustment'),
                DB::raw('IFNULL(SUM(case when voucher_type = 2 then product_ledgers.in end), 0) as total_sales_return'),
                DB::raw('IFNULL(SUM(case when voucher_type = 1 then product_ledgers.out end), 0) as total_sale'),
                DB::raw('SUM(case when product_ledgers.in != 0 then product_ledgers.subtotal end) as total_cost'),
            )->groupBy(
                'branches.name',
                'branches.area_name',
                'branches.branch_code',
                'parentBranch.name',
                'warehouses.warehouse_name',
                'warehouses.is_global',

                'product_ledgers.branch_id',
                'product_ledgers.warehouse_id',
                'product_ledgers.product_id',
                'product_ledgers.variant_id',
            );

        $data['ownBranchAndWarehouseStocks'] = $ownBranchAndWarehouseStocksQ
            ->orderBy('product_ledgers.branch_id', 'asc')
            ->orderBy('product_ledgers.product_id', 'desc')
            ->orderBy('product_ledgers.variant_id', 'desc')
            ->get();

        $data['globalWareHouseStocks'] = $ownBranchAndWarehouseStocksQ->where('warehouses.is_global', 1)
            ->orderBy('product_ledgers.branch_id', 'asc')
            ->orderBy('product_ledgers.product_id', 'desc')
            ->orderBy('product_ledgers.variant_id', 'desc')
            ->get();

        return $data;
    }

    public function createProductListOfProducts()
    {
        $products = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->select('products.id', 'products.name', 'products.product_cost', 'products.product_price')
            ->where('product_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('products.id', 'desc');

        return DataTables::of($products)
            ->addColumn('action', fn ($row) => '<a href="' . route('products.edit', [$row->id]) . '" class="action-btn c-edit" title="Edit"><span class="fas fa-edit"></span></a>')
            ->editColumn('name', fn ($row) => '<span title="' . $row->name . '">' . Str::limit($row->name, 25) . '</span>')
            ->rawColumns(['action', 'name'])->make(true);
    }

    public function addProduct($request)
    {
        $addProduct = new Product();
        $addProduct->type = $request->type;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->code ? $request->code : $request->auto_generated_code;
        $addProduct->category_id = $request->category_id;
        $addProduct->sub_category_id = $request->sub_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->alert_quantity = $request->alert_quantity ? $request->alert_quantity : 0;
        $addProduct->tax_ac_id = $request->tax_ac_id;
        $addProduct->tax_type = $request->tax_type ? $request->tax_type : 1;
        $addProduct->product_condition = $request->product_condition;
        $addProduct->is_show_in_ecom = $request->is_show_in_ecom;
        $addProduct->is_for_sale = $request->is_for_sale;
        $addProduct->has_batch_no_expire_date = $request->has_batch_no_expire_date;
        $addProduct->is_show_emi_on_pos = $request->is_show_emi_on_pos;
        $addProduct->is_manage_stock = $request->is_manage_stock;
        $addProduct->product_details = isset($request->product_details) ? $request->product_details : null;
        $addProduct->is_purchased = BooleanType::False->value;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->weight = isset($request->weight) ? $request->weight : null;
        $addProduct->has_multiple_unit = isset($request->has_multiple_unit) ? $request->has_multiple_unit : 0;

        if ($request->type == 1) {

            $addProduct->is_variant = $request->is_variant ? BooleanType::True->value : BooleanType::False->value;
            $addProduct->product_cost = $request->product_cost ? $request->product_cost : 0;
            $addProduct->profit = $request->profit ? $request->profit : 0;
            $addProduct->product_cost_with_tax = $request->product_cost_with_tax ? $request->product_cost_with_tax : 0;
            $addProduct->product_price = $request->product_price ? $request->product_price : 0;
        }

        if ($request->file('photo')) {

            $productThumbnailPhoto = $request->file('photo');
            $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();

            $dir = public_path('uploads/product/thumbnail/');

            if (!\File::isDirectory($dir)) {

                \File::makeDirectory($dir, 493, true);
            }

            Image::make($productThumbnailPhoto)->resize(600, 600)->save($dir . '/' . $productThumbnailName);
            $addProduct->thumbnail_photo = $productThumbnailName;
        }

        $addProduct->save();

        return $addProduct;
    }

    public function updateProduct(object $request, int $productId): object
    {
        $updateProduct = $this->singleProduct(id: $productId, with: ['productUnits', 'variants', 'variants.variantUnits', 'productAccessBranches']);

        foreach ($updateProduct->productUnits as $productUnit) {

            $productUnit->is_delete_in_update = IsDeleteInUpdate::Yes->value;
            $productUnit->save();
        }

        if (count($updateProduct->variants) > 0) {

            foreach ($updateProduct->variants as $variant) {

                $variant->is_delete_in_update = IsDeleteInUpdate::Yes->value;
                $variant->save();

                foreach ($variant->variantUnits as $variantUnit) {

                    $variantUnit->is_delete_in_update = IsDeleteInUpdate::Yes->value;
                    $variantUnit->save();
                }
            }
        }

        $updateProduct->type = $request->type;
        $updateProduct->name = $request->name;
        $updateProduct->product_code = $request->code ? $request->code : $request->current_product_code;
        $updateProduct->category_id = $request->category_id;
        $updateProduct->sub_category_id = $request->sub_category_id;
        $updateProduct->brand_id = $request->brand_id;
        $updateProduct->unit_id = $request->unit_id;
        $updateProduct->alert_quantity = $request->alert_quantity ? $request->alert_quantity : 0;
        $updateProduct->tax_ac_id = $request->tax_ac_id;
        $updateProduct->tax_type = $request->tax_type ? $request->tax_type : 1;
        $updateProduct->product_condition = $request->product_condition;
        $updateProduct->is_show_in_ecom = $request->is_show_in_ecom;
        $updateProduct->is_for_sale = $request->is_for_sale;
        $updateProduct->has_batch_no_expire_date = $request->has_batch_no_expire_date;
        $updateProduct->is_show_emi_on_pos = $request->is_show_emi_on_pos;
        $updateProduct->is_manage_stock = $request->is_manage_stock;
        $updateProduct->product_details = isset($request->product_details) ? $request->product_details : null;
        $updateProduct->is_purchased = BooleanType::False->value;
        $updateProduct->barcode_type = $request->barcode_type;
        $updateProduct->warranty_id = $request->warranty_id;
        $updateProduct->weight = isset($request->weight) ? $request->weight : null;
        $updateProduct->has_multiple_unit = $request->has_multiple_unit;

        if ($request->type == 1) {

            $updateProduct->is_variant = $request->is_variant ? BooleanType::True->value : BooleanType::False->value;
            $updateProduct->product_cost = $request->product_cost ? $request->product_cost : 0;
            $updateProduct->profit = $request->profit ? $request->profit : 0;
            $updateProduct->product_cost_with_tax = $request->product_cost_with_tax ? $request->product_cost_with_tax : 0;
            $updateProduct->product_price = $request->product_price ? $request->product_price : 0;
        }

        if ($request->file('photo')) {

            $dir = public_path('uploads/product/thumbnail/');

            if ($updateProduct->thumbnail_photo) {

                if (file_exists($dir . $updateProduct->thumbnail_photo)) {

                    unlink($dir . $updateProduct->thumbnail_photo);
                }
            }

            if (!\File::isDirectory($dir)) {

                \File::makeDirectory($dir, 493, true);
            }

            $productThumbnailPhoto = $request->file('photo');
            $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();
            Image::make($productThumbnailPhoto)->resize(600, 600)->save($dir . '/' . $productThumbnailName);
            $updateProduct->thumbnail_photo = $productThumbnailName;
        }

        $updateProduct->save();

        return $updateProduct;
    }

    public function updateProductAndVariantPrice(
        int $productId,
        ?int $variantId,
        float $unitCostWithDiscount,
        float $unitCostIncTax,
        float $profit,
        float $sellingPrice,
        string $isEditProductPrice,
        string $isLastEntry
    ) {
        $updateProduct = Product::where('id', $productId)->first();
        $updateProduct->is_purchased = BooleanType::True->value;

        if ($updateProduct->is_variant == BooleanType::False->value) {

            if ($isLastEntry == BooleanType::True->value) {

                $updateProduct->product_cost = $unitCostWithDiscount;
                $updateProduct->product_cost_with_tax = $unitCostIncTax;
            }

            if ($isEditProductPrice == '1') {

                $updateProduct->profit = $profit;
                $updateProduct->product_price = $sellingPrice;
            }
        }

        $updateProduct->save();

        if ($variantId != null) {

            $updateVariant = ProductVariant::where('id', $variantId)
                ->where('product_id', $productId)
                ->first();

            if ($isLastEntry == BooleanType::True->value) {

                $updateVariant->variant_cost = $unitCostWithDiscount;
                $updateVariant->variant_cost_with_tax = $unitCostIncTax;
            }

            if ($isEditProductPrice == '1') {

                $updateVariant->variant_profit = $profit;
                $updateVariant->variant_price = $sellingPrice;
            }

            $updateVariant->is_purchased = BooleanType::True->value;
            $updateVariant->save();
        }
    }

    public function changeProductStatus(int $id): array
    {
        $statusChange = $this->singleProduct(id: $id);

        if ($statusChange->status == BooleanType::True->value) {

            $statusChange->status = BooleanType::False->value;
            $statusChange->save();

            return ['msg' => __('Successfully Product is deactivated')];
        } else {

            $statusChange->status = BooleanType::True->value;
            $statusChange->save();

            return ['msg' => __('Successfully Product is activated')];
        }
    }

    public function deleteProduct(int $id): array|object
    {
        $deleteProduct = $this->singleProduct(id: $id, with: ['ledgerEntries', 'variants']);

        if (!is_null($deleteProduct)) {

            if (count($deleteProduct->ledgerEntries) > 0) {

                return ['pass' => false, 'msg' => __('Product can not be deleted. This product has one or more entries in the product ledger.')];
            }

            $dir = public_path('uploads/product/thumbnail/');
            if (isset($deleteProduct->thumbnail_photo) && file_exists($dir . $deleteProduct->thumbnail_photo)) {

                unlink($dir . $deleteProduct->thumbnail_photo);
            }

            if (count($deleteProduct->variants) > 0) {

                $variantImgDir = public_path('uploads/product/variant_image/');
                foreach ($deleteProduct->variants as $variant) {

                    if ($variant->variant_image) {

                        if (file_exists($variantImgDir . $variant->variant_image)) {

                            unlink($variantImgDir . $variant->variant_image);
                        }
                    }
                }
            }

            $deleteProduct->delete();
        }

        return $deleteProduct;
    }

    public function restrictions($request)
    {
        if ($request->is_variant == 1) {

            if ($request->variant_combinations == null) {

                return ['pass' => false, 'msg' => 'You have selected Has variant? = Yes, but there is no variant at all.'];
            }
        }

        if ($request->type == 2) {

            if ($request->product_ids == null) {

                return ['pass' => false, 'msg' => 'You have selected combo item but there is no item at all.'];
            }
        }

        return ['pass' => true];
    }

    public function productByAnyCondition(array $with): ?object
    {
        $query = Product::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function branchProducts(?int $branchId, bool $withVariant = false): ?object
    {
        if ($withVariant == false) {

            return DB::table('products')
                ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
                ->where('product_access_branches.branch_id', $branchId)
                ->select('products.*')
                ->get();
        } elseif ($withVariant == true) {

            return DB::table('products')
                ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
                ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
                ->where('product_access_branches.branch_id', $branchId)
                ->select(
                    'products.*',
                    'product_variants.id as variant_id',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'product_variants.variant_cost',
                    'product_variants.variant_cost_with_tax',
                    'product_variants.variant_price',
                )->get();
        }
    }

    public function singleProduct(int $id, array $with = null, array $firstWithSelect = null): ?object
    {
        $query = Product::query();

        if (isset($with)) {

            $query->with($with);
        }

        return isset($firstWithSelect) ? $query->where('id', $id)->first($firstWithSelect) : $query->where('id', $id)->first();
    }

    public function productUpdateValidation(object $request, int $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'code' => 'sometimes|unique:products,product_code,' . $id,
                'unit_id' => 'required',
                'photo' => 'sometimes|image|max:2048',
            ],
            [
                'unit_id.required' => __('Product unit field is required.'),
            ]
        );

        if ($request->is_variant == BooleanType::True->value) {

            $request->validate(

                ['variant_image.*' => 'sometimes|image|max:2048'],
            );
        }
    }

    public function getLastProductSerialCode(): string
    {
        $id = 1;
        $lastEntry = DB::table('products')->orderBy('id', 'desc')->first(['id']);

        if ($lastEntry) {

            $id = ++$lastEntry->id;
        }

        return str_pad($id, 4, '0', STR_PAD_LEFT);
    }
}
