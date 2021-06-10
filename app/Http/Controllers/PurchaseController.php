<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Account;
use App\Models\Product;
use App\Models\CashFlow;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warranty;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\SupplierLedger;
use App\Models\PurchasePayment;
use App\Models\PurchaseProduct;
use App\Models\SupplierProduct;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\ProductWarehouseVariant;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    public function index_v2(Request $request)
    {
        if (auth()->user()->permission->purchase['purchase_all'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();

            $purchases = '';
            $query = DB::table('purchases')
                ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
                ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
                ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
                ->leftJoin('admin_and_users as created_by', 'purchases.admin_id', 'created_by.id');

            if (!empty($request->branch_id)) {
                if ($request->branch_id == 'NULL') {
                    $query->where('purchases.branch_id', NULL);
                } else {
                    $query->where('purchases.branch_id', $request->branch_id);
                }
            }

            if (!empty($request->warehouse_id)) {
                $query->where('purchases.warehouse_id', $request->warehouse_id);
            }

            if ($request->supplier_id) {
                $query->where('purchases.supplier_id', $request->supplier_id);
            }

            if ($request->status) {
                $query->where('purchases.purchase_status', $request->status);
            }

            if ($request->date_range) {
                $date_range = explode('-', $request->date_range);
                $form_date = date('Y-m-d', strtotime($date_range[0]));
                //$form_date = date('Y-m-d', strtotime($date_range[0]. '-1 days'));
                $to_date = date('Y-m-d', strtotime($date_range[1] . ' +1 days'));
                //$to_date = date('Y-m-d', strtotime($date_range[1]));
                $query->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
                //$query->whereDate('report_date', '<=', $form_date.' 00:00:00')->whereDate('report_date', '>=', $to_date.' 00:00:00');
            }

            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                $purchases = $query->select(
                    'purchases.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.id as warehouse_id',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'suppliers.name as supplier_name',
                    'created_by.prefix as created_prefix',
                    'created_by.name as created_name',
                    'created_by.last_name as created_last_name',
                )->orderBy('id', 'desc')
                ->get();
            } else {
                $purchases = $query->select(
                    'purchases.*',
                    'branches.id as branch_id',
                    'branches.name as branch_name',
                    'branches.branch_code',
                    'warehouses.warehouse_name',
                    'warehouses.warehouse_code',
                    'suppliers.name as supplier_name',
                    'created_by.prefix as created_prefix',
                    'created_by.name as created_name',
                    'created_by.last_name as created_last_name',
                )->where('purchases.branch_id', auth()->user()->branch_id)
                ->orderBy('id', 'desc')
                ->get();
            }

            return DataTables::of($purchases)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item details_button" href="' . route('purchases.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i>View</a>';

                    $html .= '<a class="dropdown-item" href="' . route('barcode.on.purchase.barcode', $row->id) . '"><i class="fas fa-barcode mr-1 text-primary"></i>Barcode</a>';

                    if (auth()->user()->branch_id == $row->branch_id) {
                        if (auth()->user()->permission->purchase['purchase_payment'] == '1') {
                            if ($row->due > 0) {
                                $html .= '<a class="dropdown-item" data-type="1" id="add_payment" href="' . route('purchases.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i>Add Payment</a>';
                            }

                            if ($row->purchase_return_due > 0) {
                                $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('purchases.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i>Receive Return Amount</a>';
                            }

                            $html .= '<a class="dropdown-item" id="view_payment" href="' . route('purchase.payment.list', $row->id) . '"><i class="far fa-money-bill-alt mr-1 text-primary"></i>View Payment</a>';
                        }

                        if (auth()->user()->permission->purchase['purchase_edit'] == '1') {
                            $html .= '<a class="dropdown-item" href="' . route('purchases.edit', $row->id) . ' "><i class="far fa-edit mr-1 text-primary"></i>Edit</a>';
                        }
    
                        if (auth()->user()->permission->purchase['purchase_delete'] == '1') {
                            $html .= '<a class="dropdown-item" id="delete" href="' . route('purchase.delete', $row->id) . '"><i class="far fa-trash-alt mr-1 text-primary"></i>Delete</a>';
                        }

                        if (auth()->user()->permission->purchase['purchase_return'] == '1') {
                            $html .= '<a class="dropdown-item" id="purchase_return" href="' . route('purchases.returns.create', $row->id) . '"><i class="fas fa-undo-alt mr-1 text-primary"></i>Purchase Return</a>';
                        }

                        $html .= '<a class="dropdown-item" id="change_status" href="' . route('purchases.change.status.modal', $row->id) . '"><i class="far fa-edit mr-1 text-primary"></i>Update Status</a>';
                    }

                    $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope mr-1 text-primary"></i>Items Received Notification</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('invoice_id', function ($row) {
                    $html = '';
                    // $html .= $row->is_return_available ? '<br>' : '';
                    $html .= $row->invoice_id;
                    $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo mr-1 text-white"></i></span>' : '';
                    return $html;
                })
                ->editColumn('from',  function ($row) {
                    return $row->branch_name != null ? ($row->branch_name).'<b>(BR)</b>' : $row->warehouse_name .'<b>(WH)</b>';
                })
                ->editColumn('total_purchase_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_purchase_amount . '</b>';
                })
                ->editColumn('paid', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</b>';
                })
                ->editColumn('due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'] . ($row->due >= 0 ? $row->due :   0.00) . '</span></b>';
                })
                ->editColumn('return_amount', function ($row) use ($generalSettings) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->purchase_return_amount . '</b>';
                })
                ->editColumn('return_due', function ($row) use ($generalSettings) {
                    return '<b><span class="text-success">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->purchase_return_due . '</span></b>';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->purchase_status == 1) {
                        $html .= '<span class="text-success"><b>Received</b></span>';
                    } elseif ($row->purchase_status == 2) {
                        $html .= '<span class="text-primary"><b>Panding</b></span>';
                    } elseif ($row->purchase_status == 3) {
                        $html .= '<span class="text-warning"><b>Ordered</b></span>';
                    }
                    return $html;
                })
                ->editColumn('payment_status', function ($row) {
                    $html = '';
                    $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                    if ($row->due <= 0) {
                        $html .= '<span class="text-success"><b>Paid</b></span>';
                    } elseif ($row->due > 0 && $row->due < $payable) {
                        $html .= '<span class="text-primary"><b>Partial</b></span>';
                    } elseif ($payable == $row->due) {
                        $html .= '<span class="text-danger"><b>Due</b></span>';
                    }
                    return $html;
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('purchases.show', [$row->id]);
                    }
                ])
                ->setRowClass('clickable_row')
                ->rawColumns(['action', 'date', 'invoice_id', 'from', 'total_purchase_amount', 'paid', 'due', 'return_amount', 'return_due', 'payment_status', 'status', 'created_by'])
                ->make(true);
        }
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('purchases.index_v2', compact('branches'));
    }

    // show purchase details
    public function show($purchaseId)
    {
        $purchase = Purchase::with([
            'warehouse',
            'branch',
            'supplier',
            'admin',
            'purchase_products',
            'purchase_products.product',
            'purchase_products.product.warranty',
            'purchase_products.variant',
            'purchase_payments',
        ])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.purchase_details_modal', compact('purchase'));
    }

    public function create()
    {
        if (auth()->user()->permission->purchase['purchase_add'] == '0') {
            abort(403, 'Access Forbidden.');
        }
        return view('purchases.create');
    }

    // add purchase method
    public function store(Request $request)
    {
        //return $request->all();
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_invoice'];
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];
        $isEditProductPrice = json_decode($prefixSettings->purchase, true)['is_edit_pro_price'];
        //return $request->all();
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $this->validate($request, [
                'warehouse_id' => 'required',
            ]);
        }

        $this->validate($request, [
            'supplier_id' => 'required',
        ]);

        if (!isset($request->product_ids)) {
            return response()->json(['errorMsg' => 'Product table is empty.']);
        }

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;
        $unit_names = $request->unit_names;
        $discounts = $request->unit_discounts;
        $unit_costs = $request->unit_costs;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $subtotal = $request->subtotals;
        $tax_percents = $request->tax_percents;
        $unit_taxes = $request->unit_taxes;
        $net_unit_costs = $request->net_unit_costs;
        $linetotals = $request->linetotals;
        $profits = $request->profits;
        $selling_prices = $request->selling_prices;

        // Add supplier product
        $i = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$i] != 'noid' ? $variant_ids[$i] : NULL;
            $SupplierProduct = SupplierProduct::where('supplier_id', $request->supplier_id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->first();
            if (!$SupplierProduct) {
                $addSupplierProduct = new SupplierProduct();
                $addSupplierProduct->supplier_id = $request->supplier_id;
                $addSupplierProduct->product_id = $product_id;
                $addSupplierProduct->product_variant_id = $variant_id;
                $addSupplierProduct->label_qty = $quantities[$i];
                $addSupplierProduct->save();
            } else {
                $SupplierProduct->label_qty += $quantities[$i];
                $SupplierProduct->save();
            }
            $i++;
        }

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // update product and variant quantity
        $productIndex = 0;
        foreach ($product_ids as $productId) {
            $updateProductQty = Product::where('id', $productId)->first();
            $updateProductQty->is_purchased = 1;
            $updateProductQty->quantity += $quantities[$productIndex];
            if ($updateProductQty->is_variant == 0) {
                $updateProductQty->product_cost = $unit_costs[$productIndex];
                $updateProductQty->product_cost_with_tax = $unit_costs_inc_tax[$productIndex];

                if ($isEditProductPrice == '1') {
                    $updateProductQty->profit = $profits[$productIndex];
                    $updateProductQty->product_price = $selling_prices[$productIndex];
                }
                $updateProductQty->save();
            }

            if ($variant_ids[$productIndex] != 'noid') {
                $updateVariantQty = ProductVariant::where('id', $variant_ids[$productIndex])->where('product_id', $productId)->first();
                $updateVariantQty->variant_quantity += $quantities[$productIndex];
                $updateVariantQty->variant_cost = $unit_costs[$productIndex];
                $updateVariantQty->variant_cost_with_tax = $unit_costs_inc_tax[$productIndex];

                if ($isEditProductPrice == '1') {
                    $updateVariantQty->variant_profit = $profits[$productIndex];
                    $updateProductQty->variant_price = $selling_prices[$productIndex];
                }

                $updateVariantQty->is_purchased = 1;
                $updateVariantQty->save();
            }
            $productIndex++;
        }

        $getLastCreated = Purchase::where('is_last_created', 1)->first();
        if ($getLastCreated) {
            $getLastCreated->is_last_created = 0;
            $getLastCreated->save();
        }

        // add purchase total information
        $addPurchase = new Purchase();
        $addPurchase->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'PI') . date('ymd') . $invoiceId;
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $addPurchase->warehouse_id = $request->warehouse_id;
        } else {
            $addPurchase->branch_id = auth()->user()->branch_id;
        }

        $addPurchase->supplier_id = $request->supplier_id;
        $addPurchase->pay_term = $request->pay_term;
        $addPurchase->pay_term_number = $request->pay_term_number;
        $addPurchase->admin_id = auth()->user()->id;
        $addPurchase->total_item = $request->total_item;
        $addPurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addPurchase->order_discount_type = $request->order_discount_type;
        $addPurchase->order_discount_amount = $request->order_discount_amount;
        $addPurchase->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
        $addPurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $addPurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addPurchase->net_total_amount = $request->net_total_amount;
        $addPurchase->total_purchase_amount = $request->total_purchase_amount;
        $addPurchase->paid = $request->paying_amount;
        $addPurchase->due = $request->purchase_due;
        $addPurchase->shipment_details = $request->shipment_details;
        $addPurchase->purchase_note = $request->purchase_note;
        $addPurchase->purchase_status = $request->purchase_status;
        $addPurchase->date = $request->date;
        $addPurchase->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchase->time = date('h:i:s a');
        $addPurchase->month = date('F');
        $addPurchase->year = date('Y');
        $addPurchase->is_last_created = 1;

        if ($request->hasFile('attachment')) {
            $purchaseAttachment = $request->file('attachment');
            $purchaseAttachmentName = uniqid() . '-' . '.' . $purchaseAttachment->getClientOriginalExtension();
            $purchaseAttachment->move(public_path('uploads/purchase_attachment/'), $purchaseAttachmentName);
            $addPurchase->attachment = $purchaseAttachmentName;
        }
        $addPurchase->save();

        // Update supplier due
        $supplier = Supplier::where('id', $request->supplier_id)->first();
        $supplier->total_purchase += $request->total_purchase_amount;
        $supplier->total_paid += $request->paying_amount;
        $supplier->total_purchase_due += $request->purchase_due;
        $supplier->save();

        // add purchase product
        $index = 0;
        foreach ($product_ids as $productId) {
            $addPurchaseProduct = new PurchaseProduct();
            $addPurchaseProduct->purchase_id = $addPurchase->id;
            $addPurchaseProduct->product_id = $productId;
            $addPurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $addPurchaseProduct->quantity = $quantities[$index];
            $addPurchaseProduct->unit = $unit_names[$index];
            $addPurchaseProduct->unit_cost = $unit_costs[$index];
            $addPurchaseProduct->unit_discount = $discounts[$index];
            $addPurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
            $addPurchaseProduct->subtotal = $subtotal[$index];
            $addPurchaseProduct->unit_tax_percent = $tax_percents[$index];
            $addPurchaseProduct->unit_tax = $unit_taxes[$index];
            $addPurchaseProduct->net_unit_cost = $net_unit_costs[$index];
            $addPurchaseProduct->line_total = $linetotals[$index];
            if ($isEditProductPrice == '1') {
                $addPurchaseProduct->profit_margin = $profits[$index];
                $addPurchaseProduct->selling_price = $selling_prices[$index];
            }

            if (isset($request->lot_number)) {
                $addPurchaseProduct->lot_no = $request->lot_number[$index];
            }

            $addPurchaseProduct->save();
            $index++;
        }

        // add purchase product in warehouse
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $index2 = 0;
            foreach ($product_ids as $productId) {
                // add warehouse product
                $productWarehouse = ProductWarehouse::where('warehouse_id', $request->warehouse_id)->where('product_id', $productId)->first();
                if ($productWarehouse) {
                    $productWarehouse->product_quantity += $quantities[$index2];
                    $productWarehouse->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productWarehouseVariant) {
                            $productWarehouseVariant->variant_quantity += $quantities[$index2];
                            $productWarehouseVariant->save();
                        } else {
                            $addProductWarehousehVariant = new ProductWarehouseVariant();
                            $addProductWarehousehVariant->product_warehouse_id = $productWarehouse->id;
                            $addProductWarehousehVariant->product_id = $productId;
                            $addProductWarehousehVariant->product_variant_id = $variant_ids[$index2];
                            $addProductWarehousehVariant->variant_quantity = $quantities[$index2];
                            $addProductWarehousehVariant->save();
                        }
                    }
                } else {
                    $addProductWarehouse = new ProductWarehouse();
                    $addProductWarehouse->warehouse_id = $request->warehouse_id;
                    $addProductWarehouse->product_id = $productId;
                    $addProductWarehouse->product_quantity = $quantities[$index2];
                    $addProductWarehouse->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $addProductWarehouse->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productWarehouseVariant) {
                            $productWarehouseVariant->variant_quantity += $quantities[$index2];
                            $productWarehouseVariant->save();
                        } else {
                            $addProductWarehouseVariant = new ProductWarehouseVariant();
                            $addProductWarehouseVariant->product_warehouse_id = $addProductWarehouse->id;
                            $addProductWarehouseVariant->product_id = $productId;
                            $addProductWarehouseVariant->product_variant_id = $variant_ids[$index2];
                            $addProductWarehouseVariant->variant_quantity = $quantities[$index2];
                            $addProductWarehouseVariant->save();
                        }
                    }
                }
                $index2++;
            }
        } else {
            $index2 = 0;
            foreach ($product_ids as $productId) {
                // add branch product
                $productBranch = ProductBranch::where('branch_id', auth()->user()->branch_id)->where('product_id', $productId)->first();
                if ($productBranch) {
                    $productBranch->product_quantity += $quantities[$index2];
                    $productBranch->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productBranchVariant) {
                            $productBranchVariant->variant_quantity += $quantities[$index2];
                            $productBranchVariant->save();
                        } else {
                            $addProductBranchVariant = new ProductBranchVariant();
                            $addProductBranchVariant->product_branch_id = $productBranch->id;
                            $addProductBranchVariant->product_id = $productId;
                            $addProductBranchVariant->product_variant_id = $variant_ids[$index2];
                            $addProductBranchVariant->variant_quantity = $quantities[$index2];
                            $addProductBranchVariant->save();
                        }
                    }
                } else {
                    $addProductBranch = new ProductBranch();
                    $addProductBranch->branch_id = auth()->user()->branch_id;
                    $addProductBranch->product_id = $productId;
                    $addProductBranch->product_quantity = $quantities[$index2];
                    $addProductBranch->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $addProductBranch->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productBranchVariant) {
                            $productBranchVariant->variant_quantity += $quantities[$index2];
                            $productBranchVariant->save();
                        } else {
                            $addProductBranchVariant = new ProductWarehouseVariant();
                            $addProductBranchVariant->product_branch_id = $addProductBranch->id;
                            $addProductBranchVariant->product_id = $productId;
                            $addProductBranchVariant->product_variant_id = $variant_ids[$index2];
                            $addProductBranchVariant->variant_quantity = $quantities[$index2];
                            $addProductBranchVariant->save();
                        }
                    }
                }
                $index2++;
            }
        }

        // Add supplier ledger
        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->supplier_id = $request->supplier_id;
        $addSupplierLedger->purchase_id = $addPurchase->id;
        $addSupplierLedger->save();

        // Add purchase payment
        if ($request->paying_amount > 0) {
            $addPurchasePayment = new PurchasePayment();
            $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPI') . date('ymd') . $invoiceId;
            $addPurchasePayment->purchase_id = $addPurchase->id;
            $addPurchasePayment->supplier_id = $request->supplier_id;
            $addPurchasePayment->account_id = $request->account_id;
            $addPurchasePayment->pay_mode = $request->payment_method;
            $addPurchasePayment->paid_amount = $request->paying_amount;
            $addPurchasePayment->date = $request->date;
            $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
            $addPurchasePayment->month = date('F');
            $addPurchasePayment->year = date('Y');
            $addPurchasePayment->note = $request->payment_note;

            if ($request->payment_method == 'Card') {
                $addPurchasePayment->card_no = $request->card_no;
                $addPurchasePayment->card_holder = $request->card_holder_name;
                $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
                $addPurchasePayment->card_type = $request->card_type;
                $addPurchasePayment->card_month = $request->month;
                $addPurchasePayment->card_year = $request->year;
                $addPurchasePayment->card_secure_code = $request->secure_code;
            } elseif ($request->payment_method == 'Cheque') {
                $addPurchasePayment->cheque_no = $request->cheque_no;
            } elseif ($request->payment_method == 'Bank-Transfer') {
                $addPurchasePayment->account_no = $request->account_no;
            } elseif ($request->payment_method == 'Custom') {
                $addPurchasePayment->transaction_no = $request->transaction_no;
            }
            $addPurchasePayment->admin_id = auth()->user()->id;
            $addPurchasePayment->save();

            if ($request->account_id) {
                // update account
                $account = Account::where('id', $request->account_id)->first();
                $account->debit += $request->paying_amount;
                $account->balance -= $request->paying_amount;
                $account->save();

                // Add cash flow
                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->paying_amount;
                $addCashFlow->balance = $account->balance;
                $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
                $addCashFlow->transaction_type = 3;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
            }

            // Add supplier ledger
            $addSupplierLedger = new SupplierLedger();
            $addSupplierLedger->supplier_id = $request->supplier_id;
            $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
            $addSupplierLedger->row_type = 2;
            $addSupplierLedger->save();
        }

        session()->flash('successMsg', 'Successfully purchase is added');
        return response()->json('Successfully purchase is added');
    }

    // update purchase method
    public function update(Request $request)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_invoice'];
        $isEditProductPrice = json_decode($prefixSettings->purchase, true)['is_edit_pro_price'];

        //return $request->all();
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $this->validate($request, [
                'warehouse_id' => 'required',
            ]);
        }

        if (!isset($request->product_ids)) {
            return response()->json(['errorMsg' => 'Product table is empty.']);
        }

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;
        $unit_names = $request->unit_names;
        $discounts = $request->unit_discounts;
        $unit_costs = $request->unit_costs;
        $unit_costs_with_discount = $request->unit_costs_with_discount;
        $subtotal = $request->subtotals;
        $tax_percents = $request->tax_percents;
        $unit_taxes = $request->unit_taxes;
        $net_unit_costs = $request->net_unit_costs;
        $linetotals = $request->linetotals;
        $profits = $request->profits;
        $selling_prices = $request->selling_prices;

        // get updatable purchase row
        $updatePurchase = purchase::with('purchase_products')->where('id', $request->id)->first();

        // Update supplier total purchase due
        $presentDue = $request->total_purchase_amount - $updatePurchase->paid - $updatePurchase->purchase_return_amount;
        $previouseDue = $updatePurchase->due;
        $supplierDue =  $presentDue - $previouseDue;
        $supplier = Supplier::where('id', $updatePurchase->supplier_id)->first();
        $supplier->total_purchase_due += $supplierDue;
        $supplier->total_purchase -= $updatePurchase->total_purchase_amount;
        $supplier->total_purchase += $request->total_purchase_amount;
        $supplier->save();

        // update product and variant quantity for adjustment
        foreach ($updatePurchase->purchase_products as $purchase_product) {
            $updateProductQty = Product::where('id', $purchase_product->product_id)->first();
            $updateProductQty->quantity -= $purchase_product->quantity;
            $updateProductQty->save();
            if ($purchase_product->product_variant_id) {
                $updateVariantQty = ProductVariant::where('id', $purchase_product->product_variant_id)->where('product_id', $purchase_product->product_id)->first();
                $updateVariantQty->variant_quantity -= $purchase_product->quantity;
                $updateVariantQty->save();
            }

            $SupplierProduct = SupplierProduct::where('supplier_id', $updatePurchase->supplier_id)
                ->where('product_id', $purchase_product->product_id)
                ->where('product_variant_id', $purchase_product->product_variant_id)
                ->first();

            if ($SupplierProduct) {
                $SupplierProduct->label_qty -= $purchase_product->quantity;
                $SupplierProduct->save();
            }
        }

        // update Warehouse product and variant quantity for adjustment
        if ($updatePurchase->warehouse_id) {
            foreach ($updatePurchase->purchase_products as $purchase_product) {
                $updateProductWarehouse = ProductWarehouse::where('warehouse_id', $updatePurchase->warehouse_id)->where('product_id', $purchase_product->product_id)->first();
                $updateProductWarehouse->product_quantity -= $purchase_product->quantity;
                $updateProductWarehouse->save();
                if ($purchase_product->product_variant_id) {
                    $updateProductWarehouseVariant =  ProductWarehouseVariant::where('product_warehouse_id', $updateProductWarehouse->id)->where('product_id', $purchase_product->product_id)->where('product_variant_id', $purchase_product->product_variant_id)->first();
                    $updateProductWarehouseVariant->variant_quantity -= $purchase_product->quantity;
                    $updateProductWarehouseVariant->save();
                }
            }
        } else {
            foreach ($updatePurchase->purchase_products as $purchase_product) {
                $updateProductBranch = ProductBranch::where('branch_id', $updatePurchase->branch_id)->where('product_id', $purchase_product->product_id)->first();
                $updateProductBranch->product_quantity -= $purchase_product->quantity;
                $updateProductBranch->save();

                if ($purchase_product->product_variant_id) {
                    $updateProductBranchVariant =  ProductBranchVariant::where('product_branch_id', $updateProductBranch->id)->where('product_id', $purchase_product->product_id)->where('product_variant_id', $purchase_product->product_variant_id)->first();
                    $updateProductBranchVariant->variant_quantity -= $purchase_product->quantity;
                    $updateProductBranchVariant->save();
                }
            }
        }

        // update update delete_in_update column of purchase_product table for noticing new and old product for update
        foreach ($updatePurchase->purchase_products as $purchase_product) {
            $purchase_product->delete_in_update = 1;
            $purchase_product->save();
        }

        // update supplier product
        $i = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$i] != 'noid' ? $variant_ids[$i] : NULL;
            $SupplierProduct = SupplierProduct::where('supplier_id', $updatePurchase->supplier_id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->first();
            if (!$SupplierProduct) {
                $addSupplierProduct = new SupplierProduct();
                $addSupplierProduct->supplier_id = $updatePurchase->supplier_id;
                $addSupplierProduct->product_id = $product_id;
                $addSupplierProduct->product_variant_id = $variant_id;
                $addSupplierProduct->label_qty = $quantities[$i];
                $addSupplierProduct->save();
            } else {
                $SupplierProduct->label_qty += $quantities[$i];
                $SupplierProduct->save();
            }
            $i++;
        }

        // update product and variant quantity
        $productIndex = 0;
        foreach ($product_ids as $productId) {
            $updateProductQty = Product::where('id', $productId)->first();
            $updateProductQty->quantity += $quantities[$productIndex];
            if ($updatePurchase->is_last_created == 1) {
                if ($updateProductQty->is_variant == 0) {
                    $updateProductQty->product_cost = $unit_costs_with_discount[$productIndex];
                    $updateProductQty->product_cost_with_tax = $net_unit_costs[$productIndex];
                    if ($isEditProductPrice == '1') {
                        $updateProductQty->profit = $profits[$productIndex];
                        $updateProductQty->product_price = $selling_prices[$productIndex];
                    } 
                }
            }
            $updateProductQty->save();
            if ($variant_ids[$productIndex] != 'noid') {
                $updateVariantQty = ProductVariant::where('id', $variant_ids[$productIndex])->where('product_id', $productId)->first();
                $updateVariantQty->variant_quantity += $quantities[$productIndex];
                if ($updatePurchase->is_last_created == 1) {
                    $updateVariantQty->variant_cost = $unit_costs_with_discount[$productIndex];
                    $updateVariantQty->variant_cost_with_tax = $net_unit_costs[$productIndex];
                    if ($isEditProductPrice == '1') {
                        $updateVariantQty->variant_profit = $profits[$productIndex];
                        $updateProductQty->variant_price = $selling_prices[$productIndex];
                    }
                }
                $updateVariantQty->save();
            }
            $productIndex++;
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $updatePurchase->warehouse_id = $request->warehouse_id;
        }

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // update purchase total information
        $updatePurchase->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'PI') . date('ymd') . $invoiceId;
        $updatePurchase->pay_term = $request->pay_term;
        $updatePurchase->pay_term_number = $request->pay_term_number;
        $updatePurchase->invoice_id = $request->invoice_id;
        $updatePurchase->admin_id = auth()->user()->id;
        $updatePurchase->total_item = $request->total_item;
        $updatePurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $updatePurchase->order_discount_type = $request->order_discount_type;
        $updatePurchase->order_discount_amount = $request->order_discount_amount;
        $updatePurchase->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
        $updatePurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $updatePurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updatePurchase->net_total_amount = $request->net_total_amount;
        $updatePurchase->total_purchase_amount = $request->total_purchase_amount;
        $updatePurchase->due = $request->total_purchase_amount - $updatePurchase->paid - $updatePurchase->purchase_return_amount;
        $updatePurchase->shipment_details = $request->shipment_details;
        $updatePurchase->purchase_note = $request->purchase_note;
        $updatePurchase->purchase_status = $request->purchase_status;
        $updatePurchase->date = $request->date;
        $updatePurchase->report_date = date('Y-m-d', strtotime($request->date));

        if ($request->hasFile('attachment')) {
            if ($updatePurchase->attachment != null) {
                if (file_exists(public_path('uploads/purchase_attachment/' . $updatePurchase->attachment))) {
                    unlink(public_path('uploads/purchase_attachment/' . $updatePurchase->attachment));
                }
            }
            $purchaseAttachment = $request->file('attachment');
            $purchaseAttachmentName = uniqid() . '-' . '.' . $purchaseAttachment->getClientOriginalExtension();
            $purchaseAttachment->move(public_path('uploads/purchase_attachment/'), $purchaseAttachmentName);
            $updatePurchase->attachment = $purchaseAttachmentName;
        }

        $updatePurchase->save();

        // add purchase product
        $index = 0;
        foreach ($product_ids as $productId) {
            $filterVariantId = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $updatePurchaseProduct = PurchaseProduct::where('purchase_id', $updatePurchase->id)->where('product_id', $productId)->where('product_variant_id', $filterVariantId)->first();
            if ($updatePurchaseProduct) {
                $updatePurchaseProduct->product_id = $productId;
                $updatePurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $updatePurchaseProduct->quantity = $quantities[$index];
                $updatePurchaseProduct->unit = $unit_names[$index];
                $updatePurchaseProduct->unit_cost = $unit_costs[$index];
                $updatePurchaseProduct->unit_discount = $discounts[$index];
                $updatePurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
                $updatePurchaseProduct->subtotal = $subtotal[$index];
                $updatePurchaseProduct->unit_tax_percent = $tax_percents[$index];
                $updatePurchaseProduct->unit_tax = $unit_taxes[$index];
                $updatePurchaseProduct->net_unit_cost = $net_unit_costs[$index];
                $updatePurchaseProduct->line_total = $linetotals[$index];

                if ($isEditProductPrice == '1') {
                    $updatePurchaseProduct->profit_margin = $profits[$index];
                    $updatePurchaseProduct->selling_price = $selling_prices[$index];
                }
                
                if (isset($request->lot_number)) {
                    $updatePurchaseProduct->lot_no = $request->lot_number[$index];
                }
                $updatePurchaseProduct->delete_in_update = 0;
                $updatePurchaseProduct->save();
            } else {
                $addPurchaseProduct = new PurchaseProduct();
                $addPurchaseProduct->purchase_id = $updatePurchase->id;
                $addPurchaseProduct->product_id = $productId;
                $addPurchaseProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $addPurchaseProduct->quantity = $quantities[$index];
                $addPurchaseProduct->unit = $unit_names[$index];
                $addPurchaseProduct->unit_cost = $unit_costs[$index];
                $addPurchaseProduct->unit_discount = $discounts[$index];
                $addPurchaseProduct->unit_cost_with_discount = $unit_costs_with_discount[$index];
                $addPurchaseProduct->subtotal = $subtotal[$index];
                $addPurchaseProduct->unit_tax_percent = $tax_percents[$index];
                $addPurchaseProduct->unit_tax = $unit_taxes[$index];
                $addPurchaseProduct->net_unit_cost = $net_unit_costs[$index];
                $addPurchaseProduct->line_total = $linetotals[$index];

                if ($isEditProductPrice == '1') {
                    $addPurchaseProduct->profit_margin = $profits[$index];
                    $addPurchaseProduct->selling_price = $selling_prices[$index];
                }
                
                if (isset($request->lot_number)) {
                    $addPurchaseProduct->lot_no = $request->lot_number[$index];
                }
                $addPurchaseProduct->save();
            }
            $index++;
        }

        // add purchase product in warehouse
        // add purchase product in warehouse
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $index2 = 0;
            foreach ($product_ids as $productId) {
                // add warehouse product
                $productWarehouse = ProductWarehouse::where('warehouse_id', $request->warehouse_id)->where('product_id', $productId)->first();
                if ($productWarehouse) {
                    $productWarehouse->product_quantity += $quantities[$index2];
                    $productWarehouse->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productWarehouseVariant) {
                            $productWarehouseVariant->variant_quantity += $quantities[$index2];
                            $productWarehouseVariant->save();
                        } else {
                            $addProductWarehousehVariant = new ProductWarehouseVariant();
                            $addProductWarehousehVariant->product_warehouse_id = $productWarehouse->id;
                            $addProductWarehousehVariant->product_id = $productId;
                            $addProductWarehousehVariant->product_variant_id = $variant_ids[$index2];
                            $addProductWarehousehVariant->variant_quantity = $quantities[$index2];
                            $addProductWarehousehVariant->save();
                        }
                    }
                } else {
                    $addProductWarehouse = new ProductWarehouse();
                    $addProductWarehouse->warehouse_id = $request->warehouse_id;
                    $addProductWarehouse->product_id = $productId;
                    $addProductWarehouse->product_quantity = $quantities[$index2];
                    $addProductWarehouse->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $addProductWarehouse->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productWarehouseVariant) {
                            $productWarehouseVariant->variant_quantity += $quantities[$index2];
                            $productWarehouseVariant->save();
                        } else {
                            $addProductWarehouseVariant = new ProductWarehouseVariant();
                            $addProductWarehouseVariant->product_warehouse_id = $addProductWarehouse->id;
                            $addProductWarehouseVariant->product_id = $productId;
                            $addProductWarehouseVariant->product_variant_id = $variant_ids[$index2];
                            $addProductWarehouseVariant->variant_quantity = $quantities[$index2];
                            $addProductWarehouseVariant->save();
                        }
                    }
                }
                $index2++;
            }
        } else {
            $index2 = 0;
            foreach ($product_ids as $productId) {
                // add branch product
                $productBranch = ProductBranch::where('branch_id', auth()->user()->branch_id)->where('product_id', $productId)->first();
                if ($productBranch) {
                    $productBranch->product_quantity += $quantities[$index2];
                    $productBranch->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productBranchVariant) {
                            $productBranchVariant->variant_quantity += $quantities[$index2];
                            $productBranchVariant->save();
                        } else {
                            $addProductBranchVariant = new ProductWarehouseVariant();
                            $addProductBranchVariant->product_branch_id = $productBranch->id;
                            $addProductBranchVariant->product_id = $productId;
                            $addProductBranchVariant->product_variant_id = $variant_ids[$index2];
                            $addProductBranchVariant->variant_quantity = $quantities[$index2];
                            $addProductBranchVariant->save();
                        }
                    }
                } else {
                    $addProductBranch = new ProductBranch();
                    $addProductBranch->branch_id = auth()->user()->branch_id;
                    $addProductBranch->product_id = $productId;
                    $addProductBranch->product_quantity = $quantities[$index2];
                    $addProductBranch->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productBranchVariant = ProductBranchVariant::where('product_branch_id', $addProductBranch->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productBranchVariant) {
                            $productBranchVariant->variant_quantity += $quantities[$index2];
                            $productBranchVariant->save();
                        } else {
                            $addProductBranchVariant = new ProductWarehouseVariant();
                            $addProductBranchVariant->product_branch_id = $addProductBranch->id;
                            $addProductBranchVariant->product_id = $productId;
                            $addProductBranchVariant->product_variant_id = $variant_ids[$index2];
                            $addProductBranchVariant->variant_quantity = $quantities[$index2];
                            $addProductBranchVariant->save();
                        }
                    }
                }
                $index2++;
            }
        }

        // deleted not getting previous product
        $deletedPurchaseProducts = PurchaseProduct::where('purchase_id', $request->id)->where('delete_in_update', 1)->get();
        foreach ($deletedPurchaseProducts as $deletedPurchaseProduct) {
            $deletedPurchaseProduct->delete();
        }
        session()->flash('successMsg', 'Successfully purchase is updated');
        return response()->json('Successfully purchase is updated');
    }

    // Product edti view
    public function edit($purchaseId)
    {
        $purchaseId = $purchaseId;
        $warehouses = DB::table('warehouses')->get();
        $purchase = DB::table('purchases')->where('id', $purchaseId)->select('id', 'warehouse_id', 'date')->first();
        return view('purchases.edit', compact('purchaseId', 'warehouses', 'purchase'));
    }

    // Get editable purchase
    public function editablePurchase($purchaseId)
    {
        $purchase = Purchase::with([
            'warehouse',
            'supplier',
            'purchase_products',
            'purchase_products.product',
            'purchase_products.variant'
        ])
            ->where('id', $purchaseId)
            ->first();
        return response()->json($purchase);
    }

    // Get all supplier requested by ajax
    public function getAllSupplier()
    {
        $suppliers = Supplier::select('id',  'name',  'pay_term', 'pay_term_number', 'phone')->get();
        return response()->json($suppliers);
    }

    // Get all warehouse requested by ajax
    public function getAllWarehouse()
    {
        $warehouses = Warehouse::select('id', 'warehouse_name', 'warehouse_code')->get();
        return response()->json($warehouses);
    }

    // Get all warehouse requested by ajax
    public function getAllUnit()
    {
        $unites = Unit::select('id', 'name')->get();
        return response()->json($unites);
    }

    // Get all warehouse requested by ajax
    public function getAllTax()
    {
        $taxes = Tax::select('id', 'tax_name', 'tax_percent')->get();
        return response()->json($taxes);
    }

    // Search product by code
    public function searchProduct($product_code)
    {
        $namedProducts = '';
        $nameSearch = Product::with(['product_variants', 'tax', 'unit'])
            ->where('name', 'LIKE', $product_code . '%')
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

        $unitCostSearch = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_cost', 'like', "%$product_code%")
            ->where('status', 1)
            ->get();

        if (count($unitCostSearch) > 0) {
            $namedProducts = $unitCostSearch;
        }

        $unitCostIncTaxSearch = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_cost_with_tax', 'like', "%$product_code%")
            ->where('status', 1)
            ->get();

        if (count($unitCostIncTaxSearch) > 0) {
            $namedProducts = $unitCostIncTaxSearch;
        }

        if ($namedProducts && $namedProducts->count() > 0) {
            return response()->json(['namedProducts' => $namedProducts]);
        }

        // $namedProducts = Product::with(['product_variants', 'tax', 'unit'])->where('name', 'LIKE', '%' . $product_code . '%')->where('status', 1)->get();
        // if ($namedProducts->count() > 0) {
        //     return response()->json(['namedProducts' => $namedProducts]);
        // }

        $product = Product::with(['product_variants', 'tax', 'unit'])->where('type', 1)->where('product_code', $product_code)->where('status', 1)->first();
        // if ($product->status == 0) {
        //     return response()->json(['errorMsg' => 'This product is disabled']);
        // }

        if ($product) {
            return response()->json(['product' => $product]);
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')->where('variant_code', $product_code)->first();
            return response()->json(['variant_product' => $variant_product]);
        }
    }

    // delete purchase method
    public function delete(Request $request, $purchaseId)
    {
        // get deleting purchase row
        $deletePurchase = purchase::with('supplier', 'purchase_products')->where('id', $purchaseId)->first();
        $supplier = Supplier::where('id', $deletePurchase->supplier_id)->first();
        $supplier->total_purchase_due -= $deletePurchase->due > 0 ? $deletePurchase->due : 0;
        $supplier->total_purchase_return_due -= $deletePurchase->purchase_return_due;
        $supplier->save();

        foreach ($deletePurchase->purchase_products as $purchase_product) {
            $updateProductQty = Product::where('id', $purchase_product->product_id)->first();
            $updateProductQty->quantity -= $purchase_product->quantity;
            $updateProductQty->save();
            if ($purchase_product->product_variant_id) {
                $updateVariantQty = ProductVariant::where('id', $purchase_product->product_variant_id)->where('product_id', $purchase_product->product_id)->first();
                $updateVariantQty->variant_quantity -= $purchase_product->quantity;
                $updateVariantQty->save();
            }

            $SupplierProduct = SupplierProduct::where('supplier_id', $deletePurchase->supplier_id)
                ->where('product_id', $purchase_product->product_id)
                ->where('product_variant_id', $purchase_product->product_variant_id)
                ->first();
            if ($SupplierProduct) {
                $SupplierProduct->label_qty -= $purchase_product->quantity;;
                $SupplierProduct->save();
            }
        }

        // update product and variant quantity for adjustment
        if ($deletePurchase->warehouse_id) {
            // update warehouse product or warehouse product and variant quantity for adjustment
            foreach ($deletePurchase->purchase_products as $purchase_product) {
                $updateProductWarehouse = ProductWarehouse::where('warehouse_id', $deletePurchase->warehouse_id)->where('product_id', $purchase_product->product_id)->first();
                if ($updateProductWarehouse) {
                    $updateProductWarehouse->product_quantity -= $purchase_product->quantity;
                    $updateProductWarehouse->save();
                    if ($purchase_product->product_variant_id) {
                        $updateProductWarehouseVariant =  ProductWarehouseVariant::where('product_warehouse_id', $updateProductWarehouse->id)->where('product_id', $purchase_product->product_id)->where('product_variant_id', $purchase_product->product_variant_id)->first();
                        $updateProductWarehouseVariant->variant_quantity -= $purchase_product->quantity;
                        $updateProductWarehouseVariant->save();
                    }
                }
            }
        } else {
            // update warehouse product or branch product and variant quantity for adjustment
            foreach ($deletePurchase->purchase_products as $purchase_product) {
                $updateProductBranch = ProductBranch::where('branch_id', $deletePurchase->branch_id)->where('product_id', $purchase_product->product_id)->first();
                if ($updateProductBranch) {
                    $updateProductBranch->product_quantity -= $purchase_product->quantity;
                    $updateProductBranch->save();
                    if ($purchase_product->product_variant_id) {
                        $updateProductBranchVariant =  ProductBranchVariant::where('product_branch_id', $updateProductBranch->id)->where('product_id', $purchase_product->product_id)->where('product_variant_id', $purchase_product->product_variant_id)->first();
                        $updateProductBranchVariant->variant_quantity -= $purchase_product->quantity;
                        $updateProductBranchVariant->save();
                    }
                }
            }
        }

        $deletePurchase->delete();
        return response()->json('Successfully purchase is deleted');
    }

    // Add product modal view with data
    public function addProductModalVeiw()
    {
        $units =  Unit::select(['id', 'name'])->get();
        $warranties = Warranty::select(['id', 'name', 'type'])->get();
        $taxes = Tax::select(['id', 'tax_name', 'tax_percent'])->get();
        $categories =  Category::where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
        $brands = $brands = Brand::all();
        return view('purchases.ajax_view.add_product_modal_view', compact('units', 'warranties', 'taxes', 'categories', 'brands'));
    }

    // Add product from purchase
    public function addProduct(Request $request)
    {
        $addProduct = new Product();
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $this->validate(
            $request,
            [
                'name' => 'required',
                'product_code' => 'required',
                'category_id' => 'required',
                'unit_id' => 'required',
                'product_price' => 'required',
                'product_cost' => 'required',
                'product_cost_with_tax' => 'required',
            ],
            [
                'category_id.required' => 'Category field is required.',
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        $addProduct->type = 1;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->product_code;
        $addProduct->category_id = $request->category_id;
        $addProduct->parent_category_id = $request->child_category_id;
        $addProduct->brand_id = $request->brand_id;
        $addProduct->unit_id = $request->unit_id;
        $addProduct->product_cost = $request->product_cost;
        $addProduct->profit = $request->profit ? $request->profit : 0.00;
        $addProduct->product_cost_with_tax = $request->product_cost_with_tax;
        $addProduct->product_price = $request->product_price;
        $addProduct->alert_quantity = $request->alert_quantity;
        $addProduct->tax_id = $tax_id;
        $addProduct->product_details = $request->product_details;
        $addProduct->is_purchased = 1;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $addProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $addProduct->save();
        return response()->json($addProduct);
    }

    // Get recent added product which has been added from purchase
    public function getRecentProduct($product_id)
    {
        $product = Product::with(['tax', 'unit'])
            ->where('id', $product_id)
            ->first();
        $units = Unit::select(['id', 'name'])->get();
        return view('purchases.ajax_view.recent_product_view', compact('product', 'units'));
    }

    // Get quick supplier modal
    public function addQuickSupplierModal()
    {
        return view('purchases.ajax_view.add_quick_supplier');
    }

    // Change purchase status
    public function addSupplier(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $addSupplier = Supplier::create([
            'type' => $request->contact_type,
            'contact_id' => $request->contact_id,
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->phone,
            'landline' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'tax_number' => $request->tax_number,
            'pay_term' => $request->pay_term,
            'pay_term_number' => $request->pay_term_number,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0,
            'total_purchase_due' => $request->opening_balance ? $request->opening_balance : 0,
        ]);

        if ($request->opening_balance && $request->opening_balance >= 0) {
            $addSupplierLedger = new SupplierLedger();
            $addSupplierLedger->supplier_id = $addSupplier->id;
            $addSupplierLedger->row_type = 3;
            $addSupplierLedger->amount = $request->opening_balance;
            $addSupplierLedger->save();
        }

        return response()->json($addSupplier);
    }

    // Change purchase status
    public function changeStatus(Request $request, $purchaseId)
    {
        $purchase = Purchase::where('id', $purchaseId)->first();
        $purchase->purchase_status = $request->purchase_status;
        $purchase->save();
        return response()->json('Successfully purchase status is changed.');
    }

    public function paymentModal($purchaseId)
    {
        $accounts = DB::table('accounts')->get();
        $purchase = Purchase::with(['supplier', 'branch', 'warehouse'])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.purchase_payment_modal', compact('purchase', 'accounts'));
    }

    public function paymentStore(Request $request, $purchaseId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];

        $purchase = Purchase::where('id', $purchaseId)->first();
        //Update Supplier due 
        $supplier = Supplier::where('id', $purchase->supplier_id)->first();
        $supplier->total_paid += $request->amount;
        $supplier->total_purchase_due -= $request->amount;
        $supplier->save();

        // Update purchase
        $purchase->paid += $request->amount;
        $purchase->due -= $request->amount;
        $purchase->save();

        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // Add purchase payment
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'PPR') . date('ymd') . $invoiceId;
        $addPurchasePayment->purchase_id = $purchase->id;
        $addPurchasePayment->supplier_id = $purchase->supplier_id;
        $addPurchasePayment->account_id = $request->account_id;
        $addPurchasePayment->pay_mode = $request->payment_method;
        $addPurchasePayment->paid_amount = $request->amount;
        $addPurchasePayment->date = $request->date;
        $addPurchasePayment->time = date('h:i:s a');
        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchasePayment->month = date('F');
        $addPurchasePayment->year = date('Y');
        $addPurchasePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $addPurchasePayment->card_no = $request->card_no;
            $addPurchasePayment->card_holder = $request->card_holder_name;
            $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
            $addPurchasePayment->card_type = $request->card_type;
            $addPurchasePayment->card_month = $request->month;
            $addPurchasePayment->card_year = $request->year;
            $addPurchasePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addPurchasePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addPurchasePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addPurchasePayment->transaction_no = $request->transaction_no;
        }
        $addPurchasePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $addPurchasePayment->attachment = $purchasePaymentAttachmentName;
        }

        $addPurchasePayment->save();
        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->debit += $request->amount;
            $account->balance -= $request->amount;
            $account->save();

            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->amount;
            $addCashFlow->balance = $account->balance;
            $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
            $addCashFlow->transaction_type = 3;
            $addCashFlow->cash_type = 1;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
        }

        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->supplier_id = $supplier->id;
        $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
        $addSupplierLedger->row_type = 2;
        $addSupplierLedger->save();
        return response()->json('Successfully payment is added.');
    }

    public function paymentEdit($paymentId)
    {
        $accounts = DB::table('accounts')->get();
        $payment = PurchasePayment::with(['purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier'])
            ->where('id', $paymentId)->first();
        return view('purchases.ajax_view.purchase_payment_edit_modal', compact('payment', 'accounts'));
    }

    public function paymentUpdate(Request $request, $paymentId)
    {
        $updatePurchasePayment = PurchasePayment::with('account', 'supplier', 'purchase', 'purchase.purchase_return', 'cashFlow')->where('id', $paymentId)->first();

        //Update Supplier due 
        $updatePurchasePayment->supplier->total_paid -= $updatePurchasePayment->paid_amount;
        $updatePurchasePayment->supplier->total_paid += $request->amount;
        $updatePurchasePayment->supplier->total_purchase_due += $updatePurchasePayment->paid_amount;
        $updatePurchasePayment->supplier->total_purchase_due -= $request->amount;
        $updatePurchasePayment->supplier->save();

        // Update previoues account and delete previous cashflow.
        if ($updatePurchasePayment->account) {
            $updatePurchasePayment->account->debit -= $updatePurchasePayment->paid_amount;
            $updatePurchasePayment->account->balance += $updatePurchasePayment->paid_amount;
            $updatePurchasePayment->account->save();
            //$updatePurchasePayment->cashFlow->delete();
        }

        // update purchase payment
        $updatePurchasePayment->account_id = $request->account_id;
        $updatePurchasePayment->pay_mode = $request->payment_method;
        $updatePurchasePayment->paid_amount = $request->amount;
        $updatePurchasePayment->date = $request->date;
        $updatePurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updatePurchasePayment->month = date('F');
        $updatePurchasePayment->year = date('Y');
        $updatePurchasePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updatePurchasePayment->card_no = $request->card_no;
            $updatePurchasePayment->card_holder = $request->card_holder_name;
            $updatePurchasePayment->card_transaction_no = $request->card_transaction_no;
            $updatePurchasePayment->card_type = $request->card_type;
            $updatePurchasePayment->card_month = $request->month;
            $updatePurchasePayment->card_year = $request->year;
            $updatePurchasePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updatePurchasePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updatePurchasePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updatePurchasePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updatePurchasePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updatePurchasePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updatePurchasePayment->attachment));
                }
            }
            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $updatePurchasePayment->attachment = $purchasePaymentAttachmentName;
        }
        $updatePurchasePayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->debit += $request->amount;
            $account->balance -= $request->amount;
            $account->save();

            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)
                ->where('purchase_payment_id', $updatePurchasePayment->id)->first();
            if ($cashFlow) {
                $cashFlow->debit = $request->amount;
                $cashFlow->balance = $account->balance;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->admin_id = auth()->user()->id;
                $cashFlow->save();
            } else {
                if ($updatePurchasePayment->cashFlow) {
                    $updatePurchasePayment->cashFlow->delete();
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->amount;
                $addCashFlow->balance = $account->balance;
                $addCashFlow->purchase_payment_id = $updatePurchasePayment->id;
                $addCashFlow->transaction_type = 3;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
            }
        } else {
            if ($updatePurchasePayment->cashFlow) {
                $updatePurchasePayment->cashFlow->delete();
            }
        }

        return response()->json('Successfully payment is updated.');
    }

    public function returnPaymentModal($purchaseId)
    {
        $accounts = DB::table('accounts')->get();
        $purchase = Purchase::with(['supplier', 'branch', 'warehouse'])->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.purchase_return_payment', compact('purchase', 'accounts'));
    }

    public function returnPaymentStore(Request $request, $purchaseId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['purchase_payment'];
        $purchase = Purchase::with(['purchase_return'])->where('id', $purchaseId)->first();
        //Update Supplier due 
        $supplier = Supplier::where('id', $purchase->supplier_id)->first();
        $supplier->total_purchase_return_due -= $request->amount;
        $supplier->save();

        // Update purchase
        $purchase->purchase_return_due -= $request->amount;
        $purchase->save();

        // update purchase return
        if ($purchase->purchase_return) {
            $purchase->purchase_return->total_return_due_received += $request->amount;
            $purchase->purchase_return->total_return_due -= $request->amount;
            $purchase->purchase_return->save();
        }
        
        // generate invoice ID
        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }
        // Add purchase payment
        $addPurchasePayment = new PurchasePayment();
        $addPurchasePayment->invoice_id = 'PRPR' . date('dmy') . $invoiceId;
        $addPurchasePayment->purchase_id = $purchase->id;
        $addPurchasePayment->supplier_id = $purchase->supplier_id;
        $addPurchasePayment->account_id = $request->account_id;
        $addPurchasePayment->pay_mode = $request->payment_method;
        $addPurchasePayment->paid_amount = $request->amount;
        $addPurchasePayment->payment_type = 2;
        $addPurchasePayment->date = $request->date;
        $addPurchasePayment->time = date('h:i:s a');
        $addPurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchasePayment->month = date('F');
        $addPurchasePayment->year = date('Y');
        $addPurchasePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $addPurchasePayment->card_no = $request->card_no;
            $addPurchasePayment->card_holder = $request->card_holder_name;
            $addPurchasePayment->card_transaction_no = $request->card_transaction_no;
            $addPurchasePayment->card_type = $request->card_type;
            $addPurchasePayment->card_month = $request->month;
            $addPurchasePayment->card_year = $request->year;
            $addPurchasePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $addPurchasePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $addPurchasePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $addPurchasePayment->transaction_no = $request->transaction_no;
        }
        $addPurchasePayment->admin_id = auth()->user()->id;

        if ($request->hasFile('attachment')) {
            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $addPurchasePayment->attachment = $purchasePaymentAttachmentName;
        }

        $addPurchasePayment->save();

        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->credit += $request->amount;
            $account->balance += $request->amount;
            $account->save();

            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->credit = $request->amount;
            $addCashFlow->balance = $account->balance;
            $addCashFlow->purchase_payment_id = $addPurchasePayment->id;
            $addCashFlow->transaction_type = 3;
            $addCashFlow->cash_type = 2;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
        }

        $addSupplierLedger = new SupplierLedger();
        $addSupplierLedger->supplier_id = $supplier->id;
        $addSupplierLedger->purchase_payment_id = $addPurchasePayment->id;
        $addSupplierLedger->row_type = 2;
        $addSupplierLedger->save();

        return response()->json('Successfully payment is added.');
    }

    public function returnPaymentEdit($paymentId)
    {
        $accounts = DB::table('accounts')->get();
        $payment = PurchasePayment::with(['purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier'])
            ->where('id', $paymentId)->first();
        return view('purchases.ajax_view.purchase_return_payment_edit', compact('payment', 'accounts'));
    }

    public function returnPaymentUpdate(Request $request, $paymentId)
    {
        $updatePurchasePayment = PurchasePayment::with('account', 'supplier', 'purchase', 'purchase.purchase_return', 'cashFlow')
            ->where('id', $paymentId)
            ->first();

        //Update Supplier due 
        $updatePurchasePayment->supplier->total_purchase_return_due += $updatePurchasePayment->paid_amount;
        $updatePurchasePayment->supplier->total_purchase_return_due -= $request->amount;
        $updatePurchasePayment->supplier->save();

        // Update purchase 
        $updatePurchasePayment->purchase->purchase_return_due += $updatePurchasePayment->paid_amount;
        $updatePurchasePayment->purchase->purchase_return_due -= $request->amount;
        $updatePurchasePayment->purchase->save();

        // Update purchase return
        $updatePurchasePayment->purchase->purchase_return->total_return_due_received -= $updatePurchasePayment->paid_amount;
        $updatePurchasePayment->purchase->purchase_return->total_return_due_received += $request->amount;
        $updatePurchasePayment->purchase->purchase_return->total_return_due += $updatePurchasePayment->paid_amount;
        $updatePurchasePayment->purchase->purchase_return->total_return_due -= $request->amount;

        $updatePurchasePayment->purchase->purchase_return->save();

        // Update previoues account and delete previous cashflow.
        if ($updatePurchasePayment->account) {
            $updatePurchasePayment->account->credit -= $updatePurchasePayment->paid_amount;
            $updatePurchasePayment->account->balance -= $updatePurchasePayment->paid_amount;
            $updatePurchasePayment->account->save();
            //$updatePurchasePayment->cashFlow->delete();
        }

        // update purchase payment
        $updatePurchasePayment->account_id = $request->account_id;
        $updatePurchasePayment->pay_mode = $request->payment_method;
        $updatePurchasePayment->paid_amount = $request->amount;
        $updatePurchasePayment->date = $request->date;
        $updatePurchasePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updatePurchasePayment->month = date('F');
        $updatePurchasePayment->year = date('Y');
        $updatePurchasePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updatePurchasePayment->card_no = $request->card_no;
            $updatePurchasePayment->card_holder = $request->card_holder_name;
            $updatePurchasePayment->card_transaction_no = $request->card_transaction_no;
            $updatePurchasePayment->card_type = $request->card_type;
            $updatePurchasePayment->card_month = $request->month;
            $updatePurchasePayment->card_year = $request->year;
            $updatePurchasePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updatePurchasePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updatePurchasePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updatePurchasePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updatePurchasePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updatePurchasePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updatePurchasePayment->attachment));
                }
            }
            $purchasePaymentAttachment = $request->file('attachment');
            $purchasePaymentAttachmentName = uniqid() . '-' . '.' . $purchasePaymentAttachment->getClientOriginalExtension();
            $purchasePaymentAttachment->move(public_path('uploads/payment_attachment/'), $purchasePaymentAttachmentName);
            $updatePurchasePayment->attachment = $purchasePaymentAttachmentName;
        }
        $updatePurchasePayment->save();


        if ($request->account_id) {
            // update account
            $account = Account::where('id', $request->account_id)->first();
            $account->credit += $request->amount;
            $account->balance += $request->amount;
            $account->save();

            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)
                ->where('purchase_payment_id', $updatePurchasePayment->id)->first();
            if ($cashFlow) {
                $cashFlow->credit = $request->amount;
                $cashFlow->balance = $account->balance;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->admin_id = auth()->user()->id;
                $cashFlow->save();
            } else {
                if ($updatePurchasePayment->cashFlow) {
                    $updatePurchasePayment->cashFlow->delete();
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->credit = $request->amount;
                $addCashFlow->balance = $account->balance;
                $addCashFlow->purchase_payment_id = $updatePurchasePayment->id;
                $addCashFlow->transaction_type = 3;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
            }
        } else {
            if ($updatePurchasePayment->cashFlow) {
                $updatePurchasePayment->cashFlow->delete();
            }
        }
        return response()->json('Successfully payment is updated.');
    }

    //Get purchase wise payment list
    public function paymentList($purchaseId)
    {
        $purchase = Purchase::with(['supplier', 'purchase_payments', 'purchase_payments.account'])
            ->where('id', $purchaseId)
            ->first();
        return view('purchases.ajax_view.view_payment_list', compact('purchase'));
    }

    public function paymentDetails($paymentId)
    {
        $payment = PurchasePayment::with('purchase', 'purchase.branch', 'purchase.warehouse', 'purchase.supplier')->where('id', $paymentId)->first();
        return view('purchases.ajax_view.payment_details', compact('payment'));
    }

    // Delete purchase payment
    public function paymentDelete(Request $request, $paymentId)
    {
        $deletePurchasePayment = PurchasePayment::with('account', 'purchase', 'cashFlow')->where('id', $paymentId)->first();

        if (!is_null($deletePurchasePayment)) {
            //Update Supplier due 
            $deletePurchasePayment->supplier->total_purchase_due += $deletePurchasePayment->paid_amount;
            $deletePurchasePayment->supplier->save();

            // Update purchase 
            $deletePurchasePayment->purchase->paid -= $deletePurchasePayment->paid_amount;
            $deletePurchasePayment->purchase->due += $deletePurchasePayment->paid_amount;
            $deletePurchasePayment->purchase->save();

            // Update previoues account and delete previous cashflow.
            if ($deletePurchasePayment->account) {
                $deletePurchasePayment->account->debit -= $deletePurchasePayment->paid_amount;
                $deletePurchasePayment->account->balance += $deletePurchasePayment->paid_amount;
                $deletePurchasePayment->account->save();
                $deletePurchasePayment->cashFlow->delete();
            }

            if ($deletePurchasePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $deletePurchasePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $deletePurchasePayment->attachment));
                }
            }

            $deletePurchasePayment->delete();
        }
        return response()->json('Successfully payment is deleted.');
    }

    //Show Change status modal
    public function changeStatusModal($purchaseId)
    {
        $purchase = DB::table('purchases')->select('id', 'purchase_status')->where('id', $purchaseId)->first();
        return view('purchases.ajax_view.change_status_modal', compact('purchase'));
    }
}
