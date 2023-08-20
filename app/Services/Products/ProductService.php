<?php

namespace App\Services\Products;

use App\Models\ProductImage;
use App\Models\Products\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ProductService
{
    public function productListTable($request)
    {
        // $generalSettings = config('generalSettings');
        $generalSettings = config('generalSettings');
        $countPriceGroup = DB::table('price_groups')->where('status', 'Active')->count();
        $img_url = asset('uploads/product/thumbnail');
        $products = '';

        $query = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id')
            ->leftJoin('accounts', 'products.tax_ac_id', 'accounts.id')
            ->leftJoin('brands', 'products.brand_id', 'brands.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->where('product_branches.status', 1);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('product_branches.branch_id', null);
            } else {

                $query->where('product_branches.branch_id', $request->branch_id);
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

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
        } else {

            $query->where('product_branches.branch_id', auth()->user()->branch_id);
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
                'units.name as unit_name',
                'taxes.tax_name',
                'categories.name as cate_name',
                'sub_cate.name as sub_cate_name',
                'brands.name as brand_name',
            ]
        )->distinct('product_branches.branch_id')->orderBy('id', 'desc');

        return DataTables::of($products)
            ->addColumn('multiple_delete', function ($row) {

                return '<input id="'.$row->id.'" class="data_id sorting_disabled" type="checkbox" name="data_ids[]" value="'.$row->id.'"/>';
            })->editColumn('photo', function ($row) use ($img_url) {

                return '<img loading="lazy" class="rounded" style="height:40px; width:40px; padding:2px 0px;" src="'.$img_url.'/'.$row->thumbnail_photo.'">';
            })->addColumn('action', function ($row) use ($countPriceGroup) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="'.route('products.view', [$row->id]).'"><i class="far fa-eye text-primary"></i> View</a>';
                $html .= '<a class="dropdown-item" id="check_pur_and_gan_bar_button" href="'.route('products.check.purchase.and.generate.barcode', [$row->id]).'"><i class="fas fa-barcode text-primary"></i> Barcode</a>';

                if (auth()->user()->can('product_edit')) {

                    $html .= '<a class="dropdown-item" href="'.route('products.edit', [$row->id]).'"><i class="far fa-edit text-primary"></i> Edit</a>';
                }

                if (auth()->user()->can('product_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('products.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                }

                // if ($row->status == 1) {

                //     $html .= '<a class="dropdown-item" id="change_status" href="' . route('products.change.status', [$row->id]) . '"><i class="far fa-thumbs-up text-success"></i> Change Status</a>';
                // } else {

                //     $html .= '<a class="dropdown-item" id="change_status" href="' . route('products.change.status', [$row->id]) . '"><i class="far fa-thumbs-down text-danger"></i> Change Status</a>';
                // }

                if (auth()->user()->can('openingStock_add')) {

                    $html .= '<a class="dropdown-item" id="opening_stock" href="'.route('products.opening.stock', [$row->id]).'"><i class="fas fa-database text-primary"></i> Add or edit opening stock</a>';
                }

                if ($countPriceGroup > 0) {

                    $html .= '<a class="dropdown-item" href="'.route('products.add.price.groups', [$row->id, $row->is_variant]).'"><i class="far fa-money-bill-alt text-primary"></i> Add or edit price group</a>';
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
            })
            ->editColumn('cate_name', fn ($row) => '<p class="p-0">'.($row->cate_name ? $row->cate_name : '...').'</p><p class="p-0">'.($row->sub_cate_name ? ' --- '.$row->sub_cate_name : '').'</p>')

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {
                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input"  id="change_status" data-url="'.route('products.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked />';
                    $html .= '</div>';

                    return $html;
                } else {
                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input" id="change_status" data-url="'.route('products.change.status', [$row->id]).'" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    $html .= '</div>';

                    return $html;
                }
            })
            ->editColumn('access_locations', function ($row) use ($generalSettings, $request) {

                $productBranches = '';
                $query = DB::table('product_branches')->leftJoin('branches', 'product_branches.branch_id', 'branches.id')->where('product_branches.product_id', $row->id);

                if ($request->branch_id) {

                    if ($request->branch_id == 'NULL') {

                        $query->where('product_branches.branch_id', null);
                    } else {

                        $query->where('product_branches.branch_id', $request->branch_id);
                    }
                }

                if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                    $productBranches = $query->select('branches.name as b_name')->orderBy('product_branches.branch_id', 'asc')->get();
                } else {

                    $productBranches = $query->where('product_branches.branch_id', auth()->user()->branch_id)->select('branches.name as b_name')->orderBy('product_branches.branch_id', 'asc')->get();
                }

                $text = '';
                foreach ($productBranches as $productBranch) {

                    $text .= '<p class="m-0 p-0">'.($productBranch->b_name != null ? $productBranch->b_name : $generalSettings['business__shop_name']).',</p>';
                }

                return $text;
            })
            ->editColumn('quantity', function ($row) {

                // $quantity = $productStock->branchWiseSingleProductStock($row->id, $request->branch_id);

                return \App\Utils\Converter::format_in_bdt(0).'/'.$row->unit_name;
            })
            ->editColumn('brand_name', fn ($row) => $row->brand_name ? $row->brand_name : '...')
            ->editColumn('tax_name', fn ($row) => $row->tax_name ? $row->tax_name : '...')
            ->rawColumns(['multiple_delete', 'photo', 'quantity', 'action', 'name', 'type', 'cate_name', 'status', 'expire_date', 'tax_name', 'brand_name', 'access_locations'])
            ->smart(true)->make(true);
    }

    public function createProductListOfProducts()
    {
        $products = DB::table('product_branches')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->select('products.id', 'products.name', 'products.product_cost', 'products.product_price')
            ->where('product_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('products.id', 'desc');

        return DataTables::of($products)
            ->addColumn('action', fn ($row) => '<a href="'.route('products.edit', [$row->id]).'" class="action-btn c-edit" title="Edit"><span class="fas fa-edit"></span></a>')
            ->editColumn('name', fn ($row) => '<span title="'.$row->name.'">'.Str::limit($row->name, 25).'</span>')
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
        $addProduct->alert_quantity = $request->alert_quantity;
        // $addProduct->tax_ac_id = $request->tax_ac_id;
        $addProduct->tax_type = $request->tax_type;
        $addProduct->product_condition = $request->product_condition;
        $addProduct->is_show_in_ecom = $request->is_show_in_ecom;
        $addProduct->is_for_sale = $request->is_for_sale;
        $addProduct->is_show_emi_on_pos = $request->is_show_emi_on_pos;
        $addProduct->is_manage_stock = $request->is_manage_stock;
        $addProduct->product_details = isset($request->product_details) ? $request->product_details : null;
        $addProduct->is_purchased = 0;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->weight = isset($request->weight) ? $request->weight : null;

        if ($request->type == 1) {

            $addProduct->is_variant = $request->is_variant ? 1 : 0;
            $addProduct->product_cost = $request->product_cost ? $request->product_cost : 0;
            $addProduct->profit = $request->profit ? $request->profit : 0;
            $addProduct->product_cost_with_tax = $request->product_cost_with_tax ? $request->product_cost_with_tax : 0;
            $addProduct->product_price = $request->product_price ? $request->product_price : 0;
        }

        if ($request->file('photo')) {

            $productThumbnailPhoto = $request->file('photo');
            $productThumbnailName = uniqid().'.'.$productThumbnailPhoto->getClientOriginalExtension();

            $path = public_path('uploads/product/thumbnail');

            if (! file_exists($path)) {

                mkdir($path);
            }

            Image::make($productThumbnailPhoto)->resize(600, 600)->save($path.'/'.$productThumbnailName);
            $addProduct->thumbnail_photo = $productThumbnailName;
        }

        $addProduct->save();

        if ($request->file('image')) {

            if (count($request->file('image')) > 0) {

                foreach ($request->file('image') as $image) {

                    $productImage = $image;
                    $productImageName = uniqid().'.'.$productImage->getClientOriginalExtension();
                    Image::make($productImage)->resize(600, 600)->save('uploads/product/'.$productImageName);
                    $addProductImage = new ProductImage();
                    $addProductImage->product_id = $addProduct->id;
                    $addProductImage->image = $productImageName;
                    $addProductImage->save();
                }
            }
        }

        // if ($request->type == 2) {

        //     $addProduct->is_combo = 1;
        //     $addProduct->profit = $request->profit ? $request->profit : 0.00;
        //     $addProduct->combo_price = $request->combo_price;
        //     $addProduct->product_price = $request->combo_price;
        //     $addProduct->save();

        //     $productIds = $request->product_ids;
        //     $combo_quantities = $request->combo_quantities;
        //     $productVariantIds = $request->variant_ids;
        //     $index = 0;

        //     foreach ($productIds as $id) {

        //         $addComboProducts = new ComboProduct();
        //         $addComboProducts->product_id = $addProduct->id;
        //         $addComboProducts->combo_product_id = $id;
        //         $addComboProducts->quantity = $combo_quantities[$index];
        //         $addComboProducts->product_variant_id = $productVariantIds[$index] !== 'noid' ? $productVariantIds[$index] : null;
        //         $addComboProducts->save();

        //         $index++;
        //     }
        // }

        return $addProduct;
    }

    public function restrictions($request)
    {
        if ($request->is_variant == 1) {

            if ($request->variant_combinations == null) {

                return ['pass' => false, 'msg' => 'You have selected Has variant? = Yes but there is no variant at all.'];
            }
        }

        if ($request->type == 2) {

            if ($request->product_ids == null) {

                return ['pass' => false, 'msg' => 'You have selected combo item but there is no item at all.'];
            }
        }

        return ['pass' => true];
    }
}
