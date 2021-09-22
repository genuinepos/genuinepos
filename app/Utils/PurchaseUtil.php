<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\ProductWarehouseVariant;
use Yajra\DataTables\Facades\DataTables;


class PurchaseUtil
{
    public function purchaseListTable($request)
    {
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
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']); // Final
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $purchases = $query->select(
                'purchases.*',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as supplier_name',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
            )->orderBy('purchases.report_date', 'desc');
        } else {
            $purchases = $query->select(
                'purchases.*',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as supplier_name',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
            )->where('purchases.branch_id', auth()->user()->branch_id)->orderBy('purchases.report_date', 'desc');
        }

        return DataTables::of($purchases)
            ->addColumn('action', fn ($row) => $this->createAction($row))
            ->editColumn('date', function ($row) use ($generalSettings) {
                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('invoice_id', function ($row) {
                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';
                return $html;
            })->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->warehouse_name) {
                    return $row->warehouse_name . '<b>(WH)</b>';
                } elseif ($row->branch_name) {
                    return $row->branch_name . '<b>(BL)</b>';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . ' (<b>HO</b>)';
                }
            })->editColumn('total_purchase_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->total_purchase_amount . '</b>';
            })->editColumn('paid', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->paid . '</b>';
            })->editColumn('due', function ($row) use ($generalSettings) {
                return '<b><span class="text-danger">' . json_decode($generalSettings->business, true)['currency'].' '. $row->due . '</span></b>';
            })->editColumn('purchase_return_amount', function ($row) use ($generalSettings) {
                return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->purchase_return_amount . '</b>';
            })->editColumn('purchase_return_due', function ($row) use ($generalSettings) {
                return '<b><span class="text-success">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->purchase_return_due . '</span></b>';
            })->editColumn('status', function ($row) {
                if ($row->purchase_status == 1) {
                    return '<span class="text-success"><b>Received</b></span>';
                } elseif ($row->purchase_status == 2) {
                    return '<span class="text-primary"><b>Pending</b></span>';
                } elseif ($row->purchase_status == 3) {
                    return '<span class="text-warning"><b>Ordered</b></span>';
                }
            })->editColumn('payment_status', function ($row) {
                $payable = $row->total_purchase_amount - $row->purchase_return_amount;
                if ($row->due <= 0) {
                    return '<span class="text-success"><b>Paid</b></span>';
                } elseif ($row->due > 0 && $row->due < $payable) {
                    return '<span class="text-primary"><b>Partial</b></span>';
                } elseif ($payable == $row->due) {
                    return '<span class="text-danger"><b>Due</b></span>';
                }
            })->editColumn('created_by', function ($row) {
                return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'from', 'total_purchase_amount', 'paid', 'due', 'purchase_return_amount', 'purchase_return_due', 'payment_status', 'status', 'created_by'])
            ->make(true);
    }


    public function updateStockForPurchaseStore($request)
    {
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $quantities = $request->quantities;

        if (isset($request->warehouse_id)) {
            $index2 = 0;
            foreach ($product_ids as $productId) {
                // add warehouse product
                $productWarehouse = ProductWarehouse::where('warehouse_id', $request->warehouse_id)->where('product_id', $productId)->first();
                if ($productWarehouse) {
                    $productWarehouse->product_quantity += (float)$quantities[$index2];
                    $productWarehouse->save();
                    if ($variant_ids[$index2] != 'noid') {
                        // add warehouse product variant 
                        $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                        if ($productWarehouseVariant) {
                            $productWarehouseVariant->variant_quantity += (float)$quantities[$index2];
                            $productWarehouseVariant->save();
                        } else {
                            $addProductWarehouseVariant = new ProductWarehouseVariant();
                            $addProductWarehouseVariant->product_warehouse_id = $productWarehouse->id;
                            $addProductWarehouseVariant->product_id = $productId;
                            $addProductWarehouseVariant->product_variant_id = $variant_ids[$index2];
                            $addProductWarehouseVariant->variant_quantity = $quantities[$index2];
                            $addProductWarehouseVariant->save();
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
                            $productWarehouseVariant->variant_quantity += (float)$quantities[$index2];
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
            if (auth()->user()->branch_id) {
                foreach ($product_ids as $productId) {
                    // add branch product
                    $productBranch = ProductBranch::where('branch_id', auth()->user()->branch_id)->where('product_id', $productId)->first();
                    if ($productBranch) {
                        $productBranch->product_quantity = $productBranch->product_quantity + $quantities[$index2];
                        $productBranch->save();
                        if ($variant_ids[$index2] != 'noid') {
                            // add product branch variant 
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                            if ($productBranchVariant) {
                                $productBranchVariant->variant_quantity += (float)$quantities[$index2];
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
                            // add product branch variant 
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $addProductBranch->id)->where('product_id', $productId)->where('product_variant_id', $variant_ids[$index2])->first();
                            if ($productBranchVariant) {
                                $productBranchVariant->variant_quantity += (float)$quantities[$index2];
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
            } else {
                $index2 = 0;
                foreach ($product_ids as $productId) {
                    $updateMbStock = Product::where('id', $productId)->first();
                    $updateMbStock->mb_stock += (float)$quantities[$index2];
                    $updateMbStock->save();

                    if ($variant_ids[$index2] != 'noid') {
                        $updateProVariantMbStock = ProductVariant::where('id', $variant_ids[$index2])
                            ->where('product_id', $productId)->first();
                        $updateProVariantMbStock->mb_stock += (float)$quantities[$index2];
                        $updateProVariantMbStock->save();
                    }
                    $index2++;
                }
            }
        }
    }

    public function purchaseProductListTable($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $purchaseProducts = '';
        $query = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.product_variant_id', 'product_variants.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.parent_category_id', 'sub_cate.id')
            ;

        if ($request->product_id) {
            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('purchase_products.product_variant_id', $request->variant_id);
        }

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('purchases.branch_id', NULL);
            } else {
                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_id) {
            $query->where('purchases.supplier_id', $request->supplier_id);
        }

        if ($request->category_id) {
            $query->where('products.category_id', $request->category_id);
        }

        if ($request->sub_category_id) {
            $query->where('products.parent_category_id', $request->sub_category_id);
        }

        if ($request->date_range) {
            $date_range = explode('-', $request->date_range);
            $form_date = date('Y-m-d', strtotime($date_range[0]));
            $to_date = date('Y-m-d', strtotime($date_range[1]));
            $query->whereBetween('purchases.report_date', [$form_date . ' 00:00:00', $to_date . ' 00:00:00']);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 1) {
            $purchaseProducts = $query->select(
                'purchase_products.purchase_id',
                'purchase_products.product_id',
                'purchase_products.product_variant_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity',
                'units.code_name as unit_code',
                'purchase_products.line_total',
                'purchase_products.selling_price',
                'purchases.*',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'suppliers.name as supplier_name'
            )->orderBy('purchases.report_date', 'desc');
        } else {
            $purchaseProducts = $query->select(
                'purchase_products.purchase_id',
                'purchase_products.product_id',
                'purchase_products.product_variant_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity',
                'units.code_name as unit_code',
                'purchase_products.line_total',
                'purchase_products.selling_price',
                'purchases.*',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'suppliers.name as supplier_name'
            )->where('purchases.branch_id', auth()->user()->branch_id)->orderBy('purchases.report_date', 'desc');
        }

        return DataTables::of($purchaseProducts)
            ->addColumn('action', function ($row) {
                $html = '<div class="dropdown table-dropdown">';
                if (auth()->user()->permission->purchase['purchase_edit'] == '1') {
                    $html .= '<a href="' . route('purchases.product.edit', [$row->purchase_id, $row->product_id, ($row->product_variant_id ? $row->product_variant_id : 'NULL')]) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->editColumn('product', function ($row) {
                $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                return $row->name . $variant;
            })->editColumn('product_code', function ($row) {
                return $row->variant_code ? $row->variant_code : $row->product_code;
            })->editColumn('date', function ($row) {
                return date('d/m/Y', strtotime($row->date));
            })->editColumn('quantity', function ($row) {
                return $row->quantity . ' (<span class="qty" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>)';
            })->editColumn('net_unit_cost',  function ($row) use ($generalSettings) {
                return '<b><span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->net_unit_cost . '</span></b>';
            })->editColumn('price',  function ($row) use ($generalSettings) {
                if ($row->selling_price > 0) {
                    return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->selling_price . '</b>';
                } else {
                    if ($row->variant_name) {
                        return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->variant_price . '</b>';
                    } else {
                        return '<b>' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->product_price . '</b>';
                    }
                }
                return '<b><span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->net_unit_cost . '</span></b>';
            })->editColumn('subtotal', function ($row) use ($generalSettings) {
                return '<b><span class="subtotal" data-value="' . $row->line_total . '">' . json_decode($generalSettings->business, true)['currency'] . ' ' . $row->line_total . '</span></b>';
            })->rawColumns(['action', 'product', 'product_code', 'date', 'quantity', 'branch', 'net_unit_cost', 'price', 'subtotal'])
            ->make(true);
    }

    private function createAction($row)
    {
        $html = '<div class="btn-group" role="group">';
        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item details_button" href="' . route('purchases.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';
        $html .= '<a class="dropdown-item" href="' . route('barcode.on.purchase.barcode', $row->id) . '"><i class="fas fa-barcode text-primary"></i> Barcode</a>';

        if (auth()->user()->branch_id == $row->branch_id) {
            if (auth()->user()->permission->purchase['purchase_payment'] == '1') {
                if ($row->due > 0) {
                    $html .= '<a class="dropdown-item" data-type="1" id="add_payment" href="' . route('purchases.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Add Payment</a>';
                }

                if ($row->purchase_return_due > 0) {
                    $html .= '<a class="dropdown-item" id="add_return_payment" href="' . route('purchases.return.payment.modal', [$row->id]) . '"><i class="far fa-money-bill-alt text-primary"></i> Receive Return Amount</a>';
                }

                $html .= '<a class="dropdown-item" id="view_payment" href="' . route('purchase.payment.list', $row->id) . '"><i class="far fa-money-bill-alt text-primary"></i> View Payment</a>';
            }

            if (auth()->user()->permission->purchase['purchase_edit'] == '1') {
                $html .= '<a class="dropdown-item" href="' . route('purchases.edit', $row->id) . ' "><i class="far fa-edit text-primary"></i> Edit</a>';
            }

            if (auth()->user()->permission->purchase['purchase_delete'] == '1') {
                $html .= '<a class="dropdown-item" id="delete" href="' . route('purchase.delete', $row->id) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
            }

            if (auth()->user()->permission->purchase['purchase_return'] == '1') {
                $html .= '<a class="dropdown-item" id="purchase_return" href="' . route('purchases.returns.create', $row->id) . '"><i class="fas fa-undo-alt text-primary"></i> Purchase Return</a>';
            }
            $html .= '<a class="dropdown-item" id="change_status" href="' . route('purchases.change.status.modal', $row->id) . '"><i class="far fa-edit text-primary"></i> Update Status</a>';
        }

        $html .= '<a class="dropdown-item" id="items_notification" href=""><i class="fas fa-envelope text-primary"></i> Items Received Notification</a>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    public function adjustPurchaseInvoiceAmounts($purchase)
    {
        $totalPurchasePaid = DB::table('purchase_payments')
        ->where('purchase_payments.purchase_id', $purchase->id)->where('payment_type', 1)
        ->select(DB::raw('sum(paid_amount) as total_paid'))
        ->groupBy('purchase_payments.purchase_id')
        ->get();

        $totalReturnPaid = DB::table('purchase_payments')
        ->where('purchase_payments.purchase_id', $purchase->id)->where('payment_type', 2)
        ->select(DB::raw('sum(paid_amount) as total_paid'))
        ->groupBy('purchase_payments.purchase_id')
        ->get();

        $return = DB::table('purchase_returns')->where('purchase_id', $purchase->id)->first();
        $returnAmount = $return ? $return->total_return_amount : 0;

        $due = $purchase->total_purchase_amount - $totalPurchasePaid->sum('total_paid') - $returnAmount + $totalReturnPaid->sum('total_paid')  ;

        $returnDue = $returnAmount 
                    - ($purchase->total_purchase_amount - $totalPurchasePaid->sum('total_paid'))
                    - $totalReturnPaid->sum('total_paid');

        $purchase->paid = $totalPurchasePaid->sum('total_paid');
        $purchase->due = $due;
        $purchase->purchase_return_amount = $returnAmount;
        $purchase->purchase_return_due = $returnDue > 0 ? $returnDue : 0;
        $purchase->save();
    }
}
