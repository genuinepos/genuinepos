<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use App\Models\ProductBranchVariant;
use App\Models\ProductWarehouseVariant;
use App\Models\TransferStockToWarehouse;
use App\Models\TransferStockToWarehouseProduct;

class TransferToWarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Index view of Transfer stock to branch 
    public function index()
    {
        return view('transfer_stock.branch_to_warehouse.index');
    }

    // Get all transfers requested by ajax
    public function allTransfer()
    {
        $transferStocks = TransferStockToWarehouse::with('branch', 'warehouse')->orderBy('id', 'desc')->where('branch_id', auth()->user()->branch_id)->get();
        return view('transfer_stock.branch_to_warehouse.ajax_view.transfer_list', compact('transferStocks'));
    }

    // Transfer products by transfer id **requested by ajax
    public function transferProduct($transferId)
    {
        $transferProducts = TransferStockToWarehouseProduct::with(['product', 'variant'])->where('transfer_stock_id',$transferId)->get();
        return response()->json($transferProducts);
    }

    // Add transfer stock to branch create view
    public function create()
    {
        return view('transfer_stock.branch_to_warehouse.create');
    }

    // Store transfer products form warehouse to branch
    public function store(Request $request)
    {
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

        $addTransferToWarehouse = new TransferStockToWarehouse();
        $addTransferToWarehouse->invoice_id = $request->invoice_id ? $request->invoice_id : 'TSB'.date('dmy').$invoiceId;
        $addTransferToWarehouse->warehouse_id = $request->warehouse_id;
        $addTransferToWarehouse->branch_id = $request->branch_id;
        $addTransferToWarehouse->status = 1;
        $addTransferToWarehouse->total_item = $request->total_item;
        $addTransferToWarehouse->total_send_qty = $request->total_send_quantity;
        $addTransferToWarehouse->net_total_amount = $request->net_total_amount;
        $addTransferToWarehouse->shipping_charge = $request->shipping_charge;
        $addTransferToWarehouse->date = $request->date;
        $addTransferToWarehouse->report_date = date('Y-m-d', strtotime($request->date));
        $addTransferToWarehouse->month = date('F');
        $addTransferToWarehouse->year = date('Y');
        $addTransferToWarehouse->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $units = $request->units;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $quantities = $request->quantities;

        // Add transfer stock to branch products
        $index2 = 0;
        foreach($product_ids as $product_id){
            $variant_id = $variant_ids[$index2] != 'noid' ? $variant_ids[$index2] : NULL;
            $addTransferStockToWarehouseProduct = new TransferStockToWarehouseProduct();
            $addTransferStockToWarehouseProduct->transfer_stock_id = $addTransferToWarehouse->id;
            $addTransferStockToWarehouseProduct->product_id = $product_id;
            $addTransferStockToWarehouseProduct->product_variant_id = $variant_id;
            $addTransferStockToWarehouseProduct->unit = $units[$index2];
            $addTransferStockToWarehouseProduct->unit_price = $unit_prices[$index2];
            $addTransferStockToWarehouseProduct->quantity = $quantities[$index2];
            $addTransferStockToWarehouseProduct->subtotal = $subtotals[$index2];
            $addTransferStockToWarehouseProduct->save();
            $index2++;
        }

        if($request->action == 'save'){
            session()->flash('successMsg', 'Successfully transfer stock is added');
            return response()->json(['successMsg' => 'Successfully transfer stock is added']);
        }else{
           $transferStock = TransferStockToWarehouse::with('branch', 'warehouse', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')->where('id', $addTransferToWarehouse->id)->first();
           return response()->json($transferStock);
        }
    }

    // Transfer stock edit view
    public function edit($transferId)
    {
        $transferId = $transferId;
        return view('transfer_stock.branch_to_warehouse.edit', compact('transferId'));
    }

    // Get editable transfer **reqeusted by ajax
    public function editableTransfer($transferId)
    {
        $transfer = TransferStockToWarehouse::with('warehouse', 'branch', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')->where('id', $transferId)->first();
        $qty_limits = [];
        foreach ($transfer->Transfer_products as $transfer_product) {
            $productBranch = ProductBranch::where('branch_id', $transfer->branch_id)
            ->where('product_id', $transfer_product->product_id)->first();
            if ($transfer_product->product_variant_id) {
                $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $transfer_product->product_id)
                ->where('product_variant_id', $transfer_product->product_variant_id)
                ->first();
                $qty_limits[] = $productBranchVariant->variant_quantity;
            }else {
                $qty_limits[] = $productBranch->product_quantity;  
            }
        }

        return response()->json(['transfer' => $transfer, 'qty_limits' => $qty_limits]);
    }

    // Update Transfer to branch
    public function update(Request $request, $transferId)
    {
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

        $updateTransferToWarehouse = TransferStockToWarehouse::with('transfer_products')->where('id', $transferId)->first();

        // Update is delete in update status
        foreach($updateTransferToWarehouse->transfer_products as $transfer_product){
            $transfer_product->is_delete_in_update = 1;
            $transfer_product->save();
        }

        $updateTransferToWarehouse->invoice_id = $request->invoice_id ? $request->invoice_id : 'TSW'.date('dmy').$invoiceId;
        $updateTransferToWarehouse->warehouse_id = $request->warehouse_id;
        $updateTransferToWarehouse->branch_id = $request->branch_id;
        $updateTransferToWarehouse->total_item = $request->total_item;
        $updateTransferToWarehouse->total_send_qty = $request->total_send_quantity;
        
        if($request->total_send_quantity == $updateTransferToWarehouse->total_received_qty){
            $updateTransferToWarehouse->status = 3;
        }elseif($updateTransferToWarehouse->total_received_qty > 0 && $updateTransferToWarehouse->total_received_qty <        $request->total_send_quantity){
            $updateTransferToWarehouse->status = 2;
        }elseif($updateTransferToWarehouse->total_received_qty == 0 ){
            $updateTransferToWarehouse->status = 1;
        }
        $updateTransferToWarehouse->net_total_amount = $request->net_total_amount;
        $updateTransferToWarehouse->shipping_charge = $request->shipping_charge;
        $updateTransferToWarehouse->additional_note = $request->additional_note;
        $updateTransferToWarehouse->date = $request->date;
        $updateTransferToWarehouse->report_date = date('Y-m-d', strtotime($request->date));
        $updateTransferToWarehouse->month = date('F');
        $updateTransferToWarehouse->year = date('Y');
        $updateTransferToWarehouse->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $units = $request->units;
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $quantities = $request->quantities;

        // Add transfer stock to branch products
        $index2 = 0;
        foreach($product_ids as $product_id){
            $variant_id = $variant_ids[$index2] != 'noid' ? $variant_ids[$index2] : NULL;
            $transferProduct = TransferStockToWarehouseProduct::where('transfer_stock_id', $updateTransferToWarehouse->id)->where('product_id')->where('product_variant_id', $variant_id)->first();
            if($transferProduct){
                $transferProduct->quantity = $quantities[$index2];
                $transferProduct->subtotal = $subtotals[$index2];
                $transferProduct->is_delete_in_update = 0;
                $transferProduct->save();
            }else{
                $addTransferStockToBranchProduct = new TransferStockToWarehouseProduct();
                $addTransferStockToBranchProduct->transfer_stock_id = $updateTransferToWarehouse->id;
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
        $deleteableTransferProducts = TransferStockToWarehouseProduct::where('transfer_stock_id', $transferId)->where('is_delete_in_update', 1)->get();
        foreach($deleteableTransferProducts as $deleteableTransferProduct){
            $deleteableTransferProduct->delete();
        }

        if($request->action == 'save'){
            session()->flash('successMsg', 'Successfully transfer stock is updated');
            return response()->json(['successMsg' => 'Successfully transfer stock is updated']);
        }else{
            $transferStock = TransferStockToWarehouse::with('warehouse', 'branch', 'Transfer_products', 'Transfer_products.product', 'Transfer_products.variant')->where('id', $transferId)->first();
            return response()->json($transferStock);
        }
    }

    // delete transfer
    public function delete($transferId)
    {
        $deleteTransferToWarehouse = TransferStockToWarehouse::with('transfer_products')->where('id', $transferId)->first();
        if (!is_null($deleteTransferToWarehouse)) {
            // Update warehouse qty if created transfer status is 2
            
            foreach($deleteTransferToWarehouse->transfer_products as $transfer_product){
                // update branch product qty for adjustment
                $productBranch = ProductBranch::where('branch_id', $deleteTransferToWarehouse->branch_id)->where('product_id', $transfer_product->product_id)->first();
                $productBranch->product_quantity += $transfer_product->received_qty;
                $productBranch->save();

                if($transfer_product->product_variant_id){
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $transfer_product->product_id)->where('product_variant_id', $transfer_product->product_variant_id)->first();
                    $productBranchVariant->variant_quantity += $transfer_product->received_qty;
                    $productBranchVariant->save();
                }

                // update warehouse product qty for adjustment
                $productWarehouse = ProductWarehouse::where('warehouse_id', $deleteTransferToWarehouse->warehouse_id)->where('product_id', $transfer_product->product_id)->first();
                $productWarehouse->product_quantity -= $transfer_product->received_qty;
                $productWarehouse->save();

                if($transfer_product->product_variant_id){
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $transfer_product->product_id)->where('product_variant_id', $transfer_product->product_variant_id)->first();
                    $productWarehouseVariant->variant_quantity -= $transfer_product->received_qty;
                    $productWarehouseVariant->save();
                }
            }
            
            $deleteTransferToWarehouse->delete();  
            return response()->json('Successfully transfer stock is deleted');
        }
    }

    // Product search by product code
    public function productSearch($product_code, $branch_id)
    {
        $product = Product::with(['product_variants', 'tax', 'unit'])->where('product_code', $product_code)->first();
        if ($product) {
            $productBranch = ProductBranch::where('branch_id', $branch_id)->where('product_id', $product->id)->first();
            if ($productBranch) {
                if ($product->type == 2) {
                    return response()->json(['errorMsg' => 'Combo product is not transferable.']);
                }else {
                    if ($productBranch->product_quantity > 0) {
                        return response()->json(['product' => $product, 'qty_limit' => $productBranch->product_quantity]);
                    }else {
                        return response()->json(['errorMsg' => 'Stock is out of this product of this warehouse']);
                    } 
                } 
            }else {
                return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
            }
        }else{
           $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $product_code)->first();
            if ($variant_product) {
               $productBranch = ProductBranch::where('branch_id', $branch_id)->where('product_id', $variant_product->product_id)->first();

                if(is_null($productBranch)){
                    return response()->json(['errorMsg' => 'This product is not available in this branch']);
                }

                $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $variant_product->product_id)->where('product_variant_id', $variant_product->id)->first();

                if(is_null($productBranchVariant)){
                    return response()->json(['errorMsg' => 'This variant is not available in this branch']);
                }

                if ($productBranch && $productBranchVariant) {
                    if ($productBranchVariant->variant_quantity > 0) {
                        return response()->json(['variant_product' => $variant_product, 'qty_limit' => $productBranchVariant->variant_quantity]);
                    }else {
                        return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this branch']);
                    }
                }else {
                    return response()->json(['errorMsg' => 'This product is not available in this branch.']);
                }
            }
        }
    }

     // Check branch product variant qty 
     public function checkBranchProductVariant($product_id, $variant_id, $branch_id)
     {
        $productBranch = ProductBranch::where('branch_id', $branch_id)->where('product_id', $product_id)->first();

        if(is_null($productBranch)){
            return response()->json(['errorMsg' => 'This product is not available in this branch']);
        }

        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $product_id)->where('product_variant_id', $variant_id)->first();

        if(is_null($productBranchVariant)){
            return response()->json(['errorMsg' => 'This variant is not available in this branch']);
        }

        if ($productBranch && $productBranchVariant) {
            if ($productBranchVariant->variant_quantity > 0) {
                return response()->json($productBranchVariant->variant_quantity);
            }else {
                return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this branch']);
            }
         }else {
             return response()->json(['errorMsg' => 'This variant is not available in this shop.']);
         }
     }

    // Get all warehouse requested by ajax
    public function getAllWarehouse()
    {
        $warehouses = Warehouse::select('id', 'warehouse_name', 'warehouse_code')->get();
        return response()->json($warehouses);
    }
}
