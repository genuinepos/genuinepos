<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\StockAdjustment;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\StockAdjustmentProduct;
use App\Utils\Converter;
use App\Utils\ProductStockUtil;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentController extends Controller
{
    protected $productStockUtil;
    protected $converter;
    public function __construct(ProductStockUtil $productStockUtil, Converter $converter)
    {
        $this->productStockUtil = $productStockUtil;
        $this->converter = $converter;
        $this->middleware('auth:admin_and_user');
    }

    // Index view of stock adjustment
    public function index(Request $request)
    {
        if (auth()->user()->permission->s_adjust['adjustment_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $adjustments = '';
            $query = DB::table('stock_adjustments')->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'stock_adjustments.warehouse_id', 'warehouses.id')
                ->leftJoin('admin_and_users', 'stock_adjustments.admin_id', 'admin_and_users.id');

            if ($request->branch_id) {
                if ($request->branch_id == 'NULL') {
                    $query->where('stock_adjustments.branch_id', NULL);
                } else {
                    $query->where('stock_adjustments.branch_id', $request->branch_id);
                }
            }

            if ($request->type) {
                $query->where('stock_adjustments.type', $request->type);
            }

            if ($request->from_date) {
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('stock_adjustments.report_date_ts', $date_range); // Final
            }

            $query->select(
                'stock_adjustments.*',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'admin_and_users.prefix',
                'admin_and_users.name',
                'admin_and_users.last_name',
            );

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $adjustments = $query->orderBy('id', 'desc');
            } else {
                $adjustments = $query->where('stock_adjustments.branch_id', auth()->user()->branch_id)
                    ->orderBy('stock_adjustments.report_date_ts', 'desc');
            }

            return DataTables::of($adjustments)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="' . route('stock.adjustments.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';

                    if (auth()->user()->permission->s_adjust['adjustment_delete'] == '1') {
                        if (auth()->user()->branch_id == $row->branch_id) {
                            $html .= '<a class="dropdown-item" id="delete" href="' . route('stock.adjustments.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i> Delete
                            </a>';
                        }
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {
                    return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
                })
                ->editColumn('business_location',  function ($row) use ($generalSettings) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('adjustment_location',  function ($row) use ($generalSettings) {
                    if ($row->warehouse_name) {
                        return $row->warehouse_name . '/' . $row->warehouse_code . '(<b>WH</b>)';
                    } elseif ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BR</b>)';
                    } else {
                        return json_decode($generalSettings->business, true)['shop_name'] . '<b>(HO)</b>';
                    }
                })
                ->editColumn('type',  function ($row) {
                    return $row->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>';
                })
                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . $this->converter->format_in_bdt($row->net_total_amount) . '</span>')
                ->editColumn('recovered_amount', fn ($row) => '<span class="recovered_amount" data-value="' . $row->recovered_amount . '">' . $this->converter->format_in_bdt($row->recovered_amount) . '</span>')
                ->editColumn('created_by', function ($row) {
                    return $row->prefix . ' ' . $row->name . ' ' . $row->last_name;
                })
                ->rawColumns(['action', 'date', 'invoice_id', 'business_location', 'adjustment_location', 'type', 'net_total_amount', 'recovered_amount', 'created_by'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('stock_adjustment.index', compact('branches'));
    }

    public function show($adjustmentId)
    {
        $adjustment = StockAdjustment::with(
            'warehouse',
            'branch',
            'adjustment_products',
            'adjustment_products.product',
            'adjustment_products.variant',
            'admin'
        )->where('id', $adjustmentId)->first();
        return view('stock_adjustment.ajax_view.show', compact('adjustment'));
    }

    // Stock adjustment create view
    public function create()
    {
        if (auth()->user()->permission->s_adjust['adjustment_add_from_location'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        return view('stock_adjustment.create');
    }

    public function createFromWarehouse()
    {
        if (auth()->user()->permission->s_adjust['adjustment_add_from_warehouse'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $warehouses = DB::table('warehouses')
            ->where('branch_id', auth()->user()->branch_id)
            ->get(['id', 'warehouse_name', 'warehouse_code']);

        return view('stock_adjustment.create_from_warehouse', compact('warehouses'));
    }

    // Store Stock Adjustment
    public function store(Request $request)
    {
        if (isset($request->warehouse_id)) {
            if (auth()->user()->permission->s_adjust['adjustment_add_from_warehouse'] == '0') {
                return response()->json('Access Denied.');
            }
        } else {
            if (auth()->user()->permission->s_adjust['adjustment_add_from_location'] == '0') {
                return response()->json('Access Denied.');
            }
        }

        $this->validate($request, [
            'type' => 'required',
        ]);

        if (isset($request->warehouse_id)) {
            $this->validate($request, [
                'warehouse_id' => 'required',
            ]);
        }

        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty.']);
        }

        // generate invoice ID
        $invoiceId = 1;
        $lastRow = DB::table('stock_adjustments')->orderBy('id', 'desc')->first();
        if ($lastRow) {
            $invoiceId = ++$lastRow->id;
        }

        // Add Stock adjustment.
        $addStockAdjustment = new StockAdjustment();
        $addStockAdjustment->warehouse_id = isset($request->warehouse_id) ? $request->warehouse_id : NULL;
        $addStockAdjustment->branch_id = auth()->user()->branch_id;

        $addStockAdjustment->invoice_id = $request->invoice_id ? $request->invoice_id : date('my') . $invoiceId;
        $addStockAdjustment->type = $request->type;
        $addStockAdjustment->total_item = $request->total_item;
        $addStockAdjustment->net_total_amount = $request->net_total_amount;
        $addStockAdjustment->recovered_amount = $request->total_recodered_amount ? $request->total_recodered_amount : 0;
        $addStockAdjustment->date = $request->date;
        $addStockAdjustment->time = date('h:i:s a');;
        $addStockAdjustment->month = date('F');
        $addStockAdjustment->year = date('Y');
        $addStockAdjustment->report_date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addStockAdjustment->admin_id = auth()->user()->id;
        $addStockAdjustment->reason = $request->reason;
        $addStockAdjustment->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;
        $units = $request->units;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $subtotals = $request->subtotals;

        $index = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $addStockAdjustmentProduct = new StockAdjustmentProduct();
            $addStockAdjustmentProduct->stock_adjustment_id = $addStockAdjustment->id;
            $addStockAdjustmentProduct->product_id = $product_id;
            $addStockAdjustmentProduct->product_variant_id = $variant_id;
            $addStockAdjustmentProduct->quantity = $quantities[$index];
            $addStockAdjustmentProduct->unit = $units[$index];
            $addStockAdjustmentProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
            $addStockAdjustmentProduct->subtotal = $subtotals[$index];
            $addStockAdjustmentProduct->save();

            $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);
            if (isset($request->warehouse_id)) {
                $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $request->warehouse_id);
            } else {
                $this->productStockUtil->adjustBranchStock($product_id, $variant_id, auth()->user()->branch_id);
            }
            $index++;
        }
        session()->flash('successMsg', 'Stock adjustment created successfully');
        return response()->json('Stock adjustment created successfully');
    }

    // Delete stock adjustment
    public function delete($adjustmentId)
    {
        $deleteAdjustment = StockAdjustment::with([
            'adjustment_products',
            'adjustment_products.product',
            'adjustment_products.variant'
        ])->where('id', $adjustmentId)->first();

        if (!is_null($deleteAdjustment)) {
            $storedWarehouseId = $deleteAdjustment->warehouse_id;
            $storedBranchId = $deleteAdjustment->branch_id;
            $storedAdjustmentProducts = $deleteAdjustment->adjustment_products;
            $deleteAdjustment->delete();
            foreach ($storedAdjustmentProducts as $adjustment_product) {
                // Update product qty for adjustment
                $this->productStockUtil->adjustMainProductAndVariantStock($adjustment_product->product_id, $adjustment_product->product_variant_id);
                if ($storedWarehouseId) {
                    $this->productStockUtil->adjustWarehouseStock($adjustment_product->product_id, $adjustment_product->product_variant_id, $storedWarehouseId);
                } else {
                    $this->productStockUtil->adjustBranchStock($adjustment_product->product_id, $adjustment_product->product_variant_id, $storedBranchId);
                } 
            }
        }
        return response()->json('Stock adjustment deleted successfully.');
    }

    // Search product
    public function searchProduct($keyword)
    {
        $namedProducts = Product::with(['product_variants', 'tax', 'unit'])
            ->where('name', 'LIKE', $keyword . '%')
            ->where('status', 1)->get();

        if ($namedProducts->count() > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        }

        $branch_id = auth()->user()->branch_id;
        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $keyword)->first();
        if ($product) {
            $productBranch = DB::table('product_branches')->where('branch_id', $branch_id)->where('product_id', $product->id)->first();
            if ($productBranch) {
                if ($product->type == 2) {
                    return response()->json(['errorMsg' => 'Combo product is not adjustable.']);
                } else {
                    if ($productBranch->product_quantity > 0) {
                        return response()->json([
                            'product' => $product,
                            'qty_limit' => $productBranch->product_quantity
                        ]);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is out of this product of this branch']);
                    }
                }
            } else {
                return response()->json(['errorMsg' => 'This product is not available in this branch.']);
            }
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $keyword)
                ->first();

            if ($variant_product) {

                $productBranch = DB::table('product_branches')->where('branch_id', $branch_id)->where('product_id', $variant_product->product_id)->first();
                if (is_null($productBranch)) {
                    return response()->json(['errorMsg' => 'This product is not available in this shop']);
                }

                $productBranchVariant = DB::table('product_branch_variants')->where('product_branch_id', $productBranch->id)
                    ->where('product_id', $variant_product->product_id)
                    ->where('product_variant_id', $variant_product->id)
                    ->first();

                if (is_null($productBranchVariant)) {
                    return response()->json(['errorMsg' => 'This variant is not available in this shop']);
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
            }
        }
    }

    public function searchProductInWarehouse($keyword, $warehouse_id)
    {
        $namedProducts = Product::with(['product_variants', 'tax', 'unit'])
            ->where('name', 'LIKE', $keyword . '%')
            ->where('status', 1)->get();

        if (count($namedProducts) > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        }

        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $keyword)->first();
        if ($product) {
            $productWarehouse = DB::table('product_warehouses')
                ->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)
                ->first();

            if ($productWarehouse) {
                if ($product->type == 2) {
                    return response()->json(['errorMsg' => 'Combo product is not adjustable.']);
                } else {
                    if ($productWarehouse->product_quantity > 0) {
                        return response()->json([
                            'product' => $product,
                            'qty_limit' => $productWarehouse->product_quantity
                        ]);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is out of this product of this warehouse']);
                    }
                }
            } else {
                return response()->json(['errorMsg' => 'This product is not available in this branch.']);
            }
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $keyword)->first();
            if ($variant_product) {
                $productWarehouse = DB::table('product_warehouses')
                    ->where('warehouse_id', $warehouse_id)
                    ->where('product_id', $variant_product->product_id)->first();

                if (is_null($productWarehouse)) {
                    return response()->json(['errorMsg' => 'This product is not available in this warehouse']);
                }

                $productWarehouseVariant = DB::table('product_warehouse_variants')
                    ->where('product_warehouse_id', $productWarehouse->id)
                    ->where('product_id', $variant_product->product_id)
                    ->where('product_variant_id', $variant_product->id)->first();

                if (is_null($productWarehouseVariant)) {
                    return response()->json(['errorMsg' => 'This variant is not available in this warehouse']);
                }

                if ($productWarehouseVariant->variant_quantity > 0) {
                    return response()->json([
                        'variant_product' => $variant_product,
                        'qty_limit' => $productWarehouseVariant->variant_quantity
                    ]);
                } else {
                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
                }
            }
        }
    }

    public function checkSingleProductStockInWarehouse($product_id, $warehouse_id)
    {
        $productWarehouse = ProductWarehouse::where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();
        if ($productWarehouse) {
            if ($productWarehouse->product_quantity > 0) {
                return response()->json($productWarehouse->product_quantity);
            } else {
                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this Warehouse']);
            }
        } else {
            return response()->json(['errorMsg' => 'This product is not available in this Warehouse.']);
        }
    }

    public function checkVariantProductStockInWarehouse($product_id, $variant_id, $warehouse_id)
    {
        $productWarehouse = DB::table('product_warehouses')
            ->where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)->first();
        if ($productWarehouse) {
            $productWarehouseVariant = DB::table('product_warehouse_variants')
                ->where('product_warehouse_id', $productWarehouse->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();
            if ($productWarehouseVariant) {
                if ($productWarehouseVariant->variant_quantity > 0) {
                    return response()->json($productWarehouseVariant->variant_quantity);
                } else {
                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
                }
            } else {
                return response()->json(['errorMsg' => 'This variant is not available in this warehouse.']);
            }
        } else {
            return response()->json(['errorMsg' => 'This variant is not available in this warehouse.']);
        }
    }

    public function checkSingleProductStock($product_id)
    {
        $branch_id = auth()->user()->branch_id;
        $productBranch = ProductBranch::where('product_id', $product_id)
            ->where('branch_id', $branch_id)
            ->first();
        if ($productBranch) {
            if ($productBranch->product_quantity > 0) {
                return response()->json($productBranch->product_quantity);
            } else {
                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this shop/branch']);
            }
        } else {
            return response()->json(['errorMsg' => 'This product is not available in this shop/branch.']);
        }
    }

    public function checkVariantProductStock($product_id, $variant_id)
    {
        $branch_id = auth()->user()->branch_id;

        $productBranch = DB::table('product_branches')->where('branch_id', $branch_id)->where('product_id', $product_id)->first();
        if ($productBranch) {
            $productBranchVariant = DB::table('product_branch_variants')
                ->where('product_branch_id', $productBranch->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($productBranchVariant) {
                if ($productBranchVariant->variant_quantity > 0) {
                    return response()->json($productBranchVariant->variant_quantity);
                } else {
                    return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this shop']);
                }
            } else {
                return response()->json(['errorMsg' => 'This variant is not available in this branch/shop.']);
            }
        } else {
            return response()->json(['errorMsg' => 'This product is not available in this branch/shop.']);
        }
    }
}
