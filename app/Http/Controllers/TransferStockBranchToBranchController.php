<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Utils\NameSearchUtil;
use App\Models\ProductVariant;
use App\Utils\TransferStockUtil;
use Illuminate\Support\Facades\DB;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Models\TransferStockBranchToBranch;
use App\Models\TransferStockBranchToBranchProducts;

class TransferStockBranchToBranchController extends Controller
{
    protected $nameSearchUtil;
    protected $invoiceVoucherRefIdUtil;
    protected $transferStockUtil;

    public function __construct(
        NameSearchUtil $nameSearchUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        TransferStockUtil $transferStockUtil
    )
    {
        $this->nameSearchUtil = $nameSearchUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->transferStockUtil = $transferStockUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function transferList(Request $request)
    {
        if ($request->ajax()) {

            $settings = DB::table('general_settings')->select('id', 'business')->first();

            $transfers = '';

            $query = DB::table('transfer_stock_branch_to_branches')
                ->leftJoin('branches as sender_branch', 'transfer_stock_branch_to_branches.sender_branch_id', 'sender_branch.id')
                ->leftJoin('branches as receiver_branch', 'transfer_stock_branch_to_branches.receiver_branch_id', 'receiver_branch.id');

                if ($request->branch_id) {

                    if ($request->branch_id == 'NULL') {

                        $query->where('transfer_stock_branch_to_branches.sender_branch_id', NULL);
                    } else {
                        
                        $query->where('transfer_stock_branch_to_branches.sender_branch_id', $request->branch_id);
                    }
                }

                // if ($request->status) {

                //     $query->where('transfer_stock_branch_to_branches.status', $request->status);
                // }
        
                if ($request->from_date) {

                    $from_date = date('Y-m-d', strtotime($request->from_date));
                    $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                    // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                    $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                    $query->whereBetween('transfer_stock_branch_to_branches.report_date', $date_range); // Final
                }

                if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                    
                    $transfers = $query->orderBy('transfer_stock_branch_to_branches.report_date');

                }
        }

        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')->get();

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view(

            'transfer_stock.branch_to_branch.transfer_list',

            compact('warehouses', 'branches')
        );
    }

    public function create()
    {
        $accounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance']);

        $expenseAccounts = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('account_branches.branch_id', auth()->user()->branch_id)
            ->whereIn('accounts.account_type', [7, 8, 9, 10, 15])
            ->orderBy('accounts.account_type', 'asc')
            ->get(['accounts.id', 'accounts.name', 'account_type']);

        $methods = DB::table('payment_methods')->select('id', 'name', 'account_id')->get();

        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')->get();

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();

        return view(

            'transfer_stock.branch_to_branch.create',

            compact('warehouses', 'accounts', 'expenseAccounts', 'methods', 'branches')
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'ex_account_id' => 'required',
            'account_id' => 'required',
            'payment_method_id' => 'required',
            'receiver_branch_id' => 'required',
        ], [
            'ex_account_id.required' => 'Expense ledger A/C is required',
            'account_id.required' => 'Credit A/C is required',
            'receiver_branch_id.required' => 'Receive from field is required',
        ]);

        $receiver_branch_id = $request->receiver_branch_id != 'NULL' ? $request->receiver_branch_id : NULL;

