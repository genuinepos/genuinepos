<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\TransferStockToBranch;
use Illuminate\Support\Facades\Cache;
use App\Models\ProductWarehouseVariant;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransferStockToBranchProduct;

class TransferToBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Transfer stock to branch 
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $transfers = DB::table('transfer_stock_to_branches')
                ->leftJoin('warehouses', 'transfer_stock_to_branches.warehouse_id', 'warehouses.id')
                ->leftJoin('branches', 'transfer_stock_to_branches.branch_id', 'branches.id')->select(
                    'transfer_stock_to_branches.*',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'branches.name as branch_name',
                    'branches.branch_code',
                )->orderBy('id', 'desc')->get();


            return DataTables::of($transfers)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="#"><i class="far fa-eye mr-1 text-primary"></i>View</a>';
                    $html .= '<a class="dropdown-item" href="' . route('transfer.stock.to.branch.edit', $row->id) . '"><i class="far fa-edit mr-1 text-primary"></i>Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="' . route('transfer.stock.to.branch.delete', $row->id) . '"><i class="far fa-trash-alt mr-1 text-primary"></i>Delete</a>';

                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('from',  function ($row) {
                    return  $row->warehouse_name . '/' . $row->warehouse_code;
                })
                ->editColumn('to',  function ($row) {
                    return  $row->branch_name . '/' . $row->branch_code;
                })
                ->editColumn('shipping_charge', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->shipping_charge . '</b>';
                })
                ->editColumn('net_total_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . $row->net_total_amount  . '</b>';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == 1) {
                        $html .= '<span class="badge bg-danger">Pending</span>';
                    } else if ($row->status == 2) {
                        $html .= '<span class="badge bg-warning text-white">Partial</span>';
                    } else if ($row->status == 3) {
                        $html .= '<span class="badge bg-success">Completed</span>';
                    }
                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('transfer.stock.to.branch.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row text-start')
                ->rawColumns(['date', 'from', 'to', 'shipping_charge', 'net_total_amount', 'status', 'action'])
                ->make(true);
        }
        return view('transfer_stock.warehouse_to_branch.index');
    }

    public function show($transferId)
    {
        $transfer = TransferStockToBranch::with('warehouse', 'branch', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')->where('id', $transferId)->first();
        return view('transfer_stock.warehouse_to_branch.ajax_view.show', compact('transfer'));
    }

    // Transfer products by transfer id **requested by ajax
    public function transferProduct($transferId)
    {
        $transferProducts = TransferStockToBranchProduct::with(['product', 'variant'])->where('transfer_stock_id', $transferId)->get();
        return response()->json($transferProducts);
    }

    // Add transfer stock to branch create view
    public function create()
    {
        return view('transfer_stock.warehouse_to_branch.create');
    }

    // Store transfer products form warehouse to branch
    public function store(Request $request)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['stock_transfer'];
        //return $request->all();
        $this->validate($request, [
            'warehouse_id' => 'required',
            'branch_id' => 'required',
        ]);

        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $addTransferToBranch = new TransferStockToBranch();
        $addTransferToBranch->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'TSB') . date('ymd') . $invoiceId;
        $addTransferToBranch->warehouse_id = $request->warehouse_id;
        $addTransferToBranch->branch_id = $request->branch_id;
        $addTransferToBranch->total_item = $request->total_item;
        $addTransferToBranch->total_send_qty = $request->total_send_quantity;
        $addTransferToBranch->net_total_amount = $request->net_total_amount;
        $addTransferToBranch->shipping_charge = $request->shipping_charge;
        $addTransferToBranch->date = $request->date;
        $addTransferToBranch->report_date = date('Y-m-d', strtotime($request->date));
        $addTransferToBranch->month = date('F');
        $addTransferToBranch->year = date('Y');
        $addTransferToBranch->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $units = $request->units;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $quantities = $request->quantities;

        // Add transfer stock to branch products
        $index2 = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$index2] != 'noid' ? $variant_ids[$index2] : NULL;
            $addTransferStockToBranchProduct = new TransferStockToBranchProduct();
            $addTransferStockToBranchProduct->transfer_stock_id = $addTransferToBranch->id;
            $addTransferStockToBranchProduct->product_id = $product_id;
            $addTransferStockToBranchProduct->product_variant_id = $variant_id;
            $addTransferStockToBranchProduct->unit = $units[$index2];
            $addTransferStockToBranchProduct->unit_price = $unit_prices[$index2];
            $addTransferStockToBranchProduct->quantity = $quantities[$index2];
            $addTransferStockToBranchProduct->subtotal = $subtotals[$index2];
            $addTransferStockToBranchProduct->save();
            $index2++;
        }

        if ($request->action == 'save') {
            Cache::forget('all-products');
            session()->flash('successMsg', 'Successfully transfer stock is added');
            return response()->json(['successMsg' => 'Successfully transfer stock is added']);
        } else {
            Cache::forget('all-products');
            $transfer = TransferStockToBranch::with('warehouse', 'branch', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')->where('id', $addTransferToBranch->id)->first();
            return view('transfer_stock.warehouse_to_branch.save_and_print_template.print', compact('transfer'));
        }
    }

    // Transfer stock edit view
    public function edit($transferId)
    {
        $transferId = $transferId;
        $transfer = DB::table('transfer_stock_to_branches')->where('id', $transferId)->select('id', 'warehouse_id', 'branch_id', 'date')->first();
        $warehouses = DB::table('warehouses')
            ->select('id', 'warehouse_name', 'warehouse_code')->get();
        $branches = DB::table('branches')
        ->select('id', 'name', 'branch_code')->get();
        return view('transfer_stock.warehouse_to_branch.edit', compact('transferId', 'transfer', 'warehouses', 'branches'));
    }

    // Get editable transfer **reqeusted by ajax
    public function editableTransfer($transferId)
    {
        $transfer = TransferStockToBranch::with('warehouse', 'branch', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')->where('id', $transferId)->first();

        $qty_limits = [];
        foreach ($transfer->Transfer_products as $transfer_product) {
            $productWarehouse = ProductWarehouse::where('warehouse_id', $transfer->warehouse_id)
                ->where('product_id', $transfer_product->product_id)->first();
            if ($transfer_product->product_variant_id) {
                $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $transfer_product->product_id)
                    ->where('product_variant_id', $transfer_product->product_variant_id)
                    ->first();
                $qty_limits[] = $productWarehouseVariant->variant_quantity;
            } else {
                $qty_limits[] = $productWarehouse->product_quantity;
            }
        }

        return response()->json(['transfer' => $transfer, 'qty_limits' => $qty_limits]);
    }

    // Update Transfer to branch
    public function update(Request $request, $transferId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['stock_transfer'];
        //return $request->all();
        $this->validate($request, [
            'warehouse_id' => 'required',
            'branch_id' => 'required',
        ]);

        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $updateTransferToBranch = TransferStockToBranch::with('transfer_products')->where('id', $transferId)->first();

        // Update is delete in update status
        foreach ($updateTransferToBranch->transfer_products as $transfer_product) {
            $transfer_product->is_delete_in_update = 1;
            $transfer_product->save();
        }

        $updateTransferToBranch->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'TSB') . date('ymd') . $invoiceId;
        $updateTransferToBranch->warehouse_id = $request->warehouse_id;
        $updateTransferToBranch->branch_id = $request->branch_id;
        $updateTransferToBranch->total_item = $request->total_item;
        $updateTransferToBranch->total_send_qty = $request->total_send_quantity;

        if ($request->total_send_quantity == $updateTransferToBranch->total_received_qty) {
            $updateTransferToBranch->status = 3;
        } elseif ($updateTransferToBranch->total_received_qty > 0 && $updateTransferToBranch->total_received_qty <        $request->total_send_quantity) {
            $updateTransferToBranch->status = 2;
        } elseif ($updateTransferToBranch->total_received_qty == 0) {
            $updateTransferToBranch->status = 1;
        }

        $updateTransferToBranch->net_total_amount = $request->net_total_amount;
        $updateTransferToBranch->shipping_charge = $request->shipping_charge;
        $updateTransferToBranch->additional_note = $request->additional_note;
        $updateTransferToBranch->date = $request->date;
        $updateTransferToBranch->report_date = date('Y-m-d', strtotime($request->date));
        $updateTransferToBranch->month = date('F');
        $updateTransferToBranch->year = date('Y');
        $updateTransferToBranch->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $units = $request->units;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $quantities = $request->quantities;

        // Add transfer stock to branch products
        $index2 = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$index2] != 'noid' ? $variant_ids[$index2] : NULL;
            $transferProduct = TransferStockToBranchProduct::where('transfer_stock_id', $updateTransferToBranch->id)->where('product_id')->where('product_variant_id', $variant_id)->first();
            if ($transferProduct) {
                $transferProduct->quantity = $quantities[$index2];
                $transferProduct->subtotal = $subtotals[$index2];
                $transferProduct->is_delete_in_update = 0;
                $transferProduct->save();
            } else {
                $addTransferStockToBranchProduct = new TransferStockToBranchProduct();
                $addTransferStockToBranchProduct->transfer_stock_id = $updateTransferToBranch->id;
                $addTransferStockToBranchProduct->product_id = $product_id;
                $addTransferStockToBranchProduct->product_variant_id = $variant_id;
                $addTransferStockToBranchProduct->unit = $units[$index2];
                $addTransferStockToBranchProduct->unit_price = $unit_prices[$index2];
                $addTransferStockToBranchProduct->quantity = $quantities[$index2];
                $addTransferStockToBranchProduct->subtotal = $subtotals[$index2];
                $addTransferStockToBranchProduct->save();
            }
            $index2++;
        }

        // Delete not found which was previous
        $deleteableTransferProducts = TransferStockToBranchProduct::where('transfer_stock_id', $transferId)->where('is_delete_in_update', 1)->get();
        foreach ($deleteableTransferProducts as $deleteableTransferProduct) {
            $deleteableTransferProduct->delete();
        }

        session()->flash('successMsg', 'Successfully transfer stock is updated');
        return response()->json(['successMsg' => 'Successfully transfer stock is updated']);
        
    }

    // delete transfer
    public function delete($transferId)
    {
        $deleteTransferToBranch = TransferStockToBranch::with('transfer_products')->where('id', $transferId)->first();
        if (!is_null($deleteTransferToBranch)) {
            // Update warehouse qty if created transfer status is 2

            foreach ($deleteTransferToBranch->transfer_products as $transfer_product) {
                // update warehouse product qty for adjustment
                $productWarehouse = ProductWarehouse::where('warehouse_id', $deleteTransferToBranch->warehouse_id)->where('product_id', $transfer_product->product_id)->first();
                $productWarehouse->product_quantity += $transfer_product->received_qty;
                $productWarehouse->save();

                if ($transfer_product->product_variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $transfer_product->product_id)->where('product_variant_id', $transfer_product->product_variant_id)->first();
                    $productWarehouseVariant->variant_quantity += $transfer_product->received_qty;
                    $productWarehouseVariant->save();
                }

                // update branch product qty for adjustment
                $productBranch = ProductBranch::where('branch_id', $deleteTransferToBranch->branch_id)->where('product_id', $transfer_product->product_id)->first();
                $productBranch->product_quantity -= $transfer_product->received_qty;
                $productBranch->save();

                if ($transfer_product->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $transfer_product->product_id)->where('product_variant_id', $transfer_product->product_variant_id)->first();
                    $productBranchVariant->variant_quantity -= $transfer_product->received_qty;
                    $productBranchVariant->save();
                }
            }

            $deleteTransferToBranch->delete();
            return response()->json('Successfully transfer stock is deleted');
        }
    }

    // Product search by product code
    public function productSearch($product_code, $warehouse_id)
    {
        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $product_code)->first();
        if ($product) {
            $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $product->id)->first();
            if ($productWarehouse) {
                if ($product->type == 2) {
                    return response()->json(['errorMsg' => 'Combo product is not transferable.']);
                } else {
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

        $namedProducts = '';
        $nameSearch = Product::with(['product_variants', 'tax', 'unit'])
            ->where('name', 'LIKE', '%' . $product_code . '%')
            ->where('status', 1)
            ->get();

        if (count($nameSearch) > 0) {
            $namedProducts = $nameSearch;
        }

        $priceSearch = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_price', 'like', "%$product_code%")
            ->where('status', 1)
            ->get();

        if (count($priceSearch) > 0) {
            $namedProducts = $priceSearch;
        }

        if ($namedProducts && $namedProducts->count() > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        } else {
            return response()->json(['NotFoundMsg' => 'Not Found.']);
        }
    }

    public function checkWarehouseSingleProduct($product_id, $warehouse_id)
    {
        $productWarehouse = ProductWarehouse::where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();
        if ($productWarehouse) {
            if ($productWarehouse->product_quantity > 0) {
                return response()->json($productWarehouse->product_quantity);
            } else {
                return response()->json(['errorMsg' => 'Stock is out of this product from this warehouse']);
            }
        } else {
            return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
        }
    }

    // Check warehouse product variant qty 
    public function checkWarehouseProductVariant($product_id, $variant_id, $warehouse_id)
    {
        $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();

        if (is_null($productWarehouse)) {
            return response()->json(['errorMsg' => 'This product is not available in this warehouse']);
        }

        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $product_id)->where('product_variant_id', $variant_id)->first();

        if (is_null($productWarehouseVariant)) {
            return response()->json(['errorMsg' => 'This variant is not available in this warehouse']);
        }

        if ($productWarehouse && $productWarehouseVariant) {
            if ($productWarehouseVariant->variant_quantity > 0) {
                return response()->json($productWarehouseVariant->variant_quantity);
            } else {
                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
            }
        } else {
            return response()->json(['errorMsg' => 'This variant is not available in this shop.']);
        }
    }

    // Get all warehouse requested by ajax
    public function getAllWarehouse()
    {
        $warehouses = Cache::rememberForever('all-warehouses', function () {
            return $warehouses = Warehouse::select('id', 'warehouse_name', 'warehouse_code')->get();
        });
        return response()->json($warehouses);
    }
}
