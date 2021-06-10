<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\StockAdjustment;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\StockAdjustmentProduct;
use App\Models\ProductWarehouseVariant;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentController extends Controller
{
    public function __construct()
    {
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

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                //$form_date = date('Y-m-d', strtotime($date_range[0]. '-1 days'));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                //$to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('stock_adjustments.report_date_ts', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
                //$query->whereDate('report_date', '<=', $form_date.' 00:00:00')->whereDate('report_date', '>=', $to_date.' 00:00:00');
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $adjustments = $query->select(
                    'stock_adjustments.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                )->orderBy('id', 'desc')->get();
            } else {
                $adjustments = $query->select(
                    'stock_adjustments.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'admin_and_users.prefix',
                    'admin_and_users.name',
                    'admin_and_users.last_name',
                )->where('stock_adjustments.branch_id', auth()->user()->branch_id)
                    ->get();
            }

            return DataTables::of($adjustments)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="'.route('stock.adjustments.show', [$row->id]).'"><i class="far fa-eye text-primary"></i> View</a>';

                    if (auth()->user()->permission->s_adjust['adjustment_delete'] == '1') {
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('stock.adjustments.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i> Delete
                        </a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) {
                    if ($row->branch_name) {
                        return $row->branch_name . '/' . $row->branch_code . '(<b>BRANCH</b>)';
                    } else {
                        return $row->warehouse_name . '/' . $row->warehouse_code . '(<b>WAREHOUSE</b>)';
                    }
                })
                ->editColumn('type',  function ($row) {
                    return $row->type == 1 ? '<span class="badge bg-primary">Normal</span>' : '<span class="badge bg-danger">Abnormal</span>';
                })
                ->editColumn('net_total', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->net_total_amount . '</b>';
                })
                ->editColumn('recovered_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->recovered_amount . '</b>';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->prefix . ' ' . $row->name . ' ' . $row->last_name;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('stock.adjustments.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'type', 'net_total', 'recovered_amount', 'created_by'])
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
        if (auth()->user()->permission->s_adjust['adjustment_add'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('stock_adjustment.create');
    }

    // Store Stock Adjustment
    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'type' => 'required',
        ]);

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $this->validate($request, [
                'warehouse_id' => 'required',
            ]);
        }

        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty.']);
        }

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // Add Stock adjustment.
        $addStockAdjustment = new StockAdjustment();
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $addStockAdjustment->warehouse_id = $request->warehouse_id;
        } else {
            $addStockAdjustment->branch_id = $request->branch_id;
        }

        $addStockAdjustment->invoice_id = $request->invoice_id ? $request->invoice_id : 'SAI' . date('dmy') . $invoiceId;
        $addStockAdjustment->type = $request->type;
        $addStockAdjustment->total_item = $request->total_item;
        $addStockAdjustment->net_total_amount = $request->net_total_amount;
        $addStockAdjustment->recovered_amount = $request->total_recodered_amount ? $request->total_recodered_amount : 0;
        $addStockAdjustment->date = $request->date;
        $addStockAdjustment->month = date('F');
        $addStockAdjustment->year = date('Y');
        $addStockAdjustment->report_date_ts = date('Y-m-d', strtotime($request->date));
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

            // Update product Qty
            $product = Product::where('id', $product_id)->first();
            $product->quantity -= $quantities[$index];
            $product->total_adjusted += $quantities[$index];
            $product->save();

            // Update product variant if variant is exists
            if ($variant_ids[$index] != 'noid') {
                $productVariant = ProductVariant::where('id', $variant_ids[$index])
                    ->where('product_id', $product_id)->first();
                $productVariant->variant_quantity -= $quantities[$index];
                $productVariant->total_adjusted += $quantities[$index];
                $productVariant->save();
            }

            //Update product branch qty
            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $productWarehouse = ProductWarehouse::where('warehouse_id', $request->warehouse_id)->where('product_id', $product_id)->first();
                if ($productWarehouse) {
                    $productWarehouse->product_quantity -= $quantities[$index];
                    $productWarehouse->save();

                    // Update product branch variant qty if variant is exists. 
                    if ($variant_ids[$index] != 'noid') {
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
                            ->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                        $productWarehouseVariant->variant_quantity -= $quantities[$index];
                        $productWarehouseVariant->save();
                    }
                }
            } else {
                $productBranch = ProductBranch::where('branch_id', $request->branch_id)->where('product_id', $product_id)->first();
                if ($productBranch) {
                    $productBranch->product_quantity -= $quantities[$index];
                    $productBranch->save();

                    // Update product branch variant qty if variant is exists. 
                    if ($variant_ids[$index] != 'noid') {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                            ->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                        $productBranchVariant->variant_quantity -= $quantities[$index];
                        $productBranchVariant->save();
                    }
                }
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
        ])
            ->where('id', $adjustmentId)->first();
        if (!is_null($deleteAdjustment)) {
            foreach ($deleteAdjustment->adjustment_products as $adjustment_product) {
                // Update product qty for adjustment
                $adjustment_product->product->quantity += $adjustment_product->quantity;
                $adjustment_product->product->total_adjusted -= $adjustment_product->quantity;
                $adjustment_product->product->save();

                // Update product vairant qty for adjustment if variant exists
                if ($adjustment_product->product_variant_id) {
                    $adjustment_product->variant->variant_quantity += $adjustment_product->quantity;
                    $adjustment_product->variant->total_adjusted -= $adjustment_product->quantity;
                    $adjustment_product->variant->save();
                }

                // Update product branch qty for adjustment
                $productBranch = ProductBranch::where('branch_id', $deleteAdjustment->branch_id)
                    ->where('product_id', $adjustment_product->product_id)->first();
                if ($productBranch) {
                    $productBranch->product_quantity += $adjustment_product->quantity;
                    $productBranch->save();

                    if ($adjustment_product->product_variant_id) {
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $adjustment_product->product_id)->where('product_variant_id', $adjustment_product->product_variant_id)->first();
                        $productBranchVariant->variant_quantity += $adjustment_product->quantity;
                        $productBranchVariant->save();
                    }
                }
            }
            $deleteAdjustment->delete();
        }
        return response()->json('Stock adjustment deleted successfully.');
    }

    // Search product
    public function searchProduct($keyword, $branch_id)
    {
        $namedProducts = Product::with(['product_variants', 'tax', 'unit'])->where('name', 'LIKE', $keyword . '%')->where('status', 1)->get();
        if ($namedProducts->count() > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        }

        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $keyword)->first();
        if ($product) {
            $productBranch = ProductBranch::where('branch_id', $branch_id)->where('product_id', $product->id)->first();
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
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $keyword)->first();
            if ($variant_product) {
                $productBranch = ProductBranch::where('branch_id', $branch_id)->where('product_id', $variant_product->product_id)->first();

                if (is_null($productBranch)) {
                    return response()->json(['errorMsg' => 'This product is not available in this shop']);
                }

                $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $variant_product->product_id)->where('product_variant_id', $variant_product->id)->first();

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
        $namedProducts = Product::with(['product_variants', 'tax', 'unit'])->where('name', 'LIKE', $keyword . '%')->where('status', 1)->get();
        if ($namedProducts->count() > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        }

        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $keyword)->first();
        if ($product) {
            $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $product->id)->first();
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
                        return response()->json([
                            'variant_product' => $variant_product,
                            'qty_limit' => $productWarehouseVariant->variant_quantity
                        ]);
                    } else {
                        return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
                    }
                } else {
                    return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
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

    public function checkSingleProductStock($product_id, $branch_id)
    {
        $productBranch = ProductBranch::where('product_id', $product_id)->where('branch_id', $branch_id)->first();
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

    public function checkVariantProductStockInWarehouse($product_id, $variant_id, $warehouse_id)
    {
        $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();
        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)->first();
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

    public function checkVariantProductStock($product_id, $variant_id, $branch_id)
    {
        $productBranch = ProductBranch::where('branch_id', $branch_id)->where('product_id', $product_id)->first();
        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)->first();
        if ($productBranch && $productBranchVariant) {
            if ($productBranchVariant->variant_quantity > 0) {
                return response()->json($productBranchVariant->variant_quantity);
            } else {
                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this shop']);
            }
        } else {
            return response()->json(['errorMsg' => 'This variant is not available in this shop.']);
        }
    }
}