        $refId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('transfer_stock_branch_to_branches'), 5, "0", STR_PAD_LEFT);

        $addTransfer = new TransferStockBranchToBranch();
        $addTransfer->ref_id = 'TBB'.$refId;
        $addTransfer->sender_branch_id = $request->sender_branch_id;
        $addTransfer->sender_warehouse_id = $request->sender_warehouse_id;
        $addTransfer->receiver_branch_id = $receiver_branch_id;
        $addTransfer->date = $request->date;
        $addTransfer->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addTransfer->total_item = $request->total_item;
        $addTransfer->total_send_qty = $request->total_send_qty;
        $addTransfer->total_pending_qty = $request->total_send_qty;
        $addTransfer->total_stock_value = $request->total_stock_value;
        $addTransfer->transfer_note = $request->transfer_note;
        $addTransfer->expense_account_id = $request->ex_account_id;
        $addTransfer->bank_account_id = $request->account_id;
        $addTransfer->transfer_cost = $request->transfer_cost;
        $addTransfer->save();

        if (count($request->product_ids) > 0) {

            $index = 0;
            foreach ($request->product_ids as $product_id) {
                
                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : NULL;

                $addTransProduct = New TransferStockBranchToBranchProducts();
                $addTransProduct->transfer_id = $addTransfer->id;
                $addTransProduct->product_id = $product_id;
                $addTransProduct->variant_id = $variant_id;
                $addTransProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $addTransProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
                $addTransProduct->send_qty = $request->quantities[$index];
                $addTransProduct->pending_qty = $request->quantities[$index];
                $addTransProduct->subtotal = $request->subtotals[$index];
                $addTransProduct->save();
                $index++;
            }
        }
     
        if($request->transfer_cost > 0){

            $this->transferStockUtil->addExpenseFromTransferStock($request, $addTransfer->id);
        }

        if ($request->action == 'save') {

            return response()->json(['successMsg' => 'Successfully transfer stock is added']);
        } else {

            $transfer = TransferStockBranchToBranch::with(
                [
                    'sender_branch',
                    'sender_warehouse',
                    'receiver_branch',
                    'receiver_branch',
                    'Transfer_products', 
                    'Transfer_products.product', 
                    'Transfer_products.variant',
                    'Transfer_products.product.unit'
                ]
            )->where('id', $addTransfer->id)->first();

            return view('transfer_stock.branch_to_branch.save_and_print_template.print', compact('transfer'));
        }
    }

    public function searchProduct($product_code, $warehouse_id)
    {
        $product_code = (string)$product_code;
        $__product_code = str_replace('~', '/', $product_code);
        $branch_id = auth()->user()->branch_id;

        $product = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_code', $__product_code)
            ->select([
                'id',
                'name',
                'product_code',
                'product_price',
                'profit',
                'product_cost_with_tax',
                'thumbnail_photo',
                'unit_id',
                'tax_id',
                'tax_type',
                'is_show_emi_on_pos',
            ])->first();

        if ($warehouse_id != 'no_id') {

            return $this->searchStockToWarehouse($product, $__product_code, $warehouse_id);
        } else {

            return $this->searchStockToBranch($product, $__product_code, $branch_id);
        }
    }

    private function searchStockToBranch($product, $product_code, $branch_id)
    {
        if ($product) {

            $productBranch = DB::table('product_branches')
                ->where('branch_id', $branch_id)
                ->where('product_id', $product->id)
                ->select('product_quantity')
                ->first();

            if ($productBranch) {

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo product is not transferable.']);
                } else {

                    if ($productBranch->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productBranch->product_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product in this Business Location/Shop.']);
                    }
                }
            } else {

                return response()->json(['errorMsg' => 'This product is not available in this Business Location/Shop.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->select([
                    'id', 'product_id', 'variant_name', 'variant_code', 'variant_quantity', 'variant_cost', 'variant_cost_with_tax', 'variant_profit', 'variant_price'
                ])->first();

            if ($variant_product) {

                if ($variant_product) {

                    $productBranch = DB::table('product_branches')
                        ->where('branch_id', $branch_id)
                        ->where('product_id', $variant_product->product_id)
                        ->first();

                    if (is_null($productBranch)) {

                        return response()->json(['errorMsg' => 'This product is not available in this Business Location/Shop.']);
                    }

                    $productBranchVariant = DB::table('product_branch_variants')
                        ->where('product_branch_id', $productBranch->id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('product_variant_id', $variant_product->id)
                        ->select('variant_quantity')
                        ->first();

                    if (is_null($productBranchVariant)) {

                        return response()->json(['errorMsg' => 'This variant is not available in this Business Location/Shop.']);
                    }

                    if ($productBranch && $productBranchVariant) {

                        if ($productBranchVariant->variant_quantity > 0) {

                            return response()->json([
                                'variant_product' => $variant_product,
                                'qty_limit' => $productBranchVariant->variant_quantity
                            ]);
                        } else {

                            return response()->json(['errorMsg' => 'Stock is out of this product(variant) from this Business Location/Shop.']);
                        }
                    } else {

                        return response()->json(['errorMsg' => 'This product is not available in this Business Location/Shop.']);
                    }
                }
            }
        }

        return $this->nameSearchUtil->nameSearching($product_code);
    }

    public function searchStockToWarehouse($product, $product_code, $warehouse_id)
    {
        if ($product) {

            $productWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)
                ->first();

            if ($productWarehouse) {

                if ($product->type == 2) {

                    return response()->json(['errorMsg' => 'Combo product is not transferable.']);
                } else {

                    if ($productWarehouse->product_quantity > 0) {

                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productWarehouse->product_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product of this warehouse']);
                    }
                }
            } else {
                return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
            }
        } else {

            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->first();

            if ($variant_product) {

                $productWarehouse = DB::table('product_warehouses')
                    ->where('warehouse_id', $warehouse_id)
                    ->where('product_id', $variant_product->product_id)
                    ->first();

                if (is_null($productWarehouse)) {

                    return response()->json(['errorMsg' => 'This product is not available in this warehouse']);
                }

                $productWarehouseVariant = DB::table('product_warehouse_variants')
                    ->where('product_warehouse_id', $productWarehouse->id)
                    ->where('product_id', $variant_product->product_id)
                    ->where('product_variant_id', $variant_product->id)
                    ->first();

                if (is_null($productWarehouseVariant)) {

                    return response()->json(['errorMsg' => 'This variant is not available in this warehouse']);
                }

                if ($productWarehouse && $productWarehouseVariant) {

                    if ($productWarehouseVariant->variant_quantity > 0) {

                        return response()->json(
                            [
                                'variant_product' => $variant_product,
                                'qty_limit' => $productWarehouseVariant->variant_quantity
                            ]
                        );
                    } else {

                        return response()->json(['errorMsg' => 'Stock is out of this product(variant) of this warehouse']);
                    }
                } else {

                    return response()->json(['errorMsg' => 'This product is not available in this warehouse.']);
                }
            }
        }

        return $this->nameSearchUtil->nameSearching($product_code);
    }

    public function checkSingleProductStock($product_id, $warehouse_id)
    {
        if ($warehouse_id != 'no_id') {

            return $this->nameSearchUtil->checkWarehouseSingleProduct($product_id, $warehouse_id);
        } else {

            return $this->nameSearchUtil->checkBranchSingleProductStock($product_id, auth()->user()->branch_id);
        }
    }

    public function checkVariantProductStock($product_id, $variant_id, $warehouse_id)
    {
        if ($warehouse_id != 'no_id') {

            return $this->nameSearchUtil->checkWarehouseProductVariant($product_id, $variant_id, $warehouse_id);
        } else {

            return $this->nameSearchUtil->checkBranchVariantProductStock($product_id, $variant_id, auth()->user()->branch_id);
        }
    }
}
