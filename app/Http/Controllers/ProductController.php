<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warranty;
use App\Models\BulkVariant;
use App\Models\ComboProduct;
use App\Models\PriceGroupProduct;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\SupplierProduct;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOpeningStock;
use App\Models\ProductBranchVariant;
use Intervention\Image\Facades\Image;
use App\Models\ProductWarehouseVariant;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // index view
    public function allProduct(Request $request)
    {
        if (auth()->user()->permission->product['product_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        //return $request->status;
        if ($request->ajax()) {
            $priceGroups = DB::table('price_groups')->where('status', 'Active')->get();
            $img_url = asset('public/uploads/product/thumbnail');
            $products = '';
            $query = DB::table('products')->join('units', 'products.unit_id', 'units.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id')
                ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
                ->leftJoin('brands', 'products.brand_id', 'brands.id');
            //return $request->all();

            if ($request->product_type == 1) {
                $query->where('products.type', 1)->where('products.is_variant', 0);
            }

            if ($request->product_type == 2) {
                $query->where('products.is_variant', 1)->where('products.type', 1);
            }

            if ($request->product_type == 3) {
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

            $products = $query->select(
                [
                    'products.*',
                    'units.name as unit_name',
                    'taxes.tax_name',
                    'categories.name as cate_name',
                    'sub_cate.name as sub_cate_name',
                    'brands.name as brand_name',
                ]
            )->orderBy('id', 'desc')->get();

            return DataTables::of($products)
                ->addColumn('multiple_delete', function ($row) {
                    return '<input id="' . $row->id . '" class="data_id sorting_disabled" type="checkbox" name="data_ids[]" value="' . $row->id . '"/>';
                })
                ->editColumn('photo', function ($row) use ($img_url) {
                    return '<img loading="lazy" class="rounded" style="height:40px; width:40px;" src="' . $img_url . '/' . $row->thumbnail_photo . '">';
                })
                ->addColumn('action', function ($row) use ($priceGroups) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    $html .= '<a class="dropdown-item" id="check_pur_and_gan_bar_button" href="' . route('products.check.purchase.and.generate.barcode', [$row->id]) . '"><i class="fas fa-barcode mr-1 text-primary"></i> Barcode</a>';

                    $html .= '<a class="dropdown-item details_button" href="#"><i class="far fa-eye text-primary"></i> View</a>';

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

                    if (count($priceGroups) > 0) {
                        $html .= '<a class="dropdown-item" href="' . route('products.add.price.groups', [$row->id, $row->is_variant]) . '"><i class="far fa-money-bill-alt text-primary"></i> Add or edit price group</a>';
                    }

                    $html .= ' </div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('type', function ($row) {
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
                ->editColumn('category', function ($row) {
                    return '<span>' . ($row->cate_name ? $row->cate_name : '...') . ($row->sub_cate_name ? '<br>--' . $row->sub_cate_name : '') . '</span>';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="text-success">Active</span>';
                    } else {
                        return '<span class="text-danger">Inactive</span>';
                    }
                })
                ->editColumn('brand_name', function ($row) {
                    return $row->brand_name ? $row->brand_name : '...';
                })
                ->editColumn('tax_name', function ($row) {
                    return $row->tax_name ? $row->tax_name : '...';
                })
                ->editColumn('expire_date', function ($row) {
                    return $row->expire_date ? date('d/m/Y', strtotime($row->expire_date)) : '...';
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('products.view', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns([
                    'multiple_delete',
                    'photo', 'action',
                    'type', 'category',
                    'status',
                    'expire_date',
                    'tax_name',
                    'brand_name',
                ])->make(true);
        }
        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
        $brands = DB::table('brands')->get(['id', 'name']);
        $units = DB::table('units')->get(['id', 'name', 'code_name']);
        $taxes = DB::table('taxes')->get(['id', 'tax_name']);
        return view('product.products.index_v2', compact('categories', 'brands', 'units', 'taxes'));
    }

    // Add product view
    public function create()
    {
        if (auth()->user()->permission->product['product_add'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        $units = DB::table('units')->get(['id', 'name', 'code_name']);
        $categories = DB::table('categories')->where('parent_category_id', NULL)->orderBy('id', 'desc')->get(['id', 'name']);
        $brands = DB::table('brands')->orderBy('id', 'desc')->get(['id', 'name']);
        $warranties = DB::table('warranties')->orderBy('id', 'desc')->get(['id', 'name']);
        $taxes = DB::table('taxes')->get(['id', 'tax_name', 'tax_percent']);
        return view('product.products.create_v2', compact('units', 'categories', 'brands', 'warranties', 'taxes'));
    }

    public function store(Request $request)
    {
        ///return $request->all();
        $addProduct = new Product();
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $this->validate(
            $request,
            [
                'name' => 'required',
                'code' => 'required|min:3|max:40|unique:products,product_code',
                'unit_id' => 'required',
                'photo' => 'sometimes|image|max:2048',
                'image.*' => 'sometimes|image|max:2048',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
                'code.required' => 'Product code field is required.',
            ]
        );

        $addProduct->type = $request->type;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->code;
        $addProduct->category_id = $request->category_id;
        $addProduct->parent_category_id = $request->child_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->alert_quantity = $request->alert_quantity;
        $addProduct->tax_id = $tax_id;
        $addProduct->tax_type = $request->tax_type;
        $addProduct->expire_date = $request->expired_date ? $request->expired_date : NULL;
        $addProduct->product_condition = $request->product_condition;
        $addProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $addProduct->is_for_sale = isset($request->is_not_for_sale) ? 0 : 1;
        $addProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $addProduct->product_details = $request->product_details;
        $addProduct->is_purchased = 0;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->weight = $request->weight;
        $addProduct->custom_field_1 = $request->custom_field_1;
        $addProduct->custom_field_2 = $request->custom_field_2;
        $addProduct->custom_field_3 = $request->custom_field_3;

        if ($request->file('image')) {
            if (count($request->file('image')) > 2) {
                return response()->json(['errorMsg' => 'You can upload only 2 product images.']);
            }
        }

        if ($request->file('image')) {
            if (count($request->file('image')) > 0) {
                foreach ($request->file('image') as $image) {
                    $productImage = $image;
                    $productImageName = uniqid() . '.' . $productImage->getClientOriginalExtension();
                    Image::make($productImage)->resize(600, 600)->save('public/uploads/product/' . $productImageName);
                    $addProductImage = new ProductImage();
                    $addProductImage->product_id = $addProduct->id;
                    $addProductImage->image = $productImageName;
                    $addProductImage->save();
                }
            }
        }

        if ($request->type == 1) {
            $this->validate(
                $request,
                [
                    'product_price' => 'required',
                    'product_cost' => 'required',
                    'product_cost_with_tax' => 'required',
                ],

            );
            $addProduct->product_cost = $request->product_cost;
            $addProduct->profit = $request->profit ? $request->profit : 0.00;
            $addProduct->product_cost_with_tax = $request->product_cost_with_tax;
            $addProduct->product_price = $request->product_price;

            if ($request->file('photo')) {
                $productThumbnailPhoto = $request->file('photo');
                $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();
                Image::make($productThumbnailPhoto)->resize(600, 600)->save('public/uploads/product/thumbnail/' . $productThumbnailName);
                $addProduct->thumbnail_photo = $productThumbnailName;
            }

            if (isset($request->is_variant)) {
                $addProduct->is_variant = 1;
                if ($request->variant_combinations == null) {
                    return response()->json(['errorMsg' => 'You have selected variant option but there is no variant at all.']);
                }

                $this->validate(
                    $request,
                    [
                        'variant_image.*' => 'sometimes|image|max:2048',
                    ],
                );

                $addProduct->save();

                $variant_combinations = $request->variant_combinations;
                $variant_codes = $request->variant_codes;
                $variant_costings = $request->variant_costings;
                $variant_costings_with_tax = $request->variant_costings_with_tax;
                $variant_profits = $request->variant_profits;
                $variant_prices_exc_tax = $request->variant_prices_exc_tax;
                $variant_images = $request->variant_image;
                $index = 0;
                foreach ($variant_combinations as $value) {
                    $addVariant = new ProductVariant();
                    $addVariant->product_id = $addProduct->id;
                    $addVariant->variant_name = $value;
                    $addVariant->variant_code = $variant_codes[$index];
                    $addVariant->variant_cost = $variant_costings[$index];
                    $addVariant->variant_cost_with_tax = $variant_costings_with_tax[$index];
                    $addVariant->variant_profit = $variant_profits[$index];
                    $addVariant->variant_price = $variant_prices_exc_tax[$index];

                    if (isset($variant_images[$index])) {
                        $variantImage = $variant_images[$index];
                        $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                        Image::make($variantImage)->resize(250, 250)->save('public/uploads/product/variant_image/' . $variantImageName);
                        $addVariant->variant_image = $variantImageName;
                    }

                    $index++;
                    $addVariant->save();
                }
            } else {
                $addProduct->save();
            }
        }

        if ($request->type == 2) {
            if ($request->product_ids == null) {
                return response()->json(['errorMsg' => 'You have selected combo product but there is no product at all']);
            }

            $addProduct->is_combo = 1;
            $addProduct->profit = $request->profit ? $request->profit : 0.00;
            $addProduct->combo_price = $request->combo_price;
            $addProduct->product_price = $request->combo_price;
            $addProduct->save();

            $productIds = $request->product_ids;
            $combo_quantities = $request->combo_quantities;
            $productVariantIds = $request->variant_ids;
            $index = 0;
            foreach ($productIds as $id) {
                $addComboProducts = new ComboProduct();
                $addComboProducts->product_id = $addProduct->id;
                $addComboProducts->combo_product_id = $id;
                $addComboProducts->quantity = $combo_quantities[$index];
                $addComboProducts->product_variant_id = $productVariantIds[$index] !== 'noid' ? $productVariantIds[$index] : NULL;
                $index++;
                $addComboProducts->save();
            }
        }

        session()->flash('successMsg', 'Product create Successfully');
        return response()->json('Product create Successfully');
    }

    public function view($productId)
    {
        $product = Product::with([
            'category',
            'child_category',
            'tax',
            'unit',
            'brand',
            'product_variants',
            'product_warehouses',
            'product_warehouses.warehouse',
            'product_warehouses.warehouse.branch',
            'product_warehouses.product_warehouse_variants',
            'product_warehouses.product_warehouse_variants.product_variant',
            'product_branches',
            'product_branches.branch',
            'product_branches.product_branch_variants',
            'product_branches.product_branch_variants.product_variant',
        ])->where('id', $productId)->first();

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);
        return view('product.products.ajax_view.product_details_view', compact('product', 'price_groups'));
    }

    //update opening stock
    public function openingStockUpdate(Request $request)
    {
       //return $request->all();
        $branch_id = auth()->user()->branch_id;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;
        $subtotals = $request->subtotals;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;

        // Add Opening Stock and update branch stock
        $index = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $openingStock = ProductOpeningStock::where('branch_id', $branch_id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($openingStock) {
                $product = Product::where('id', $openingStock->product_id)->first();
                $product->quantity -= $openingStock->quantity;
                $product->save();
                
                if ($openingStock->product_variant_id) {
                    $productVariant = ProductVariant::where('id', $variant_id)->first();
                    $productVariant->variant_quantity -= $openingStock->quantity;
                    $productVariant->save();
                }

                if ($branch_id) {
                    $productBranch = ProductBranch::where('branch_id', $branch_id)
                    ->where('product_id', $openingStock->product_id)
                    ->first();
                    $productBranch->product_quantity -= $openingStock->quantity;
                    $productBranch->save();

                    if ($openingStock->product_variant_id) {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                        ->where('product_id', $openingStock->product_id)
                        ->where('product_variant_id', $openingStock->product_variant_id)->first();
                        $productBranchVariant->variant_quantity -= $openingStock->quantity;
                    }
                } else {
                    $mbProduct = Product::where('id', $product_id)->first();
                    $mbProduct->mb_stock -= $openingStock->quantity;
                    $mbProduct->save();

                    if ($openingStock->product_variant_id) {
                        $mbProductVariant = ProductVariant::where('id', $openingStock->product_variant_id)->first();
                        $mbProductVariant->mb_stock -= $openingStock->quantity;
                        $mbProductVariant->save();
                    }
                }

                $openingStock->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                $openingStock->quantity = $quantities[$index];
                $openingStock->subtotal = $subtotals[$index];
                $openingStock->save();
            } else {
                $addOpeningStock = new ProductOpeningStock();
                $addOpeningStock->branch_id = $branch_id;
                $addOpeningStock->product_id = $product_id;
                $addOpeningStock->product_variant_id = $variant_id;
                $addOpeningStock->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                $addOpeningStock->quantity = $quantities[$index];
                $addOpeningStock->subtotal = $subtotals[$index];
                $addOpeningStock->save();
            }

            $product = Product::where('id', $product_id)->first();
            $product->quantity += (float)$quantities[$index];
            $product->save();

            if ($variant_ids[$index] != 'noid') {
                $productVariant = ProductVariant::where('id', $variant_id)->first();
                $productVariant->variant_quantity += (float)$quantities[$index];
                $productVariant->save();
            }

            if ($branch_id) {
                // update branch product qty
                $productBranch = ProductBranch::where('branch_id', $branch_id)
                    ->where('product_id', $product_id)
                    ->first();
                
                if ($productBranch) {
                    $productBranch->product_quantity += (float)$quantities[$index];
                    $productBranch->save();
                    if ($variant_ids[$index] != 'noid') {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                            ->where('product_id', $product_id)
                            ->where('product_variant_id', $variant_id)->first();

                        if ($productBranchVariant) {
                            $productBranchVariant->variant_quantity += (float)$quantities[$index];
                            $productBranchVariant->save();
                        } else {
                            $addProductBranchVariant = new ProductBranchVariant();
                            $addProductBranchVariant->product_branch_id = $productBranch->id;
                            $addProductBranchVariant->product_id = $product_id;
                            $addProductBranchVariant->product_variant_id = $variant_id;
                            $addProductBranchVariant->variant_quantity = $quantities[$index];
                            $addProductBranchVariant->save();
                        }
                    }
                } else {
                    $addBranchProduct = new ProductBranch();
                    $addBranchProduct->branch_id = $branch_id;
                    $addBranchProduct->product_id = $product_id;
                    $addBranchProduct->product_quantity = $quantities[$index];
                    $addBranchProduct->save();

                    if ($variant_ids[$index] != 'noid') {
                        $addProductBranchVariant = new ProductBranchVariant();
                        $addProductBranchVariant->product_branch_id = $addBranchProduct->id;
                        $addProductBranchVariant->product_id = $product_id;
                        $addProductBranchVariant->product_variant_id = $variant_id;
                        $addProductBranchVariant->variant_quantity = $quantities[$index];
                        $addProductBranchVariant->save();
                    }
                }
            } else {
                $mbProduct = Product::where('id', $product_id)->first();
                $mbProduct->mb_stock += $quantities[$index];
                $mbProduct->save();

                if ($variant_ids[$index] != 'noid') {
                    $mbProductVariant = ProductVariant::where('id', $variant_id)->first();
                    $mbProductVariant->mb_stock += (float)$quantities[$index];
                    $mbProductVariant->save();
                }
            }

            $index++;
        }

        return response()->json('Successfully product opening stock is added');
    }

    // Get opening stock
    public function openingStock($productId)
    {
        $products = DB::table('products')->where('products.id', $productId)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->select(
                'products.id as p_id',
                'products.name as p_name',
                'products.product_cost as p_cost',
                'products.product_cost_with_tax as p_cost_inc_tax',
                'units.code_name as u_code',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_cost as v_cost',
                'product_variants.variant_cost_with_tax as v_cost_inc_tax',
            )
            ->get();
        return view('product.products.ajax_view.opening_stock_modal_view', compact('products'));
    }

    public function addPriceGroup($productId, $type)
    {
        $priceGroups = DB::table('price_groups')->where('status', 'Active')->get();
        $product_name = DB::table('products')->where('id', $productId)->first(['name', 'product_code']);
        $products = DB::table('products')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->where('products.id', $productId)
            ->select(
                'products.id as p_id',
                'products.is_variant',
                'products.name',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_price',
                'product_variants.id as v_id',
                'taxes.tax_percent'
            )->get();
        return view('product.products.add_price_group', compact('products', 'type', 'priceGroups', 'product_name'));
    }

    public function savePriceGroup(Request $request)
    {
        //return $request->all();
        $variant_ids = $request->variant_ids;
        $index = 0;
        foreach ($request->product_ids as $product_id) {
            foreach ($request->group_prices as $key => $group_price) {
                // echo '<pre>';
                // echo 'group_id='.$key.', product='.$group_price[$product_id][$variant_ids[$index]];
                (float)$__group_price = $group_price[$product_id][$variant_ids[$index]];
                $__variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $updatePriceGroup = PriceGroupProduct::where('price_group_id', $key)->where('product_id', $product_id)->where('variant_id', $__variant_id)->first();
                if ($updatePriceGroup) {
                    $updatePriceGroup->price = $__group_price != null ? $__group_price : NULL;
                    $updatePriceGroup->save();
                } else {
                    $addPriceGroup = new PriceGroupProduct();
                    $addPriceGroup->price_group_id = $key;
                    $addPriceGroup->product_id = $product_id;
                    $addPriceGroup->variant_id = $__variant_id;
                    $addPriceGroup->price = $__group_price != null ? $__group_price : NULL;
                    $addPriceGroup->save();
                }
            }
            $index++;
        }

        if ($request->action_type == 'save') {
            return response()->json(['saveMessage' =>  'Product price group updated Successfully']);
        } else {
            return response()->json(['saveAndAnotherMsg' =>  'Product price group updated Successfully']);
        }
    }

    // edit view of product
    public function edit($productId)
    {
        $product = DB::table('products')->where('products.id', $productId)
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->select('products.*', 'taxes.tax_percent')
            ->first();
        $categories = DB::table('categories')->get();
        $units = DB::table('units')->get();
        $brands = DB::table('brands')->get();
        $taxes = DB::table('taxes')->get();
        $warrantities = DB::table('warranties')->get();
        return view('product.products.edit_v2', compact('product', 'categories', 'units', 'brands', 'taxes', 'warrantities'));
    }

    // Get product variants 
    public function getProductVariants($productId)
    {
        $variants = DB::table('product_variants')->where('product_id', $productId)->get();
        return response()->json($variants);
    }

    public function getComboProducts($productId)
    {
        $comboProducts = ComboProduct::with(['parentProduct', 'parentProduct.tax', 'product_variant'])->where('product_id', $productId)->get();
        return response()->json($comboProducts);
    }

    // product update method
    public function update(Request $request, $productId)
    {
        $updateProduct = Product::with(['product_variants', 'ComboProducts'])->where('id', $productId)->first();
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $this->validate(
            $request,
            [
                'name' => 'required',
                'code' => 'required',
                'unit_id' => 'required',
                'photo' => 'sometimes|image|max:2048',
                'image.*' => 'sometimes|image|max:2048',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
                'code.required' => 'Product code field is required.',
            ]
        );

        $updateProduct->name = $request->name;
        $updateProduct->product_code = $request->code;
        $updateProduct->category_id = $request->category_id;
        $updateProduct->parent_category_id = $request->child_category_id;
        $updateProduct->brand_id = $request->brand_id;
        $updateProduct->unit_id = $request->unit_id;
        $updateProduct->alert_quantity = $request->alert_quantity;
        $updateProduct->tax_id = $tax_id;
        $updateProduct->tax_type = $request->tax_type;
        $updateProduct->expire_date = $request->expired_date ? $request->expired_date : NULL;
        $updateProduct->product_condition = $request->product_condition;
        $updateProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $updateProduct->is_for_sale = isset($request->is_not_for_sale) ? 0 : 1;
        $updateProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $updateProduct->product_details = $request->product_details;
        $updateProduct->is_purchased = 0;
        $updateProduct->barcode_type = $request->barcode_type;
        $updateProduct->warranty_id = $request->warranty_id;
        $updateProduct->weight = $request->weight;
        $updateProduct->custom_field_1 = $request->custom_field_1;
        $updateProduct->custom_field_2 = $request->custom_field_2;
        $updateProduct->custom_field_3 = $request->custom_field_3;

        //upload multiple photo for e-commerce
        if ($request->file('image')) {
            if (count($request->file('image')) > 2) {
                return response()->json(['errorMsg' => 'You can upload only 2 product images.']);
            }
        }

        if ($request->file('image')) {
            if (count($request->file('image')) > 0) {
                foreach ($request->file('image') as $image) {
                    $productImage = $image;
                    $productImageName = uniqid() . '.' . $productImage->getClientOriginalExtension();
                    Image::make($productImage)->resize(250, 250)->save('public/uploads/product/' . $productImageName);
                    $addProductImage = new ProductImage();
                    $addProductImage->product_id = $updateProduct->id;
                    $addProductImage->image = $productImageName;
                    $addProductImage->save();
                }
            }
        }

        if ($updateProduct->type == 1) {
            $this->validate(
                $request,
                [
                    'product_price' => 'required',
                    'product_cost' => 'required',
                    'product_cost_with_tax' => 'required',
                ],
            );

            $updateProduct->product_cost = $request->product_cost;
            $updateProduct->profit = $request->profit ? $request->profit : 0.00;
            $updateProduct->product_cost_with_tax = $request->product_cost_with_tax;
            $updateProduct->product_price = $request->product_price;

            // Upload product thumbnail
            if ($request->file('photo')) {
                if ($updateProduct->thumbnail_photo != 'default.png') {
                    if (file_exists(public_path('uploads/product/thumbnail/' . $updateProduct->thumbnail_photo))) {
                        unlink(public_path('uploads/product/thumbnail/' . $updateProduct->thumbnail_photo));
                    }
                }
                $productThumbnailPhoto = $request->file('photo');
                $productThumbnailName = uniqid() . '.' . $productThumbnailPhoto->getClientOriginalExtension();
                Image::make($productThumbnailPhoto)->resize(250, 250)->save('public/uploads/product/thumbnail/' . $productThumbnailName);
                $updateProduct->thumbnail_photo = $productThumbnailName;
            }

            if ($updateProduct->is_variant == 1) {
                if ($request->variant_combinations == null) {
                    return response()->json(['errorMsg' => 'You have selected variant option but there is no variant at all.']);
                }

                foreach ($updateProduct->product_variants as $product_variant) {
                    $product_variant->delete_in_update = 1;
                    $product_variant->save();
                }

                $this->validate(
                    $request,
                    [
                        'variant_image.*' => 'sometimes|image|max:2048',
                    ],
                );
                $updateProduct->save();

                $variant_ids = $request->variant_ids;
                $variant_combinations = $request->variant_combinations;
                $variant_codes = $request->variant_codes;
                $variant_costings = $request->variant_costings;
                $variant_costings_with_tax = $request->variant_costings_with_tax;
                $variant_profits = $request->variant_profits;
                $variant_prices_exc_tax = $request->variant_prices_exc_tax;
                $variant_images = $request->variant_image;
                $index = 0;
                foreach ($variant_combinations as $value) {
                    $updateVariant = ProductVariant::where('id', $variant_ids[$index])->first();
                    if ($updateVariant) {
                        $updateVariant->variant_name = $value;
                        $updateVariant->variant_code = $variant_codes[$index];
                        $updateVariant->variant_cost = $variant_costings[$index];
                        $updateVariant->variant_cost_with_tax = $variant_costings_with_tax[$index];
                        $updateVariant->variant_profit = $variant_profits[$index];
                        $updateVariant->variant_price = $variant_prices_exc_tax[$index];
                        $updateVariant->delete_in_update = 0;

                        if (isset($variant_images[$index])) {
                            if ($updateVariant->variant_image != null) {
                                if (file_exists(public_path('uploads/product/variant_image/' . $updateVariant->variant_image))) {
                                    unlink(public_path('uploads/product/thumbnail/' . $updateVariant->variant_image));
                                }
                            }

                            $variantImage = $variant_images[$index];
                            $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                            Image::make($variantImage)->resize(250, 250)->save('public/uploads/product/variant_image/' . $variantImageName);
                            $updateVariant->variant_image = $variantImageName;
                        }
                        $updateVariant->save();
                    } else {
                        $addVariant = new ProductVariant();
                        $addVariant->product_id = $updateProduct->id;
                        $addVariant->variant_name = $value;
                        $addVariant->variant_code = $variant_codes[$index];
                        $addVariant->variant_cost = $variant_costings[$index];
                        $addVariant->variant_cost_with_tax = $variant_costings_with_tax[$index];
                        $addVariant->variant_profit = $variant_profits[$index];
                        $addVariant->variant_price = $variant_prices_exc_tax[$index];

                        if (isset($variant_images[$index])) {
                            $variantImage = $variant_images[$index];
                            $variantImageName = uniqid() . '.' . $variantImage->getClientOriginalExtension();
                            Image::make($variantImage)->resize(250, 250)->save('public/uploads/product/variant_image/' . $variantImageName);
                            $addVariant->variant_image = $variantImageName;
                        }
                        $addVariant->save();
                    }
                    $index++;
                }

                $deleteNotFoundVariants = ProductVariant::where('delete_in_update', 1)->get();
                foreach ($deleteNotFoundVariants as $deleteNotFoundVariant) {
                    if ($deleteNotFoundVariant->variant_image != null) {
                        if (file_exists(public_path('uploads/product/variant_image/' . $updateVariant->variant_image))) {
                            unlink(public_path('uploads/product/thumbnail/' . $updateVariant->variant_image));
                        }
                    }
                    $deleteNotFoundVariant->delete();
                }
            } else {
                $updateProduct->save();
            }
        }

        if ($updateProduct->type == 2) {
            if ($request->product_ids == null) {
                return response()->json(['errorMsg' => 'You have selected combo product but there is no product at all']);
            }

            foreach ($updateProduct->ComboProducts as $ComboProduct) {
                $ComboProduct->delete_in_update = 1;
                $ComboProduct->save();
            }

            $updateProduct->profit = $request->profit ? $request->profit : 0.00;
            $updateProduct->product_price = $request->combo_price;
            $updateProduct->combo_price = $request->combo_price;
            $updateProduct->save();

            $combo_ids = $request->combo_ids;
            $productIds = $request->product_ids;
            $combo_quantities = $request->combo_quantities;
            $productVariantIds = $request->variant_ids;
            $index = 0;
            foreach ($productIds as $id) {
                $updateComboProduct = ComboProduct::where('id', $combo_ids[$index])->first();
                if ($updateComboProduct) {
                    $updateComboProduct->quantity = $combo_quantities[$index];
                    $updateComboProduct->delete_in_update = 0;
                    $updateComboProduct->save();
                } else {
                    $addComboProducts = new ComboProduct();
                    $addComboProducts->product_id = $updateProduct->id;
                    $addComboProducts->combo_product_id = $id;
                    $addComboProducts->quantity = $combo_quantities[$index];
                    $addComboProducts->product_variant_id = $productVariantIds[$index] !== 'noid' ? $productVariantIds[$index] : NULL;
                    $addComboProducts->save();
                }
                $index++;
            }
        }

        $deleteNotFoundComboProducts = ComboProduct::where('delete_in_update', 1)->get();
        foreach ($deleteNotFoundComboProducts as $deleteNotFoundComboProduct) {
            $deleteNotFoundComboProduct->delete();
        }

        session()->flash('successMsg', 'Successfully product is updated');
        return response()->json('Successfully product is updated');
    }

    // delete product
    public function delete(Request $request, $productId)
    {
        $deleteProduct = Product::with(['product_images', 'product_variants'])->where('id', $productId)->first();
        if (!is_null($deleteProduct)) {
            if ($deleteProduct->thumbnail_photo !== 'default.png') {
                if (file_exists(public_path('uploads/product/thumbnail/' . $deleteProduct->thumbnail_photo))) {
                    unlink(public_path('uploads/product/thumbnail/' . $deleteProduct->thumbnail_photo));
                }
            }

            if ($deleteProduct->product_images->count() > 0) {
                foreach ($deleteProduct->product_images as $product_image) {
                    if (file_exists(public_path('uploads/product/' . $product_image->image))) {
                        unlink(public_path('uploads/product/' . $product_image->image));
                    }
                }
            }

            if ($deleteProduct->product_variants->count() > 0) {
                foreach ($deleteProduct->product_variants as $product_variant) {
                    if ($product_variant->variant_image) {
                        if (file_exists(public_path('uploads/product/variant_image/' . $product_variant->variant_image))) {
                            unlink(public_path('uploads/product/variant_image/' . $product_variant->variant_image));
                        }
                    }
                }
            }

            $deleteProduct->delete();
        }
        return response()->json('Product deleted successfully');
    }

    // multiple delete method
    public function multipleDelete(Request $request)
    {
        if ($request->data_ids == null) {
            return response()->json(['errorMsg' => 'You did not select any product.']);
        }
        if ($request->action == 'multiple_delete') {
            //     foreach($request->data_ids as $data_id){
            //         $deleteProduct = Product::with(['product_images', 'product_variants'])->where('id', $data_id)->get();
            //         if (!is_null($deleteProduct)) {
            //             if ($deleteProduct->thumbnail_photo !== 'default.png') {
            //                 if (file_exists(public_path('uploads/product/thumbnail/'.$deleteProduct->thumbnail_photo))) {
            //                     unlink(public_path('uploads/product/thumbnail/'.$deleteProduct->thumbnail_photo));
            //                 } 
            //             }

            //             if($deleteProduct->product_images->count() > 0){
            //                 foreach($deleteProduct->product_images as $product_image){
            //                     if (file_exists(public_path('uploads/product/'.$product_image->image))) {
            //                         unlink(public_path('uploads/product/'.$product_image->image));
            //                     }
            //                 }
            //             }

            //             if($deleteProduct->product_variants->count() > 0){
            //                 foreach($deleteProduct->product_variants as $product_variant){
            //                     if($product_variant->variant_image){
            //                         if (file_exists(public_path('uploads/product/variant_image/'.$product_variant->variant_image))) {
            //                             unlink(public_path('uploads/product/variant_image/'.$product_variant->variant_image));
            //                         }
            //                     }
            //                 }
            //             }
            //             $deleteProduct->delete(); 
            //         }
            //     }
            //Cache::forget('all-products');
            return response()->json('Multiple delete feature is disabled in this demo');
        } elseif ($request->action == 'multipla_deactive') {
            foreach ($request->data_ids as $data_id) {
                $product = Product::where('id', $data_id)->first();
                $product->status = 0;
                $product->save();
            }
            return response()->json('Successfully all selected product status deactived');
        }
    }

    // Change product status method
    public function changeStatus($productId)
    {
        $statusChange = Product::where('id', $productId)->first();
        if ($statusChange->status == 1) {
            $statusChange->status = 0;
            $statusChange->save();
            return response()->json('Successfully Product is deactivated');
        } else {
            $statusChange->status = 1;
            $statusChange->save();
            return response()->json('Successfully Product is activated');
        }
    }

    //Get all form variant by ajax request
    public function getAllFormVariants()
    {
        $variants = BulkVariant::with(['bulk_variant_child'])->get();
        return response()->json($variants);
    }

    public function searchProduct($productCode)
    {
        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $productCode)->first();
        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $productCode)->first();
            return response()->json(['variant_product' => $variant_product]);
        }
    }

    public function chackPurchaseAndGenerateBarcode($productId)
    {
        $supplierProducts = SupplierProduct::where('product_id', $productId)->get();
        if ($supplierProducts->count() > 0) {
            return response()->json(route('products.generate.product.barcode', $productId));
        } else {
            return response()->json(['errorMsg' => 'This product yet to be purchased.']);
        }
    }

    // Get product warehouse stock ** reqeusted by ajax
    public function warehouseStock($productId)
    {
        $product = Product::with([
            'tax',
            'unit',
            'product_warehouses',
            'product_warehouses.warehouse',
            'product_warehouses.product_warehouse_variants',
            'product_warehouses.product_warehouse_variants.product_variant'
        ])->where('id', $productId)->first();
        return view('product.products.ajax_view.warehouse_stock_list', compact('product'));
    }

    // Get product branch stock ** reqeusted by ajax
    public function branchStock($productId)
    {
        $product = Product::with([
            'tax',
            'unit',
            'product_branches',
            'product_branches.branch',
            'product_branches.product_branch_variants',
            'product_branches.product_branch_variants.product_variant'
        ])->where('id', $productId)->first();
        return view('product.products.ajax_view.branch_stock_list', compact('product'));
    }

    // Add Category from add product
    public function addCategory(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'required',
        ]);

        $addBrand = new Category();
        $addBrand->name = $request->name;
        $addBrand->save();
        return response()->json($addBrand);
    }

    // Add brand from add product
    public function addBrand(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $addBrand = new Brand();
        $addBrand->name = $request->name;
        $addBrand->save();

        return response()->json($addBrand);
    }

    // Add brand from add product
    public function addUnit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
        ]);

        $addUnit = new Unit();
        $addUnit->name = $request->name;
        $addUnit->code_name = $request->code;
        $addUnit->save();
        return response()->json($addUnit);
    }

    public function addWarranty(Request $request)
    {
        $this->validate($request, [
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

    public function getFormPart($type)
    {
        $type = $type;
        $variants = BulkVariant::with(['bulk_variant_child'])->get();
        return view('product.products.ajax_view.form_part', compact('type', 'variants'));
    }

    public function allFromSubCategory($categoryId)
    {
        return $subCategories = DB::table('categories')->where('parent_category_id', $categoryId)->get(['id', 'name']);
    }
}
