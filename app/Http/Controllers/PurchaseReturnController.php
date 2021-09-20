<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Utils\NameSearchUtil;
use App\Models\ProductVariant;
use App\Models\PurchaseReturn;
use App\Models\PurchaseProduct;
use App\Models\ProductWarehouse;
use App\Utils\PurchaseReturnUtil;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\PurchaseReturnProduct;
use App\Models\ProductWarehouseVariant;
use App\Utils\ProductStockUtil;
use App\Utils\PurchaseUtil;
use App\Utils\SupplierUtil;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnController extends Controller
{
    protected $purchaseReturnUtil;
    protected $nameSearch;
    protected $productStockUtil;
    protected $supplierUtil;
    protected $purchaseUtil;
    public function __construct(
        PurchaseReturnUtil $purchaseReturnUtil,
        NameSearchUtil $nameSearch,
        ProductStockUtil $productStockUtil,
        SupplierUtil $supplierUtil,
        PurchaseUtil $purchaseUtil,
    ) {
        $this->purchaseReturnUtil = $purchaseReturnUtil;
        $this->nameSearch = $nameSearch;
        $this->productStockUtil = $productStockUtil;
        $this->supplierUtil = $supplierUtil;
        $this->purchaseUtil = $purchaseUtil;

        $this->middleware('auth:admin_and_user');
    }
    // Sale return index view
    public function index(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_return'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $returns = '';
            $generalSettings = DB::table('general_settings')->first();
            $query = DB::table('purchase_returns')
                ->leftJoin('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->leftJoin('branches', 'purchase_returns.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'purchase_returns.warehouse_id', 'warehouses.id')
                ->leftJoin('suppliers', 'purchase_returns.supplier_id', 'suppliers.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('purchase_returns.branch_id', NULL);
                } else {
                    $query->where('purchase_returns.branch_id', $request->branch_id);
                }
            }

            if ($request->supplier_id) {
                $query->where('purchase_returns.supplier_id', $request->supplier_id);
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0] . ' -1 days'));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                $query->whereBetween('purchase_returns.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $returns = $query->select(
                    'purchase_returns.*',
                    'purchases.invoice_id as parent_invoice_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'suppliers.name as sup_name',
                )->orderBy('id', 'desc');
            } else {
                $returns = $query->select(
                    'purchase_returns.*',
                    'purchases.invoice_id as parent_invoice_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'suppliers.name as sup_name',
                )->where('purchase_returns.branch_id', auth()->user()->branch_id)->orderBy('id', 'desc');
            }

            return DataTables::of($returns)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('purchases.returns.show', $row->id) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if ($row->return_type == 1) {
                            $html .= '<a class="dropdown-item" href="' . route('purchases.returns.create', $row->purchase_id) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        } else {
                            $html .= '<a class="dropdown-item" href="' . route('purchases.returns.supplier.return.edit', $row->id) . '"><i class="far fa-edit mr-1 text-primary"></i> Edit</a>';
                        }

                        $html .= '<a class="dropdown-item" id="delete" href="' . route('purchases.returns.delete', $row->id) . '"><i class="far fa-trash-alt mr-1 text-primary"></i> Delete</a>';
                        $html .= '<a class="dropdown-item" id="view_payment" href=""><i class="far fa-money-bill-alt mr-1 text-primary"></i> View Payment</a>';
                        if ($row->total_return_due > 0) {
                            $html .= '<a class="dropdown-item" id="add_purchase_supplier_return_payment" href=""><i class="far fa-money-bill-alt mr-1 text-primary"></i> Add Payment</a>';
                        }
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('location',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '<b>(BL)</b>';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('return_from',  function ($row) use ($generalSettings) {
                    if ($row->warehouse_name) {
                        return ($row->warehouse_name . '/' . $row->warehouse_code) . '<b>(WH)</b>';
                    } elseif ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '<b>(BL)</b>';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('total_return_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_return_amount . '</b>';
                })
                ->editColumn('total_return_due_received', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_return_due_received . '</b>';
                })
                ->editColumn('total_return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ($row->total_return_due >= 0 ? $row->total_return_due :   0.00) . '</span></b>';
                })

                ->editColumn('payment_status', function ($row) {
                    $html = '';
                    if ($row->total_return_due > 0) {
                        $html .= '<span class="text-danger"><b>Due</b></span>';
                    } else {
                        $html .= '<span class="text-success"><b>Paid</b></span>';
                    }
                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('purchases.returns.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'return_from', 'location', 'total_return_amount', 'total_return_due_received', 'total_return_due', 'payment_status'])
                ->make(true);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('purchases.purchase_return.index', compact('branches'));
    }

    // create purchase return view
    public function create($purchaseId)
    {
        if (auth()->user()->permission->purchase['purchase_return'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        $purchaseId = $purchaseId;
        $purchase = Purchase::with(['warehouse', 'branch', 'supplier'])->where('id', $purchaseId)->first();
        return view('purchases.purchase_return.create', compact('purchaseId', 'purchase'));
    }

    public function store(Request $request, $purchaseId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_return'];

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $purchase_product_ids = $request->purchase_product_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;

        $qty = 0;
        foreach ($return_quantities as $return_quantity) {
            if ($return_quantity > 0) {
                $qty += 1;
            }
        }

        if ($qty == 0) {
            return response()->json(['errorMsg' => "All product`s quantity is 0."]);
        }

        $purchaseReturn = PurchaseReturn::where('purchase_id', $purchaseId)->first();
        if ($purchaseReturn) {
            $this->purchaseReturnUtil->updatePurchaseInvoiceWiseReturn($purchaseId, $purchaseReturn, $request, $invoicePrefix, $invoiceId);
        } else {
            $this->purchaseReturnUtil->storePurchaseInvoiceWiseReturn($purchaseId, $request, $invoicePrefix, $invoiceId);
        }

        if ($request->action == 'save_and_print') {
            $purchaseReturn = PurchaseReturn::with([
                'purchase',
                'branch',
                'supplier',
                'warehouse',
                'purchase.supplier',
                'purchase_return_products',
                'purchase_return_products.product',
                'purchase_return_products.purchase_product'
            ])->where('purchase_id', $purchaseId)->first();

            if ($purchaseReturn) {
                return view('purchases.purchase_return.save_and_print_template.purchase_return_print_view', compact('purchaseReturn'));
            }
        } else {
            return response()->json(['successMsg' => 'Purchase Return Added Successfully.']);
        }
    }

    // Show purchase return details
    public function show($returnId)
    {
        $return = PurchaseReturn::with([
            'warehouse',
            'branch',
            'supplier',
            'purchase_return_products',
            'purchase_return_products.product',
            'purchase_return_products.variant',
            'purchase_return_products.purchase_product'
        ])->where('id', $returnId)->first();

        return view('purchases.purchase_return.ajax_view.show', compact('return'));
    }

    // Get purchase requested by ajax
    public function getPurchase($purchaseId)
    {
        $purchase = Purchase::with([
            'purchase_products',
            'purchase_products.product',
            'purchase_products.variant',
            'purchase_return',
            'purchase_return.purchase_return_products',
            'purchase_return.purchase_return_products.purchase_product',
            'purchase_return.purchase_return_products.purchase_product.product',
            'purchase_return.purchase_return_products.purchase_product.variant'
        ])->where('id', $purchaseId)->first();
        return response()->json($purchase);
    }

    //Deleted purchase return 
    public function delete($purchaseReturnId)
    {
        $purchaseReturn = PurchaseReturn::with(['purchase', 'purchase.supplier', 'supplier', 'purchase_return_products'])->where('id', $purchaseReturnId)->first();
        $purchaseReturn->purchase->is_return_available = 0;
        $storeReturnProducts = $purchaseReturn->purchase_return_products;
        $storePurchase = $purchaseReturn->purchase;
        $storeSupplierId = $purchaseReturn->purchase ? $purchaseReturn->purchase->supplier_id : $purchaseReturn->supplier_id;
        if ($purchaseReturn->return_type == 1) {
            if ($purchaseReturn->total_return_due_received > 0) {
                return response()->json(['errorMsg' => "You can not delete this, casuse your have received some or full amount on this return."]);
            }

            foreach ($purchaseReturn->purchase_return_products as $purchase_return_product) {
                // Get purchase product
                $purchaseProduct = PurchaseProduct::where('id', $purchase_return_product->purchase_product_id)->first();

                if ($purchaseReturn->purchase->warehouse_id) {
                    // Addition product warehouse qty for adjustment
                    $productWarehouse = ProductWarehouse::where('warehouse_id', $purchaseReturn->warehouse_id)->where('product_id', $purchaseProduct->product_id)->first();
                    $productWarehouse->product_quantity += $purchase_return_product->return_qty;
                    $productWarehouse->save();

                    // Addition product warehouse variant qty for adjustment
                    if ($purchaseProduct->product_variant_id) {
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                        $productWarehouseVariant->variant_quantity += $purchase_return_product->return_qty;
                        $productWarehouseVariant->save();
                    }
                } elseif ($purchaseReturn->purchase->branch_id) {
                    // Addition product branch qty for adjustment
                    $productBranch = ProductBranch::where('branch_id', $purchaseReturn->purchase->branch_id)->where('product_id', $purchaseProduct->product_id)->first();
                    $productBranch->product_quantity += $purchase_return_product->return_qty;
                    $productBranch->save();

                    // Addition product warehouse variant qty for adjustment
                    if ($purchaseProduct->product_variant_id) {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $purchaseReturn->purchase->branch_id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                        $productBranchVariant->variant_quantity += $purchase_return_product->return_qty;
                        $productBranchVariant->save();
                    }
                } else {
                    $MbStock = Product::where('id', $purchaseProduct->product_id)->first();
                    $MbStock->mb_stock += $purchase_return_product->return_qty;
                    $MbStock->save();

                    if ($purchaseProduct->product_variant_id) {
                        $updateProVariantMbStock = ProductVariant::where('id', $purchaseProduct->product_variant_id)
                            ->where('product_id', $purchaseProduct->product_id)
                            ->first();
                        $updateProVariantMbStock->mb_stock += $purchase_return_product->return_qty;
                        $updateProVariantMbStock->save();
                    }
                }
            }
        } else {
            if ($purchaseReturn->total_return_due_received > 0) {
                return response()->json(['errorMsg' => "You can not delete this, casuse your have received some or full amount on this return."]);
            }

            foreach ($purchaseReturn->purchase_return_products as $purchase_return_product) {
                // Addition product warehouse qty for adjustment
                if ($purchaseReturn->warehouse_id) {
                    // Addition product warehouse qty for adjustment
                    $productWarehouse = ProductWarehouse::where('warehouse_id', $purchaseReturn->warehouse_id)->where('product_id', $purchaseProduct->product_id)->first();
                    $productWarehouse->product_quantity += $purchase_return_product->return_qty;
                    $productWarehouse->save();

                    // Addition product warehouse variant qty for adjustment
                    if ($purchase_return_product->product_variant_id) {
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                        $productWarehouseVariant->variant_quantity += $purchase_return_product->return_qty;
                        $productWarehouseVariant->save();
                    }
                } elseif ($purchaseReturn->purchase->branch_id) {
                    // Addition product branch qty for adjustment
                    $productBranch = ProductBranch::where('branch_id', $purchaseReturn->purchase->branch_id)->where('product_id', $purchaseProduct->product_id)->first();
                    $productBranch->product_quantity += $purchase_return_product->return_qty;
                    $productBranch->save();

                    // Addition product warehouse variant qty for adjustment
                    if ($purchase_return_product->product_variant_id) {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $purchaseReturn->purchase->branch_id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                        $productBranchVariant->variant_quantity += $purchase_return_product->return_qty;
                        $productBranchVariant->save();
                    }
                } else {
                    $MbStock = Product::where('id', $purchase_return_product->product_id)->first();
                    $MbStock->mb_stock += $purchase_return_product->return_qty;
                    $MbStock->save();

                    if ($purchase_return_product->product_variant_id) {
                        $updateProVariantMbStock = ProductVariant::where('id', $purchase_return_product->product_variant_id)
                            ->where('product_id', $purchase_return_product->product_id)
                            ->first();
                        $updateProVariantMbStock->mb_stock += $purchase_return_product->return_qty;
                        $updateProVariantMbStock->save();
                    }
                }
            }
        }

        $purchaseReturn->delete();

        foreach ($storeReturnProducts as $return_product) {
            $this->productStockUtil->adjustMainProductAndVariantStock($return_product->product_id, $return_product->product_variant_id);
        }

        if ($storePurchase) {
            $this->purchaseUtil->adjustPurchaseInvoiceAmounts($storePurchase);
        }

        $this->supplierUtil->adjustSupplierForSalePaymentDue($storeSupplierId);

        return response()->json('Successfully purchase return is deleted');
    }

    public function supplierReturn()
    {
        if (auth()->user()->permission->purchase['purchase_return'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')
            ->where('branch_id', auth()->user()->branch_id)->get();
        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();
        return view('purchases.purchase_return.supplier_return', compact('warehouses', 'suppliers'));
    }

    // Search product by code
    public function searchProduct($product_code, $warehouse_id)
    {
        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $product_code)->first();
        if ($product) {
            $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $product->id)->first();
            if ($productWarehouse) {
                if ($product->type == 1) {
                    if ($productWarehouse->product_quantity > 0) {
                        return response()->json(['product' => $product, 'qty_limit' => $productWarehouse->product_quantity]);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is out of this product of this warehouse']);
                    }
                }
            } else {
                return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
            }
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $product_code)->first();
            if ($variant_product) {
                $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $variant_product->product_id)->first();

                if (is_null($productWarehouse)) {
                    return response()->json(['errorMsg' => 'This product is not available in this warehouse']);
                }

                $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $variant_product->product_id)->where('product_variant_id', $variant_product->id)->first();

                if (is_null($productWarehouseVariant)) {
                    return response()->json(['errorMsg' => 'This variant is not available in this warehouse']);
                }

                if ($productWarehouse && $productWarehouseVariant) {
                    if ($productWarehouseVariant->variant_quantity > 0) {
                        return response()->json(['variant_product' => $variant_product, 'qty_limit' => $productWarehouseVariant->variant_quantity]);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
                    }
                } else {
                    return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
                }
            }
        }

        return $this->nameSearch->nameSearching($product_code);
    }

    public function searchProductInBranch($productCode)
    {
        $branchId = auth()->user()->branch_id;
        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $productCode)->first();
        if ($product) {
            if ($branchId) {
                $productBranch = ProductBranch::where('branch_id', $branchId)->where('product_id', $product->id)->first();
                if ($productBranch) {
                    if ($product->type == 1) {
                        if ($productBranch->product_quantity > 0) {
                            return response()->json(['product' => $product, 'qty_limit' => $productBranch->product_quantity]);
                        } else {
                            return response()->json(['errorMsg' => 'Stock is out of this product from this branch']);
                        }
                    }
                } else {
                    return response()->json(['errorMsg' => 'This product is not available in this branch.']);
                }
            } else {
                if ($product->type == 1) {
                    if ($product->mb_stock > 0) {
                        return response()->json(['product' => $product, 'qty_limit' => $product->mb_stock]);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is out of this product from this shop/branch']);
                    }
                }
            }
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $productCode)->first();
            if ($variant_product) {
                if ($branchId) {
                    $productBranch = ProductBranch::where('branch_id', $branchId)
                        ->where('product_id', $variant_product->product_id)->first();

                    if (is_null($productBranch)) {
                        return response()->json(['errorMsg' => 'This product is not available in this branch']);
                    }

                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('product_variant_id', $variant_product->id)->first();

                    if (is_null($productBranchVariant)) {
                        return response()->json(['errorMsg' => 'This variant is not available in this branch']);
                    }

                    if ($productBranch && $productBranchVariant) {
                        if ($productBranchVariant->variant_quantity > 0) {
                            return response()->json([
                                'variant_product' => $variant_product,
                                'qty_limit' => $productBranchVariant->variant_quantity
                            ]);
                        } else {
                            return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this branch']);
                        }
                    } else {
                        return response()->json(['errorMsg' => 'This product is not available in this branch.']);
                    }
                } else {
                    if ($variant_product->mb_stock > 0) {
                        return response()->json([
                            'variant_product' => $variant_product,
                            'qty_limit' => $variant_product->mb_stock
                        ]);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is not available of this variant in this shop/branch']);
                    }
                }
            }
        }

        return $this->nameSearch->nameSearching($productCode);
    }

    public function checkWarehouseProductVariant($productId, $variantId, $warehouseId)
    {
        $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouseId)->where('product_id', $productId)->first();
        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $productId)->where('product_variant_id', $variantId)->first();
        if ($productWarehouse && $productWarehouseVariant) {
            if ($productWarehouseVariant->variant_quantity > 0) {
                return response()->json($productWarehouseVariant->variant_quantity);
            } else {
                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
            }
        } else {
            return response()->json(['errorMsg' => 'This variant is not available in this warehouse.']);
        }
    }

    public function checkBranchProductVariant($productId, $variantId)
    {
        $branchId = auth()->user()->branch_id;
        if ($branchId) {
            $productBranch = ProductBranch::where('branch_id', $branchId)->where('product_id', $productId)->first();
            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $productId)->where('product_variant_id', $variantId)->first();
            if ($productBranch && $productBranchVariant) {
                if ($productBranchVariant->variant_quantity > 0) {
                    return response()->json($productBranchVariant->variant_quantity);
                } else {
                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) from this branch']);
                }
            } else {
                return response()->json(['errorMsg' => 'This variant is not available in this branch.']);
            }
        } else {
            $variant_product = DB::table('product_variants')->where('id', $variantId)->first();
            if ($variant_product->mb_stock > 0) {
                return response()->json($variant_product->mb_stock);
            } else {
                return response()->json(['errorMsg' => 'Stock is not available of this variant in this shop/branch']);
            }
        }
    }

    public function supplierReturnStore(Request $request)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_return'];
        //return $request->all();

        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'Product return table is empty']);
        }

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $addPurchaseReturn = new PurchaseReturn();
        $addPurchaseReturn->supplier_id = $request->supplier_id;

        $addPurchaseReturn->warehouse_id = isset($request->warehouse_id) ? $request->warehouse_id : NULL;
        $addPurchaseReturn->branch_id = auth()->user()->branch_id;

        $addPurchaseReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'PRI') . date('ymd') . $invoiceId;
        $addPurchaseReturn->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
        $addPurchaseReturn->purchase_tax_amount = $request->purchase_tax_amount;
        $addPurchaseReturn->total_return_amount = $request->total_return_amount;
        $addPurchaseReturn->total_return_due = $request->total_return_amount;
        $addPurchaseReturn->date = $request->date;
        $addPurchaseReturn->return_type = 2;
        $addPurchaseReturn->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchaseReturn->month = date('F');
        $addPurchaseReturn->year = date('Y');
        $addPurchaseReturn->admin_id = auth()->user()->id;
        $addPurchaseReturn->save();

        // Update supplier purchase return amount 
        $supplier = Supplier::where('id', $request->supplier_id)->first();
        $supplier->total_purchase_return_due += $request->total_return_amount;
        $supplier->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;

        // Update product stock
        $this->purchaseReturnUtil->updateProductStock($request);

        // Add purchase return product
        $__index = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
            $addPurchaseReturnProduct = new PurchaseReturnProduct();
            $addPurchaseReturnProduct->purchase_return_id = $addPurchaseReturn->id;
            $addPurchaseReturnProduct->product_id = $product_id;
            $addPurchaseReturnProduct->product_variant_id = $variant_id;
            $addPurchaseReturnProduct->return_qty = $return_quantities[$__index];
            $addPurchaseReturnProduct->unit = $units[$__index];
            $addPurchaseReturnProduct->return_subtotal = $return_subtotals[$__index];
            $addPurchaseReturnProduct->save();
            $__index++;
        }

        return response()->json('Successfully purchase return is added.');
    }

    // Edit supplier return view
    public function supplierReturnEdit($purchaseReturnId)
    {
        $purchaseReturnId = $purchaseReturnId;
        $return = DB::table('purchase_returns')->where('id', $purchaseReturnId)->first();
        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')->get();
        return view('purchases.purchase_return.edit_supplier_return', compact('purchaseReturnId', 'return', 'warehouses'));
    }

    public function getEditableSupplierReturn($purchaseReturnId)
    {
        $purchaseReturn = PurchaseReturn::with([
            'supplier',
            'purchase_return_products',
            'purchase_return_products.product',
            'purchase_return_products.variant',
        ])->where('id', $purchaseReturnId)->first();

        $qty_limits = [];
        if ($purchaseReturn->warehouse_id) {
            foreach ($purchaseReturn->purchase_return_products as $purchase_return_product) {
                $productWarehouse = ProductWarehouse::where('warehouse_id', $purchaseReturn->warehouse_id)
                    ->where('product_id', $purchase_return_product->product_id)->first();
                if ($purchase_return_product->product_variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $purchase_return_product->product_id)
                        ->where('product_variant_id', $purchase_return_product->product_variant_id)
                        ->first();
                    $qty_limits[] = $productWarehouseVariant->variant_quantity;
                } else {
                    $qty_limits[] = $productWarehouse->product_quantity;
                }
            }
        } else {
            foreach ($purchaseReturn->purchase_return_products as $purchase_return_product) {
                $productBranch = ProductBranch::where('branch_id', $purchaseReturn->branch_id)
                    ->where('product_id', $purchase_return_product->product_id)->first();
                if ($purchase_return_product->product_variant_id) {
                    $productWaBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $purchase_return_product->product_id)
                        ->where('product_variant_id', $purchase_return_product->product_variant_id)
                        ->first();
                    $qty_limits[] = $productWaBranchVariant->variant_quantity;
                } else {
                    $qty_limits[] = $productBranch->product_quantity;
                }
            }
        }

        return response()->json(['purchaseReturn' => $purchaseReturn, 'qty_limits' => $qty_limits]);
    }

    public function supplierReturnUpdate(Request $request, $purchaseReturnId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_return'];
        $updatePurchaseReturn = PurchaseReturn::with('purchase_return_products')->where('id', $purchaseReturnId)->first();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;

        // Update product stock for adjustment
        $this->purchaseReturnUtil->updateProductStockForAdjustment($updatePurchaseReturn);
        // Generate invoice id.
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        //update supplier return due
        $supplier = Supplier::where('id', $updatePurchaseReturn->supplier_id)->first();
        $presentDue = $request->total_return_amount - $updatePurchaseReturn->total_return_due_received;
        $previousDue = $updatePurchaseReturn->total_return_due;
        $supplierReturnDue = $presentDue - $previousDue;
        $supplier->total_purchase_return_due += (float)$supplierReturnDue;
        $supplier->save();

        $updatePurchaseReturn->warehouse_id = isset($request->warehouse_id) ? $request->warehouse_id : NULL;
        $updatePurchaseReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'PRI') . date('ymd') . $invoiceId;
        $updatePurchaseReturn->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
        $updatePurchaseReturn->purchase_tax_amount = $request->purchase_tax_amount;
        $updatePurchaseReturn->total_return_amount = $request->total_return_amount;
        $updatePurchaseReturn->total_return_due = $request->total_return_amount - $updatePurchaseReturn->total_return_due_received;
        $updatePurchaseReturn->date = $request->date;
        $updatePurchaseReturn->return_type = 2;
        $updatePurchaseReturn->report_date = date('Y-m-d', strtotime($request->date));
        $updatePurchaseReturn->month = date('F');
        $updatePurchaseReturn->year = date('Y');
        $updatePurchaseReturn->save();

        // Update product stock
        $this->purchaseReturnUtil->updateProductStock($request);

        // Update Purchase Return Product
        $__index = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
            $purchaseReturnProduct = PurchaseReturnProduct::where('purchase_return_id')
                ->where('product_id', $product_id)->where('product_variant_id', $variant_id)->first();

            if ($purchaseReturnProduct) {
                $purchaseReturnProduct->return_qty = $return_quantities[$__index];
                $purchaseReturnProduct->unit = $units[$__index];
                $purchaseReturnProduct->return_subtotal = $return_subtotals[$__index];
                $purchaseReturnProduct->is_delete_in_update = 0;
                $purchaseReturnProduct->save();
            } else {
                $addPurchaseReturnProduct = new PurchaseReturnProduct();
                $addPurchaseReturnProduct->purchase_return_id = $updatePurchaseReturn->id;
                $addPurchaseReturnProduct->product_id = $product_id;
                $addPurchaseReturnProduct->product_variant_id = $variant_id;
                $addPurchaseReturnProduct->unit = $units[$__index];
                $addPurchaseReturnProduct->return_qty = $return_quantities[$__index];
                $addPurchaseReturnProduct->return_subtotal = $return_subtotals[$__index];
                $addPurchaseReturnProduct->save();
            }
            $__index++;
        }

        // delete not found previous products
        $purchaseReturnProducts = PurchaseReturnProduct::where('is_delete_in_update', 1)->get();
        foreach ($purchaseReturnProducts as $purchaseReturnProduct) {
            $purchaseReturnProduct->delete();
        }

        session()->flash('successMsg', 'Purchase return created successfully.');
        return response()->json('Purchase return created successfully.');
    }
}