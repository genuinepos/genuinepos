<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\TransferStockToBranch;
use App\Models\ProductWarehouseVariant;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TransferStockToBranchProduct;

class BranchReceiveStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    //Branch receiving stock index view
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $transfers = DB::table('transfer_stock_to_branches')
                ->leftJoin('warehouses', 'transfer_stock_to_branches.warehouse_id', 'warehouses.id')
                ->leftJoin('branches', 'transfer_stock_to_branches.branch_id', 'branches.id')->select(
                    'transfer_stock_to_branches.*',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'branches.name as branch_name',
                    'branches.branch_code',
                )->orderBy('id', 'desc')->where('branch_id', auth()->user()->branch_id)->get();

            return DataTables::of($transfers)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="'.route('transfer.stocks.to.warehouse.receive.stock.show', [$row->id]).'"><i class="far fa-eye mr-1 text-primary"></i> View</a>';
                    $html .= '<a class="dropdown-item" href="' . route('transfer.stocks.to.warehouse.receive.stock.process.view', [$row->id] ) . '"><i class="far fa-edit mr-1 text-primary"></i> Process To Receive</a>';
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
                        return route('transfer.stocks.to.warehouse.receive.stock.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row text-start')
                ->rawColumns(['date', 'from', 'to', 'status', 'action'])
                ->make(true);
        }
        return view('transfer_stock.branch_to_warehouse.receive_stock.index');
    }

    public function show($sendStockId)
    {
        $sendStock = TransferStockToBranch::with(['warehouse', 'branch', 'transfer_products', 'transfer_products.product', 'transfer_products.variant'])->where('id', $sendStockId)->first();
        return view('transfer_stock.branch_to_warehouse.receive_stock.ajax_view.show', compact('sendStock'));
    }

    public function receiveProducessView($sendStockId)
    {
        $sendStockId = $sendStockId;
        return view('transfer_stock.branch_to_warehouse.receive_stock.product_receive_stock_view', compact('sendStockId'));
    
    }

    public function receivableStock($sendStockId)
    {
        $sandStocks = TransferStockToBranch::with(['warehouse', 'branch', 'transfer_products', 'transfer_products.product', 'transfer_products.variant'])
            ->where('id', $sendStockId)->first();
        return response()->json($sandStocks);
    }

    public function receiveProcessSave(Request $request, $sendStockId)
    {
        //return $request->all();
        $updateSandStocks = TransferStockToBranch::where('id', $sendStockId)->first();
        $updateSandStocks->total_received_qty = $request->total_received_quantity;

        $status = 0;
        if ($request->total_received_quantity == 0) {
            $status = 1;
        } elseif ($request->total_received_quantity > 0 && $updateSandStocks->total_send_qty == $request->total_received_quantity) {
            $status = 3;
        } elseif ($request->total_received_quantity > 0 && $request->total_received_quantity < $updateSandStocks->total_send_qty) {
            $status = 2;
        }

        $updateSandStocks->status = $status;
        $updateSandStocks->receiver_note = $request->receiver_note;
        $updateSandStocks->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $receive_quantities = $request->receive_quantities;
        $previous_received_quantities = $request->previous_received_quantities;


        $index = 0;
        foreach ($product_ids as $product_id) {
            // Update warehouse and branch qty for adjustment
            $productWarehouse = ProductWarehouse::where('warehouse_id', $updateSandStocks->warehouse_id)->where('product_id', $product_id)->first();
            $productWarehouse->product_quantity += $previous_received_quantities[$index];
            $productWarehouse->save();

            if ($variant_ids[$index] != 'noid') {
                $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                $productWarehouseVariant->variant_quantity += $previous_received_quantities[$index];
                $productWarehouseVariant->save();
            }

            $productBranch = ProductBranch::where('branch_id', $updateSandStocks->branch_id)->where('product_id', $product_id)->first();
            if ($productBranch) {
                $productBranch->product_quantity -= $previous_received_quantities[$index];
                $productBranch->save();

                if ($variant_ids[$index] != 'noid') {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                    if ($productBranchVariant) {
                        $productBranchVariant->variant_quantity -= $previous_received_quantities[$index];
                        $productBranchVariant->save();
                    }
                }
            }

            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $updateTransferProduct = TransferStockToBranchProduct::where('transfer_stock_id', $updateSandStocks->id)->where('product_id', $product_id)->where('product_variant_id', $variant_id)->first();
            $updateTransferProduct->received_qty = $receive_quantities[$index];
            $updateTransferProduct->save();

            // Update warehouse and branch qty 
            $productWarehouse = ProductWarehouse::where('warehouse_id', $updateSandStocks->warehouse_id)->where('product_id', $product_id)->first();
            $productWarehouse->product_quantity -= $receive_quantities[$index];
            $productWarehouse->save();

            if ($variant_ids[$index] != 'noid') {
                $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                $productWarehouseVariant->variant_quantity -= $receive_quantities[$index];
                $productWarehouseVariant->save();
            }

            $productBranch = ProductBranch::where('branch_id', $updateSandStocks->branch_id)->where('product_id', $product_id)->first();
            if ($productBranch) {
                $productBranch->product_quantity += $receive_quantities[$index];
                $productBranch->save();
                if ($variant_ids[$index] != 'noid') {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                    if ($productBranchVariant) {
                        $productBranchVariant->variant_quantity += $receive_quantities[$index];
                        $productBranchVariant->save();
                    } else {
                        $addBranchProductVariant = new ProductBranchVariant();
                        $addBranchProductVariant->product_branch_id = $productBranch->id;
                        $addBranchProductVariant->product_id = $product_id;
                        $addBranchProductVariant->product_variant_id = $variant_ids[$index];
                        $addBranchProductVariant->variant_quantity = $receive_quantities[$index];
                        $addBranchProductVariant->save();
                    }
                }
            } else {
                $addBranchProduct = new ProductBranch();
                $addBranchProduct->branch_id = $updateSandStocks->branch_id;
                $addBranchProduct->product_id = $product_id;
                $addBranchProduct->product_quantity = $receive_quantities[$index];
                $addBranchProduct->save();
                if ($variant_ids[$index] != 'noid') {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $addBranchProduct->id)->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                    if ($productBranchVariant) {
                        $productBranchVariant->variant_quantity += $receive_quantities[$index];
                        $productBranchVariant->save();
                    } else {
                        $addBranchProductVariant = new ProductBranchVariant();
                        $addBranchProductVariant->product_branch_id = $addBranchProduct->id;
                        $addBranchProductVariant->product_id = $product_id;
                        $addBranchProductVariant->product_variant_id = $variant_ids[$index];
                        $addBranchProductVariant->variant_quantity = $receive_quantities[$index];
                        $addBranchProductVariant->save();
                    }
                }
            }
            $index++;
        }

        session()->flash('successMsg', 'Successfully receiving has been has been processed');
        return response()->json(['successMsg' => 'Successfully receiving has been has been processed']);
    }
}
