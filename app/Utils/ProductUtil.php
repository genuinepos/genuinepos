<?php

namespace App\Utils;

use App\Models\Unit;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Warranty;
use App\Utils\PurchaseUtil;
use App\Models\PurchaseProduct;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOpeningStock;

class ProductUtil
{
    public $purchaseUtil;
    public function __construct(PurchaseUtil $purchaseUtil)
    {
        $this->purchaseUtil = $purchaseUtil;
    }

    public function productListTable($request)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $countPriceGroup = DB::table('price_groups')->where('status', 'Active')->count();
        $img_url = asset('public/uploads/product/thumbnail');
        $products = '';
        $query = DB::table('products')->join('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id');

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

        if ($request->unit_id) {
            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_id) {
            $query->where('products.tax_id', $request->tax_id);
        }

        if ($request->brand_id) {
            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->status != '') {
            $query->where('products.status', $request->status);
        }

        // if ($request->is_for_sale) {
        //     $query->where('products.is_for_sale', '0');
        // }

        $products = $query->select(
            [
                'products.*',
                'units.name as unit_name',
                'taxes.tax_name',
                'categories.name as cate_name',
                'sub_cate.name as sub_cate_name',
                'brands.name as brand_name',
            ]
        )->orderBy('id', 'desc');

        return DataTables::of($products)
            ->addColumn('multiple_delete', function ($row) {
                return '<input id="' . $row->id . '" class="data_id sorting_disabled" type="checkbox" name="data_ids[]" value="' . $row->id . '"/>';
            })->editColumn('photo', function ($row) use ($img_url) {
                return '<img loading="lazy" class="rounded" style="height:40px; width:40px; padding:2px 0px;" src="' . $img_url . '/' . $row->thumbnail_photo . '">';
            })->addColumn('action', function ($row) use ($countPriceGroup) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('products.view', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';
                $html .= '<a class="dropdown-item" id="check_pur_and_gan_bar_button" href="' . route('products.check.purchase.and.generate.barcode', [$row->id]) . '"><i class="fas fa-barcode text-primary"></i> Barcode</a>';
                if (auth()->user()->permission->product['product_edit']  == '1') {
                    $html .= '<a class="dropdown-item" href="' . route('products.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                }

                if (auth()->user()->permission->product['product_delete']  == '1') {
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('products.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                }

                if ($row->status == 1) {
                    $html .= '<a class="dropdown-item" id="change_status" href="' . route('products.change.status', [$row->id]) . '"><i class="far fa-thumbs-up text-success"></i> Change Status</a>';
                } else {
                    $html .= '<a class="dropdown-item" id="change_status" href="' . route('products.change.status', [$row->id]) . '"><i class="far fa-thumbs-down text-danger"></i> Change Status</a>';
                }

                if (auth()->user()->permission->product['openingStock_add']  == '1') {
                    $html .= '<a class="dropdown-item" id="opening_stock" href="' . route('products.opening.stock', [$row->id]) . '"><i class="fas fa-database text-primary"></i> Add or edit opening stock</a>';
                }

                if ($countPriceGroup > 0) {
                    $html .= '<a class="dropdown-item" href="' . route('products.add.price.groups', [$row->id, $row->is_variant]) . '"><i class="far fa-money-bill-alt text-primary"></i> Add or edit price group</a>';
                }

                $html .= ' </div>';
                $html .= '</div>';
                return $html;
            })->editColumn('name', function ($row) {
                $html = '';
                $html .= $row->name;
                $html .= $row->is_manage_stock == 0 ? ' <span class="badge bg-primary pt-1"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '';
                return $html;
            })->editColumn('type', function ($row) {
                if ($row->type == 1 && $row->is_variant == 1) {
                    return '<span class="text-primary">Variant</span>';
                } elseif ($row->type == 1 && $row->is_variant == 0) {
                    return '<span class="text-success">Single</span>';
                } elseif ($row->type == 2) {
                    return '<span class="text-info">Combo</span>';
                } elseif ($row->type == 3) {
                    return '<span class="text-info">Digital</span>';
                }
            })->editColumn('cate_name', function ($row) {
                return '<span>' . ($row->cate_name ? $row->cate_name : '...') . ($row->sub_cate_name ? '<br>--' . $row->sub_cate_name : '') . '</span>';
            })->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="text-success">Active</span>';
                } else {
                    return '<span class="text-danger">Inactive</span>';
                }
            })->editColumn('brand_name', function ($row) {
                return $row->brand_name ? $row->brand_name : '...';
            })->editColumn('tax_name', function ($row) {
                return $row->tax_name ? $row->tax_name : '...';
            })->editColumn('expire_date', function ($row) use ($generalSettings) {
                return $row->expire_date ? date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->expire_date)) : '...';
            })->rawColumns([
                'multiple_delete',
                'photo',
                'action',
                'name',
                'type',
                'cate_name',
                'status',
                'expire_date',
                'tax_name',
                'brand_name',
            ])->smart(true)->make(true);
    }

    public function addQuickCategory($request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $addQuickCategory = new Category();
        $addQuickCategory->name = $request->name;
        $addQuickCategory->save();
        return response()->json($addQuickCategory);
    }

    public function addQuickBrand($request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $addBrand = new Brand();
        $addBrand->name = $request->name;
        $addBrand->save();

        return response()->json($addBrand);
    }

    public function addQuickUnit($request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        $addUnit = new Unit();
        $addUnit->name = $request->name;
        $addUnit->code_name = $request->code;
        $addUnit->save();
        return response()->json($addUnit);
    }

    public function addQuickWarranty($request)
    {
        $request->validate([
            'name' => 'required',
            'duration' => 'required',
        ]);

        $add = new Warranty();
        $add->name = $request->name;
        $add->type = $request->type;
        $add->duration = $request->duration;
        $add->duration_type = $request->duration_type;
        $add->description = $request->description;
        $add->save();
        return response()->json($add);
    }

    // Add opening stock method
    public function addOpeningStock($branch_id, $product_id, $variant_id, $unit_cost_inc_tax, $quantity, $subtotal)
    {
        $addOpeningStock = new ProductOpeningStock();
        $addOpeningStock->branch_id = $branch_id;
        $addOpeningStock->product_id = $product_id;
        $addOpeningStock->product_variant_id = $variant_id;
        $addOpeningStock->unit_cost_inc_tax = $unit_cost_inc_tax;
        $addOpeningStock->quantity = $quantity;
        $addOpeningStock->subtotal = $subtotal;
        $addOpeningStock->save();

        $addRowInPurchaseProductTable = new PurchaseProduct();
        $addRowInPurchaseProductTable->opening_stock_id = $addOpeningStock->id;
        $addRowInPurchaseProductTable->product_id = $product_id;
        $addRowInPurchaseProductTable->product_variant_id = $variant_id;
        $addRowInPurchaseProductTable->net_unit_cost = $unit_cost_inc_tax;
        $addRowInPurchaseProductTable->quantity = $quantity;
        $addRowInPurchaseProductTable->left_qty = $quantity;
        $addRowInPurchaseProductTable->line_total = $subtotal;
        $addRowInPurchaseProductTable->left_qty = $quantity;
        $addRowInPurchaseProductTable->created_at = date('Y-m-d H:i:s');
        $addRowInPurchaseProductTable->branch_id = auth()->user()->branch_id;
        $addRowInPurchaseProductTable->save();
    }

    // Update opening stock method
    public function updateOpeningStock($openingStock, $unit_cost_inc_tax, $quantity, $subtotal)
    {
        $openingStock->unit_cost_inc_tax = $unit_cost_inc_tax;
        $openingStock->quantity = $quantity;
        $openingStock->subtotal = $subtotal;
        $openingStock->save();

        $purchaseProduct = PurchaseProduct::where('opening_stock_id', $openingStock->id)->first();
        if ($purchaseProduct) {
            $purchaseProduct->net_unit_cost = $unit_cost_inc_tax;
            $purchaseProduct->quantity = $quantity;
            $purchaseProduct->line_total = $subtotal;
            $purchaseProduct->save();
            $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
        } else {
            $addRowInPurchaseProductTable = new PurchaseProduct();
            $addRowInPurchaseProductTable->opening_stock_id = $openingStock->id;
            $addRowInPurchaseProductTable->product_id = $openingStock->product_id;
            $addRowInPurchaseProductTable->product_variant_id = $openingStock->product_variant_id;
            $addRowInPurchaseProductTable->net_unit_cost = $unit_cost_inc_tax;
            $addRowInPurchaseProductTable->quantity = $quantity;
            $addRowInPurchaseProductTable->left_qty = $quantity;
            $addRowInPurchaseProductTable->line_total = $subtotal;
            $addRowInPurchaseProductTable->created_at = date('Y-m-d H:i:s');
            $addRowInPurchaseProductTable->save();
        }
    }
}
