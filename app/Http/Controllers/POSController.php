<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Account;
use App\Models\Product;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Models\CustomerLedger;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\CashRegisterTransaction;
use App\Models\ProductWarehouseVariant;

class POSController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Create pos view 
    public function create()
    {
        if (auth()->user()->permission->sale['pos_add'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $openedCashRegister = CashRegister::where('admin_id', auth()->user()->id)
            ->where('status', 1)
            ->first();
        if ($openedCashRegister) {
            $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
            $brands = DB::table('brands')->get(['id', 'name']);
            $customers = DB::table('customers')->where('status', 1)->get(['id', 'name', 'phone']);
            return view('sales.pos.create', compact('openedCashRegister', 'categories', 'brands', 'customers'));
        } else {
            return redirect()->route('sales.cash.register.create');
        }
    }

    // Store pos sale
    public function store(Request $request)
    {
        //return $request->all();
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['sale_invoice'];
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $branchInvoiceSchema = DB::table('branches')
            ->leftJoin('invoice_schemas', 'branches.invoice_schema_id', 'invoice_schemas.id')
            ->where('branches.id', auth()->user()->branch_id)
            ->select(
                'branches.*',
                'invoice_schemas.id as schema_id',
                'invoice_schemas.prefix',
                'invoice_schemas.start_from',
            )
            ->first();

        $invoicePrefix = '';
        if ($branchInvoiceSchema && $branchInvoiceSchema->prefix !== null) {
            $invoicePrefix = $branchInvoiceSchema->prefix . $branchInvoiceSchema->start_from;
        } else {
            $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();
            $invoicePrefix = $defaultSchemas->prefix . $defaultSchemas->start_from;
        }

        //return $request->all();
        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty']);
        }

        if ($request->action == 1) {
            if ($request->paying_amount < $request->total_payable_amount && !$request->customer_id) {
                return response()->json(['errorMsg' => 'Listed customer is required when sale is due or partial.']);
            }
        }

        if ($request->button_type == 1 && $request->paying_amount == 0) {
            return response()->json(['errorMsg' => 'If you want to sale in full credit, so click credit sale button.']);
        }

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        $addSale = new Sale();
        $addSale->invoice_id = $request->invoice_id ? $request->invoice_id : $invoicePrefix . date('ymd') . $invoiceId;
        $addSale->admin_id = auth()->user()->id;

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $addSale->warehouse_id = $request->warehouse_id;
        } else {
            $addSale->branch_id = auth()->user()->branch_id;
        }

        // $addSale->customer_id = $request->customer_id;
        $addSale->customer_id = $request->customer_id != 0 ? $request->customer_id : NULL;
        $addSale->status = $request->action;

        if ($request->action == 1) {
            $addSale->is_fixed_challen = 1;
        }

        if ($request->action == 5) {
            $holdInvoice = Sale::where('branch_id', auth()->user()->branch_id)->where('status', 5)->where('admin_id', auth()->user()->id)->get();
            if ($holdInvoice->count() == 5) {
                return response()->json(['errorMsg' => 'You can hold only 5 invoices.']);
            }
        }

        $addSale->date = date('d-m-Y');
        $addSale->time = date('h:i:s a');
        $addSale->report_date = date('Y-m-d h:i:s');
        $addSale->month = date('F');
        $addSale->year = date('Y');
        $addSale->total_item = $request->total_item;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = 1;
        $addSale->order_discount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $addSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $addSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $addSale->shipment_charge = 0.00;
        $addSale->created_by = 2;
        //
        $changedAmount = $request->change_amount >= 0 ? $request->change_amount : 0.00;
        $paidAmount = $request->paying_amount - $changedAmount;
        //

        if ($request->action == 1) {
            $changedAmount = $request->change_amount >= 0 ? $request->change_amount : 0.00;
            $paidAmount = $request->paying_amount - $changedAmount;
            if ($request->previous_due > 0) {
                $addSale->total_payable_amount = $request->total_invoice_payable;
                if ($paidAmount >= $request->total_invoice_payable) {
                    $addSale->paid = $request->total_invoice_payable;
                    $addSale->due = 0.00;
                    $payingPreviousDue = $paidAmount - $request->total_invoice_payable; // Comming Soon;
                } elseif ($paidAmount < $request->total_invoice_payable) {
                    $addSale->paid = $request->paying_amount;
                    $calcDue = $request->total_invoice_payable - $request->paying_amount;
                    $addSale->due = $calcDue;
                }
            } else {
                $addSale->total_payable_amount = $request->total_payable_amount;
                $addSale->paid = $request->paying_amount - $changedAmount;
                $addSale->change_amount = $request->change_amount >= 0 ? $request->change_amount : 0.00;
                $addSale->due = $request->total_due >= 0 ? $request->total_due : 0.00;
            }

            $addSale->save();
            $customer = Customer::where('id', $request->customer_id)->first();
            if ($customer) {
                $customer->total_sale += $request->total_payable_amount - $request->previous_due;
                $customer->total_paid += $request->paying_amount ? $request->paying_amount : 0;
                if ($request->paying_amount <= 0) {
                    $customer->total_sale_due = $request->total_payable_amount;
                } else {
                    if ($request->total_due > 0) {
                        $customer->total_sale_due = $request->total_due;
                    } else {
                        $customer->total_sale_due = 0;
                    }
                }

                $customer->save();
                $addCustomerLedger = new CustomerLedger();
                $addCustomerLedger->customer_id = $request->customer_id;
                $addCustomerLedger->sale_id = $addSale->id;
                $addCustomerLedger->save();
            }
        } else {
            $addSale->total_payable_amount = $request->total_invoice_payable;
            $addSale->save();
        }

        $addSale->save();
        // update product quantity
        $quantities = $request->quantities;
        $units = $request->units;
        $descriptions = $request->descriptions;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $unit_discount_types = $request->unit_discount_types;
        $unit_discounts = $request->unit_discounts;
        $unit_discount_amounts = $request->unit_discount_amounts;
        $unit_tax_percents = $request->unit_tax_percents;
        $unit_tax_amounts = $request->unit_tax_amounts;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $unit_prices_exc_tax = $request->unit_prices_exc_tax;
        $unit_prices_inc_tax = $request->unit_prices_inc_tax;
        $subtotals = $request->subtotals;

        // update product quantity and add sale product
        $index = 0;
        foreach ($product_ids as $product_id) {
            if ($request->action == 1) {
                $updateProductQty = Product::where('id', $product_id)->first();
                if ($updateProductQty->type == 1) {
                    $updateProductQty->quantity -= $quantities[$index];
                    $updateProductQty->number_of_sale += $quantities[$index];
                    $updateProductQty->save();

                    if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

                        $updateWarehouseProductQty = ProductWarehouse::where('warehouse_id', $request->warehouse_id)
                            ->where('product_id', $product_id)
                            ->first();
                        $updateWarehouseProductQty->product_quantity -= $quantities[$index];
                        $updateWarehouseProductQty->save();

                        if ($variant_ids[$index] != 'noid') {
                            $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])
                                ->where('product_id', $product_id)
                                ->first();
                            $updateProductVariant->variant_quantity -= $quantities[$index];
                            $updateProductVariant->number_of_sale += $quantities[$index];
                            $updateProductVariant->save();

                            $updateProductWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $updateWarehouseProductQty->id)->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                            $updateProductWarehouseVariant->variant_quantity -= $quantities[$index];
                            $updateProductWarehouseVariant->save();
                        }
                    } else {
                        $updateBranchProductQty = ProductBranch::where('branch_id', $request->branch_id)->where('product_id', $product_id)->first();
                        $updateBranchProductQty->product_quantity -= $quantities[$index];
                        $updateBranchProductQty->save();

                        if ($variant_ids[$index] != 'noid') {
                            $updateProductVariant = ProductVariant::where('id', $variant_ids[$index])->where('product_id', $product_id)->first();
                            $updateProductVariant->variant_quantity -= $quantities[$index];
                            $updateProductVariant->number_of_sale += $quantities[$index];
                            $updateProductVariant->save();

                            $updateProductBranchVariant = ProductBranchVariant::where('product_branch_id', $updateBranchProductQty->id)->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])->first();
                            $updateProductBranchVariant->variant_quantity -= $quantities[$index];
                            $updateProductBranchVariant->save();
                        }
                    }
                }
            }

            $addSaleProduct = new SaleProduct();
            $addSaleProduct->sale_id = $addSale->id;
            $addSaleProduct->product_id = $product_id;
            $addSaleProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $addSaleProduct->quantity = $quantities[$index];
            $addSaleProduct->unit_discount_type = $unit_discount_types[$index];
            $addSaleProduct->unit_discount = $unit_discounts[$index];
            $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$index];
            $addSaleProduct->unit_tax_percent = $unit_tax_percents[$index];
            $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$index];
            $addSaleProduct->unit = $units[$index];
            $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
            $addSaleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$index];
            $addSaleProduct->unit_price_inc_tax = $unit_prices_inc_tax[$index];
            $addSaleProduct->description = $descriptions[$index] ? $descriptions[$index] : NULL;
            $addSaleProduct->subtotal = $subtotals[$index];
            $addSaleProduct->save();
            $index++;
        }

        if ($request->customer_id) {
            $addCustomerLedger = new CustomerLedger();
            $addCustomerLedger->customer_id = $request->customer_id;
            $addCustomerLedger->sale_id = $addSale->id;
            $addCustomerLedger->save();
        }

        // Add sale payment
        if ($request->action == 1) {
            $this->salePayment($request, $addSale, $paymentInvoicePrefix, $invoiceId);
        }

        // Add cash register transaction
        $addCashRegisterTransaction = new CashRegisterTransaction();
        $addCashRegisterTransaction->cash_register_id = $request->cash_register_id;
        $addCashRegisterTransaction->sale_id = $addSale->id;
        $addCashRegisterTransaction->save();
        // Add cash register transaction end..

        $previous_due = $request->previous_due;
        $total_payable_amount = $request->total_payable_amount;
        $paying_amount = $request->paying_amount;
        $total_due = $request->total_due;
        $change_amount = $request->change_amount;

        $sale = Sale::with([
            'customer',
            'branch',
            'branch.add_sale_invoice_layout',
            'branch.pos_sale_invoice_layout',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'admin'
        ])->where('id', $addSale->id)->first();

        if ($request->action == 1) {
            return view('sales.save_and_print_template.pos_sale_print', compact(
                'sale',
                'previous_due',
                'total_payable_amount',
                'paying_amount',
                'total_due',
                'change_amount'
            ));
        } elseif ($request->action == 2) {
            return view('sales.save_and_print_template.draft_print', compact('sale'));
        } elseif ($request->action == 4) {
            return view('sales.save_and_print_template.quotation_print', compact('sale'));
        } elseif ($request->action == 5) {
            return response()->json(['holdInvoiceMsg' => 'Invoice is holded.']);
        } elseif ($request->action == 6) {
            return response()->json(['suspendMsg' => 'Invoice is suspended.']);
        }
    }

    // Pick Hold invoice **requested by ajax**
    public function pickHoldInvoice()
    {
        $holdInvoices = Sale::where('branch_id', auth()->user()->branch_id)->where('status', 5)->where('admin_id', auth()->user()->id)->get();
        return view('sales.pos.ajax_view.hold_invoice_list', compact('holdInvoices'));
    }

    // Get invoice info by edit invoice method
    public function edit($saleId)
    {
        $sale = Sale::with('branch', 'sale_products', 'customer', 'admin', 'admin.role')->where('id', $saleId)->first();
        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
        $brands = DB::table('brands')->get(['id', 'name']);
        return view('sales.pos.edit', compact('sale', 'categories', 'brands'));
    }

    // Get invoice products **requested by ajax**
    public function invoiceProducts($saleId)
    {
        $invoiceProducts = SaleProduct::with(['sale', 'product', 'variant'])->where('sale_id', $saleId)->get();
        $qty_limits = [];
        foreach ($invoiceProducts as $sale_product) {
            if ($sale_product->sale->branch_id) {
                $productBranch = ProductBranch::where('branch_id', $sale_product->sale->branch_id)
                    ->where('product_id', $sale_product->product_id)->first();
                if ($sale_product->product->type == 2) {
                    $qty_limits[] = 500000;
                } elseif ($sale_product->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id', $sale_product->product_variant_id)
                        ->first();
                    $qty_limits[] = $productBranchVariant->variant_quantity;
                } else {
                    $qty_limits[] = $productBranch->product_quantity;
                }
            } else {
                $productWarehouse = ProductWarehouse::where('warehouse_id', $sale_product->sale->warehouse_id)
                    ->where('product_id', $sale_product->product_id)->first();
                if ($sale_product->product->type == 2) {
                    $qty_limits[] = 500000;
                } elseif ($sale_product->product_variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id', $sale_product->product_variant_id)
                        ->first();
                    $qty_limits[] = $productWarehouseVariant->variant_quantity;
                } else {
                    $qty_limits[] = $productWarehouse->product_quantity;
                }
            }
        }
        return view('sales.pos.ajax_view.invoice_product_list', compact('invoiceProducts', 'qty_limits'));
    }

    // update pos sale
    public function update(Request $request)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $updateSale = Sale::with(['sale_payments', 'sale_products', 'sale_products.product', 'sale_products.variant', 'sale_products.product.comboProducts'])->where('id', $request->sale_id)->first();
        //return $request->all();
        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty']);
        }

        if ($updateSale->status == 1 && $request->action != 1) {
            return response()->json(['errorMsg' => 'Final sale you can not update to quotation, draft, hold invoice or Suspend.']);
        }

        if ($request->action == 1) {
            if ($request->paying_amount < $request->total_payable_amount && !$updateSale->customer_id) {
                return response()->json(['errorMsg' => 'Listed Customer is required when sale is credit or partial payment.']);
            }
        }

        foreach ($updateSale->sale_payments as $sale_payment) {
            if ($sale_payment->account_id) {
                $account = Account::where('id', $sale_payment->account_id)->first();
                $account->credit -= $sale_payment->paid_amount;
                $account->balance -= $sale_payment->paid_amount;
                $account->save();
            }
            $sale_payment->delete();
        }

        // generate invoice ID
        $i = 6;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }

        // Update customer due
        if ($request->action == 1) {
            $customer = Customer::where('id', $updateSale->customer_id)->first();
            if ($customer) {
                $customer->total_sale -= $updateSale->total_payable_amount;
                $customer->total_sale += $request->total_payable_amount;

                if ($request->total_due > 0) {
                    $customer->total_sale_due -= $updateSale->due;
                    $customer->total_sale_due += $request->total_due;
                }

                $customer->save();
            }
        }

        $updateSale->status = $request->action;
        $updateSale->date = date('d-m-Y');
        $updateSale->report_date = date('Y-m-d h:m:i');
        $updateSale->month = date('F');
        $updateSale->year = date('Y');
        //$addSale->pay_term = $request->pay_term;
        //$addSale->pay_term_number = $request->pay_term_number;
        $updateSale->total_item = $request->total_item;
        $updateSale->net_total_amount = $request->net_total_amount;
        $updateSale->order_discount_type = 1;
        $updateSale->order_discount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $updateSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $updateSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $updateSale->shipment_charge = 0.00;
        $updateSale->total_payable_amount = $request->total_payable_amount;

        $updateSale->change_amount = $request->change_amount >= 0 ? $request->change_amount : 0.00;

        if ($request->action == 1) {
            if ($request->paying_amount == 0) {
                $updateSale->due = $request->total_payable_amount;
            } else {
                $updateSale->paid = $request->paying_amount;
                if ($request->total_due > 0) {
                    $updateSale->due = $request->total_due;
                } else {
                    $updateSale->due = 0.00;
                }
            }
        } else {
            $updateSale->total_payable_amount += $request->total_payable_amount;
        }

        $updateSale->save();

        // Add product quantity for adjustment
        foreach ($updateSale->sale_products as $sale_product) {
            $sale_product->delete_in_update = 1;
            $sale_product->save();
            if ($updateSale->status == 1) {
                if ($sale_product->product->type == 1) {
                    $sale_product->product->quantity += $sale_product->quantity;
                    $sale_product->product->number_of_sale -= $sale_product->quantity;
                    $sale_product->product->save();
                    if ($sale_product->product_variant_id) {
                        $sale_product->variant->variant_quantity += $sale_product->quantity;
                        $sale_product->variant->number_of_sale -= $sale_product->quantity;
                        $sale_product->variant->save();
                    }

                    if ($updateSale->branch_id) {
                        $productBranch = ProductBranch::where('branch_id', $updateSale->branch_id)
                            ->where('product_id', $sale_product->product_id)
                            ->first();
                        $productBranch->product_quantity += $sale_product->quantity;
                        $productBranch->save();
                        if ($sale_product->product_variant_id) {
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                                ->where('product_id', $sale_product->product_id)
                                ->where('product_variant_id', $sale_product->product_variant_id)
                                ->first();
                            $productBranchVariant->variant_quantity += $sale_product->quantity;
                            $productBranchVariant->save();
                        }
                    } else {
                        $productWarehouse = ProductWarehouse::where('warehouse_id', $updateSale->warehouse_id)
                            ->where('product_id', $sale_product->product_id)
                            ->first();
                        $productWarehouse->product_quantity += $sale_product->quantity;
                        $productWarehouse->save();
                        if ($sale_product->product_variant_id) {
                            $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
                                ->where('product_id', $sale_product->product_id)
                                ->where('product_variant_id', $sale_product->product_variant_id)
                                ->first();
                            $productWarehouseVariant->variant_quantity += $sale_product->quantity;
                            $productWarehouseVariant->save();
                        }
                    }
                }
            }
        }

        // update product quantity
        $quantities = $request->quantities;
        $descriptions = $request->descriptions;
        $units = $request->units;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $unit_discount_types = $request->unit_discount_types;
        $unit_discounts = $request->unit_discounts;
        $unit_discount_amounts = $request->unit_discount_amounts;
        $unit_tax_percents = $request->unit_tax_percents;
        $unit_tax_amounts = $request->unit_tax_amounts;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $unit_prices_exc_tax = $request->unit_prices_exc_tax;
        $unit_prices_inc_tax = $request->unit_prices_inc_tax;
        $subtotals = $request->subtotals;

        $index = 0;
        foreach ($product_ids as $product_id) {
            if ($request->action == 1) {
                $product = Product::where('id', $product_id)->first();
                if ($product->type == 1) {
                    $product->quantity -= $quantities[$index];
                    $product->number_of_sale += $quantities[$index];
                    $product->save();

                    if ($updateSale->branch_id) {
                        $productBranch = ProductBranch::where('branch_id', $updateSale->branch_id)
                            ->where('product_id', $product_id)->first();
                        $productBranch->product_quantity -= $quantities[$index];
                        $productBranch->save();
                    } else {
                        $productWarehouse = ProductWarehouse::where('warehouse_id', $updateSale->warehouse_id)
                            ->where('product_id', $product_id)->first();
                        $productWarehouse->product_quantity -= $quantities[$index];
                        $productWarehouse->save();
                    }

                    if ($variant_ids[$index] != 'noid') {
                        $productVariant = ProductVariant::where('id', $variant_ids[$index])
                            ->where('product_id', $product_id)->first();
                        $productVariant->variant_quantity -= $quantities[$index];
                        $productVariant->number_of_sale += $quantities[$index];
                        $productVariant->save();

                        if ($updateSale->branch_id) {
                            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                                ->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])
                                ->first();

                            $productBranchVariant->variant_quantity -= $quantities[$index];
                            $productBranchVariant->save();
                        } else {
                            $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
                                ->where('product_id', $product_id)->where('product_variant_id', $variant_ids[$index])
                                ->first();

                            $productWarehouseVariant->variant_quantity -= $quantities[$index];
                            $productWarehouseVariant->save();
                        }
                    }
                }
            }

            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $saleProduct = SaleProduct::where('sale_id', $updateSale->id)->where('product_id', $product_id)->where('product_variant_id', $variant_id)->first();
            if ($saleProduct) {
                $saleProduct->quantity = $quantities[$index];
                $saleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                $saleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$index];
                $saleProduct->unit_price_inc_tax = $unit_prices_inc_tax[$index];
                $saleProduct->unit_discount_type = $unit_discount_types[$index];
                $saleProduct->unit_discount = $unit_discounts[$index];
                $saleProduct->unit_discount_amount = $unit_discount_amounts[$index];
                $saleProduct->unit_tax_percent = $unit_tax_percents[$index];
                $saleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                $saleProduct->unit = $units[$index];
                $saleProduct->subtotal = $subtotals[$index];
                $saleProduct->description = $descriptions[$index];
                $saleProduct->delete_in_update = 0;
                $saleProduct->save();
            } else {
                $addSaleProduct = new SaleProduct();
                $addSaleProduct->sale_id = $updateSale->id;
                $addSaleProduct->product_id = $product_id;
                $addSaleProduct->product_variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $addSaleProduct->quantity = $quantities[$index];
                $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                $addSaleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$index];
                $addSaleProduct->unit_price_inc_tax = $unit_prices_inc_tax[$index];
                $addSaleProduct->unit_discount_type = $unit_discount_types[$index];
                $addSaleProduct->unit_discount = $unit_discounts[$index];
                $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$index];
                $addSaleProduct->unit_tax_percent = $unit_tax_percents[$index];
                $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                $addSaleProduct->unit = $units[$index];
                $addSaleProduct->subtotal = $subtotals[$index];
                $addSaleProduct->description = $descriptions[$index];
                $addSaleProduct->save();
            }
            $index++;
        }

        $deleteNotFoundSaleProducts = SaleProduct::where('sale_id', $updateSale->id)->where('delete_in_update', 1)->get();
        foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {
            $deleteNotFoundSaleProduct->delete();
        }

        // Add new payment 
        if ($request->paying_amount > 0) {
            $addSalePayment = new SalePayment();
            $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
            $addSalePayment->sale_id = $updateSale->id;
            $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
            $addSalePayment->account_id = $request->account_id;
            $addSalePayment->paid_amount = $request->paying_amount;
            $addSalePayment->date = date('d-m-Y');
            $addSalePayment->time = date('h:i:s');
            $addSalePayment->report_date = date('Y-m-d');
            $addSalePayment->month = date('F');
            $addSalePayment->year = date('Y');
            $addSalePayment->pay_mode = $request->payment_method;
            $addSalePayment->note = $request->payment_note;

            if ($request->payment_method == 'Card') {
                $addSalePayment->card_no = $request->card_no;
                $addSalePayment->card_holder = $request->card_holder_name;
                $addSalePayment->card_transaction_no = $request->card_transaction_no;
                $addSalePayment->card_type = $request->card_type;
                $addSalePayment->card_month = $request->month;
                $addSalePayment->card_year = $request->year;
                $addSalePayment->card_secure_code = $request->secure_code;
            } elseif ($request->payment_method == 'Cheque') {
                $addSalePayment->cheque_no = $request->cheque_no;
            } elseif ($request->payment_method == 'Bank-Transfer') {
                $addSalePayment->account_no = $request->account_no;
            } elseif ($request->payment_method == 'Custom') {
                $addSalePayment->transaction_no = $request->transaction_no;
            }

            $addSalePayment->admin_id = auth()->user()->id;
            $addSalePayment->save();

            if ($request->account_id) {
                // update account
                $account = Account::where('id', $request->account_id)->first();
                $account->credit += $request->paying_amount;
                $account->balance += $request->paying_amount;
                $account->save();

                // Add cash flow
                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->credit = $request->paying_amount;
                $addCashFlow->balance = $account->balance;
                $addCashFlow->sale_payment_id = $addSalePayment->id;
                $addCashFlow->transaction_type = 2;
                $addCashFlow->cash_type = 2;
                $addCashFlow->date = date('d-m-Y');
                $addCashFlow->report_date = date('Y-m-d');
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
            }

            if ($request->customer_id) {
                $addCustomerLedger = new CustomerLedger();
                $addCustomerLedger->customer_id = $request->customer_id;
                $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                $addCustomerLedger->row_type = 2;
                $addCustomerLedger->save();
            }
        }

        $previous_due = 0;
        $total_payable_amount = $request->total_payable_amount;
        $paying_amount = $request->paying_amount;
        $total_due = $request->total_due;
        $change_amount = $request->change_amount;

        if ($request->action == 1) {
            $sale = Sale::with(['customer', 'branch', 'sale_products', 'sale_products.product', 'sale_products.variant'])->where('id', $updateSale->id)->first();
            return view('sales.save_and_print_template.pos_sale_print', compact(
                'sale',
                'previous_due',
                'total_payable_amount',
                'paying_amount',
                'total_due',
                'change_amount'
            ));
        } elseif ($request->action == 2) {
            $sale = Sale::with(['customer', 'branch', 'sale_products', 'sale_products.product', 'sale_products.variant'])->where('id', $updateSale->id)->first();
            return view('sales.save_and_print_template.draft_print', compact('sale'));
        } elseif ($request->action == 4) {
            $sale = Sale::with(['customer', 'branch', 'sale_products', 'sale_products.product', 'sale_products.variant'])->where('id', $updateSale->id)->first();
            return view('sales.save_and_print_template.quotation_print', compact('sale'));
        } elseif ($request->action == 5) {
            return response()->json(['holdInvoiceMsg' => 'Holded Invoice is updated successfully.']);
        } elseif ($request->action == 6) {
            return response()->json(['suspendMsg' => 'Suspended invoice is updated.']);
        }
    }

    // Get all recent sales ** requested by ajax **
    public function recentSales()
    {
        $sales = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 1)
            ->where('created_by', 2)
            ->where('is_return_available', 0)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.pos.ajax_view.recent_sale_list', compact('sales'));
    }

    // Get all recent quotations ** requested by ajax **
    public function recentQuotations()
    {
        $quotations = Sale::where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 4)
            ->where('created_by', 2)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.pos.ajax_view.recent_quotation_list', compact('quotations'));
    }

    // Get all recent drafts ** requested by ajax **
    public function recentDrafts()
    {
        $drafts = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 2)
            ->where('created_by', 2)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.pos.ajax_view.recent_draft_list', compact('drafts'));
    }

    // Get all suspended sales ** requested by ajax **
    public function suspendedList()
    {
        $sales = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 6)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();
        return view('sales.pos.ajax_view.suspended_sale_list', compact('sales'));
    }

    // Get recent added product which has been added from pos
    public function getRecentProduct($branch_id, $warehouse_id, $product_id)
    {
        if ($branch_id != 'null') {
            $product = ProductBranch::with(['product', 'product.tax', 'product.unit'])
                ->where('branch_id', $branch_id)
                ->where('product_id', $product_id)
                ->first();
            if ($product->product_quantity > 0) {
                return view('sales.pos.ajax_view.recent_product_view', compact('product'));
            } else {
                return response()->json([
                    'errorMsg' => 'Product is not added in the sale table, cause you did not add any number of opening stock in this branch.'
                ]);
            }
        } else {
            $product = ProductWarehouse::with(['product', 'product.tax', 'product.unit'])
                ->where('warehouse_id', $warehouse_id)
                ->where('product_id', $product_id)
                ->first();
            if ($product->product_quantity > 0) {
                return view('sales.pos.ajax_view.recent_product_view', compact('product'));
            } else {
                return response()->json([
                    'errorMsg' => 'Product is not added in the sale table, cause you did not add any number of opening stock in this warehouse.'
                ]);
            }
        }
    }

    public function addQuickCustomerModal()
    {
        $customerGroups = DB::table('customer_groups')->select('id', 'group_name')->get();
        return view('sales.ajax_view.quick_add_customer', compact('customerGroups'));
    }

    //Add customer from pos
    public function addCustomer(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $addCustomer = Customer::create([
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
            'customer_group_id' => $request->customer_group_id,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'state' => $request->state,
            'shipping_address' => $request->shipping_address,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0.00,
            'total_sale_due' => $request->opening_balance ? $request->opening_balance : 0.00,
        ]);

        if ($request->opening_balance && $request->opening_balance >= 0) {
            $addCustomerLedger = new CustomerLedger();
            $addCustomerLedger->customer_id = $addCustomer->id;
            $addCustomerLedger->row_type = 3;
            $addCustomerLedger->amount = $request->opening_balance;
            $addCustomerLedger->save();
        }

        return response()->json($addCustomer);
    }

    // Get pos product list
    public function posProductList(Request $request)
    {
        //return $request->all();
        $products = '';
        $query = DB::table('products')
            ->leftJoin('taxes', 'products.tax_id', 'taxes.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        if (!$request->category_id  && !$request->brand_id) {
            $query->orderBy('number_of_sale', 'DESC')->limit(90);
        }

        $products = $query->select(
            'products.id',
            'products.number_of_sale',
            'products.thumbnail_photo',
            'products.name',
            'products.product_code',
            'products.product_cost_with_tax',
            'products.profit',
            'products.product_price',
            'products.is_show_emi_on_pos',
            'units.name as unit_name',
            'taxes.id as tax_id',
            'taxes.tax_percent',
            'product_variants.id as variant_id',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_cost_with_tax',
            'product_variants.variant_profit',
            'product_variants.variant_price',
        )->get();

        return view('sales.pos.ajax_view.select_product_list', compact('products'));
    }

    private function salePayment($request, $addSale, $paymentInvoicePrefix, $invoiceId)
    {
        if ($request->paying_amount > 0) {
            $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0.00;
            $paidAmount = $request->paying_amount - $changedAmount;
            if ($request->previous_due > 0) {
                if ($paidAmount >= $request->total_invoice_payable) {
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $addSale->id;
                    $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $request->total_invoice_payable;
                    $addSalePayment->date = date('d-m-Y');
                    $addSalePayment->time = date('h:i:s a');
                    $addSalePayment->report_date = date('Y-m-d');
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->note = $request->payment_note;

                    if ($request->payment_method == 'Card') {
                        $addSalePayment->card_no = $request->card_no;
                        $addSalePayment->card_holder = $request->card_holder_name;
                        $addSalePayment->card_transaction_no = $request->card_transaction_no;
                        $addSalePayment->card_type = $request->card_type;
                        $addSalePayment->card_month = $request->month;
                        $addSalePayment->card_year = $request->year;
                        $addSalePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addSalePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addSalePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addSalePayment->transaction_no = $request->transaction_no;
                    }

                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->credit += $request->total_invoice_payable;
                        $account->balance += $request->total_invoice_payable;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $request->total_invoice_payable;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y');
                        $addCashFlow->report_date = date('Y-m-d');
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($request->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $request->customer_id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }

                    $payingPreviousDue = $paidAmount - $request->total_invoice_payable;
                    if ($payingPreviousDue > 0) {
                        $dueAmounts = $payingPreviousDue;
                        $dueInvoices = Sale::where('customer_id', $request->customer_id)
                            ->where('due', '>', 0)
                            ->get();
                        $index = 0;
                        foreach ($dueInvoices as $dueInvoice) {
                            if ($dueInvoice->due > $dueAmounts) {
                                $dueInvoice->paid += $dueAmounts;
                                $dueInvoice->due -= $dueAmounts;
                                $dueInvoice->save();
                                $addSalePayment = new SalePayment();
                                $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                $addSalePayment->sale_id = $dueInvoice->id;
                                $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                                $addSalePayment->account_id = $request->account_id;
                                $addSalePayment->paid_amount = $dueAmounts;
                                $addSalePayment->date = date('d-m-Y');
                                $addSalePayment->time = date('h:i:s a');
                                $addSalePayment->report_date = date('Y-m-d');
                                $addSalePayment->month = date('F');
                                $addSalePayment->year = date('Y');
                                $addSalePayment->pay_mode = $request->payment_method;
                                $addSalePayment->note = $request->payment_note;

                                if ($request->payment_method == 'Card') {
                                    $addSalePayment->card_no = $request->card_no;
                                    $addSalePayment->card_holder = $request->card_holder_name;
                                    $addSalePayment->card_transaction_no = $request->card_transaction_no;
                                    $addSalePayment->card_type = $request->card_type;
                                    $addSalePayment->card_month = $request->month;
                                    $addSalePayment->card_year = $request->year;
                                    $addSalePayment->card_secure_code = $request->secure_code;
                                } elseif ($request->payment_method == 'Cheque') {
                                    $addSalePayment->cheque_no = $request->cheque_no;
                                } elseif ($request->payment_method == 'Bank-Transfer') {
                                    $addSalePayment->account_no = $request->account_no;
                                } elseif ($request->payment_method == 'Custom') {
                                    $addSalePayment->transaction_no = $request->transaction_no;
                                }

                                $addSalePayment->admin_id = auth()->user()->id;
                                $addSalePayment->payment_on = 2;
                                $addSalePayment->save();

                                if ($request->account_id) {
                                    // update account
                                    $account = Account::where('id', $request->account_id)->first();
                                    $account->credit += $dueAmounts;
                                    $account->balance += $dueAmounts;
                                    $account->save();

                                    // Add cash flow
                                    $addCashFlow = new CashFlow();
                                    $addCashFlow->account_id = $request->account_id;
                                    $addCashFlow->credit = $dueAmounts;
                                    $addCashFlow->balance = $account->balance;
                                    $addCashFlow->sale_payment_id = $addSalePayment->id;
                                    $addCashFlow->transaction_type = 2;
                                    $addCashFlow->cash_type = 2;
                                    $addCashFlow->date = date('d-m-Y');
                                    $addCashFlow->report_date = date('Y-m-d');
                                    $addCashFlow->month = date('F');
                                    $addCashFlow->year = date('Y');
                                    $addCashFlow->admin_id = auth()->user()->id;
                                    $addCashFlow->save();
                                }

                                if ($request->customer_id) {
                                    $addCustomerLedger = new CustomerLedger();
                                    $addCustomerLedger->customer_id = $request->customer_id;
                                    $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                    $addCustomerLedger->row_type = 2;
                                    $addCustomerLedger->save();
                                }
                                //$dueAmounts -= $dueAmounts; 
                                if ($index == 1) {
                                    break;
                                }
                            } elseif ($dueInvoice->due == $dueAmounts) {
                                $dueInvoice->paid += $dueAmounts;
                                $dueInvoice->due -= $dueAmounts;
                                $dueInvoice->save();
                                $addSalePayment = new SalePayment();
                                $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                $addSalePayment->sale_id = $dueInvoice->id;
                                $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                                $addSalePayment->account_id = $request->account_id;
                                $addSalePayment->paid_amount = $dueAmounts;
                                $addSalePayment->date = date('d-m-Y');
                                $addSalePayment->time = date('h:i:s a');
                                $addSalePayment->report_date = date('Y-m-d');
                                $addSalePayment->month = date('F');
                                $addSalePayment->year = date('Y');
                                $addSalePayment->pay_mode = $request->payment_method;
                                $addSalePayment->note = $request->payment_note;

                                if ($request->payment_method == 'Card') {
                                    $addSalePayment->card_no = $request->card_no;
                                    $addSalePayment->card_holder = $request->card_holder_name;
                                    $addSalePayment->card_transaction_no = $request->card_transaction_no;
                                    $addSalePayment->card_type = $request->card_type;
                                    $addSalePayment->card_month = $request->month;
                                    $addSalePayment->card_year = $request->year;
                                    $addSalePayment->card_secure_code = $request->secure_code;
                                } elseif ($request->payment_method == 'Cheque') {
                                    $addSalePayment->cheque_no = $request->cheque_no;
                                } elseif ($request->payment_method == 'Bank-Transfer') {
                                    $addSalePayment->account_no = $request->account_no;
                                } elseif ($request->payment_method == 'Custom') {
                                    $addSalePayment->transaction_no = $request->transaction_no;
                                }

                                $addSalePayment->admin_id = auth()->user()->id;
                                $addSalePayment->payment_on = 2;
                                $addSalePayment->save();

                                if ($request->account_id) {
                                    // update account
                                    $account = Account::where('id', $request->account_id)->first();
                                    $account->credit += $dueAmounts;
                                    $account->balance += $dueAmounts;
                                    $account->save();

                                    // Add cash flow
                                    $addCashFlow = new CashFlow();
                                    $addCashFlow->account_id = $request->account_id;
                                    $addCashFlow->credit = $dueAmounts;
                                    $addCashFlow->balance = $account->balance;
                                    $addCashFlow->sale_payment_id = $addSalePayment->id;
                                    $addCashFlow->transaction_type = 2;
                                    $addCashFlow->cash_type = 2;
                                    $addCashFlow->date = date('d-m-Y');
                                    $addCashFlow->report_date = date('Y-m-d');
                                    $addCashFlow->month = date('F');
                                    $addCashFlow->year = date('Y');
                                    $addCashFlow->admin_id = auth()->user()->id;
                                    $addCashFlow->save();
                                }

                                if ($request->customer_id) {
                                    $addCustomerLedger = new CustomerLedger();
                                    $addCustomerLedger->customer_id = $request->customer_id;
                                    $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                    $addCustomerLedger->row_type = 2;
                                    $addCustomerLedger->save();
                                }
                                //$dueAmounts -= $dueAmounts; 
                                if ($index == 1) {
                                    break;
                                }
                            } elseif ($dueInvoice->due < $dueAmounts) {
                                $addSalePayment = new SalePayment();
                                $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                                $addSalePayment->sale_id = $dueInvoice->id;
                                $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                                $addSalePayment->account_id = $request->account_id;
                                $addSalePayment->paid_amount = $dueInvoice->due;
                                $addSalePayment->date = date('d-m-Y');
                                $addSalePayment->time = date('h:i:s a');
                                $addSalePayment->report_date = date('Y-m-d');
                                $addSalePayment->month = date('F');
                                $addSalePayment->year = date('Y');
                                $addSalePayment->pay_mode = $request->payment_method;
                                $addSalePayment->note = $request->payment_note;

                                if ($request->payment_method == 'Card') {
                                    $addSalePayment->card_no = $request->card_no;
                                    $addSalePayment->card_holder = $request->card_holder_name;
                                    $addSalePayment->card_transaction_no = $request->card_transaction_no;
                                    $addSalePayment->card_type = $request->card_type;
                                    $addSalePayment->card_month = $request->month;
                                    $addSalePayment->card_year = $request->year;
                                    $addSalePayment->card_secure_code = $request->secure_code;
                                } elseif ($request->payment_method == 'Cheque') {
                                    $addSalePayment->cheque_no = $request->cheque_no;
                                } elseif ($request->payment_method == 'Bank-Transfer') {
                                    $addSalePayment->account_no = $request->account_no;
                                } elseif ($request->payment_method == 'Custom') {
                                    $addSalePayment->transaction_no = $request->transaction_no;
                                }

                                $addSalePayment->admin_id = auth()->user()->id;
                                $addSalePayment->payment_on = 2;
                                $addSalePayment->save();

                                if ($request->account_id) {
                                    // update account
                                    $account = Account::where('id', $request->account_id)->first();
                                    $account->credit += $dueInvoice->due;
                                    $account->balance += $dueInvoice->due;
                                    $account->save();

                                    // Add cash flow
                                    $addCashFlow = new CashFlow();
                                    $addCashFlow->account_id = $request->account_id;
                                    $addCashFlow->credit = $dueInvoice->due;
                                    $addCashFlow->balance = $account->balance;
                                    $addCashFlow->sale_payment_id = $addSalePayment->id;
                                    $addCashFlow->transaction_type = 2;
                                    $addCashFlow->cash_type = 2;
                                    $addCashFlow->date = date('d-m-Y');
                                    $addCashFlow->report_date = date('Y-m-d');
                                    $addCashFlow->month = date('F');
                                    $addCashFlow->year = date('Y');
                                    $addCashFlow->admin_id = auth()->user()->id;
                                    $addCashFlow->save();
                                }

                                if ($request->customer_id) {
                                    $addCustomerLedger = new CustomerLedger();
                                    $addCustomerLedger->customer_id = $request->customer_id;
                                    $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                                    $addCustomerLedger->row_type = 2;
                                    $addCustomerLedger->save();
                                }

                                $dueAmounts -= $dueInvoice->due;
                                $dueInvoice->paid += $dueInvoice->due;
                                $dueInvoice->due -= $dueInvoice->due;
                                $dueInvoice->save();
                            }
                            $index++;
                        }
                    }
                } elseif ($paidAmount < $request->total_invoice_payable) {
                    $addSalePayment = new SalePayment();
                    $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                    $addSalePayment->sale_id = $addSale->id;
                    $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                    $addSalePayment->account_id = $request->account_id;
                    $addSalePayment->paid_amount = $paidAmount;
                    $addSalePayment->date = date('d-m-Y');
                    $addSalePayment->time = date('h:i:s a');
                    $addSalePayment->report_date = date('Y-m-d');
                    $addSalePayment->month = date('F');
                    $addSalePayment->year = date('Y');
                    $addSalePayment->pay_mode = $request->payment_method;
                    $addSalePayment->note = $request->payment_note;

                    if ($request->payment_method == 'Card') {
                        $addSalePayment->card_no = $request->card_no;
                        $addSalePayment->card_holder = $request->card_holder_name;
                        $addSalePayment->card_transaction_no = $request->card_transaction_no;
                        $addSalePayment->card_type = $request->card_type;
                        $addSalePayment->card_month = $request->month;
                        $addSalePayment->card_year = $request->year;
                        $addSalePayment->card_secure_code = $request->secure_code;
                    } elseif ($request->payment_method == 'Cheque') {
                        $addSalePayment->cheque_no = $request->cheque_no;
                    } elseif ($request->payment_method == 'Bank-Transfer') {
                        $addSalePayment->account_no = $request->account_no;
                    } elseif ($request->payment_method == 'Custom') {
                        $addSalePayment->transaction_no = $request->transaction_no;
                    }

                    $addSalePayment->admin_id = auth()->user()->id;
                    $addSalePayment->save();

                    if ($request->account_id) {
                        // update account
                        $account = Account::where('id', $request->account_id)->first();
                        $account->credit += $paidAmount;
                        $account->balance += $paidAmount;
                        $account->save();

                        // Add cash flow
                        $addCashFlow = new CashFlow();
                        $addCashFlow->account_id = $request->account_id;
                        $addCashFlow->credit = $paidAmount;
                        $addCashFlow->balance = $account->balance;
                        $addCashFlow->sale_payment_id = $addSalePayment->id;
                        $addCashFlow->transaction_type = 2;
                        $addCashFlow->cash_type = 2;
                        $addCashFlow->date = date('d-m-Y');
                        $addCashFlow->report_date = date('Y-m-d');
                        $addCashFlow->month = date('F');
                        $addCashFlow->year = date('Y');
                        $addCashFlow->admin_id = auth()->user()->id;
                        $addCashFlow->save();
                    }

                    if ($request->customer_id) {
                        $addCustomerLedger = new CustomerLedger();
                        $addCustomerLedger->customer_id = $request->customer_id;
                        $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                        $addCustomerLedger->row_type = 2;
                        $addCustomerLedger->save();
                    }
                }
            } else {
                $addSalePayment = new SalePayment();
                $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
                $addSalePayment->sale_id = $addSale->id;
                $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
                $addSalePayment->account_id = $request->account_id;
                $addSalePayment->paid_amount = $request->paying_amount;
                $addSalePayment->date = date('d-m-Y');
                $addSalePayment->time = date('h:i:s a');
                $addSalePayment->report_date = date('Y-m-d');
                $addSalePayment->month = date('F');
                $addSalePayment->year = date('Y');
                $addSalePayment->pay_mode = $request->payment_method;
                $addSalePayment->note = $request->payment_note;

                if ($request->payment_method == 'Card') {
                    $addSalePayment->card_no = $request->card_no;
                    $addSalePayment->card_holder = $request->card_holder_name;
                    $addSalePayment->card_transaction_no = $request->card_transaction_no;
                    $addSalePayment->card_type = $request->card_type;
                    $addSalePayment->card_month = $request->month;
                    $addSalePayment->card_year = $request->year;
                    $addSalePayment->card_secure_code = $request->secure_code;
                } elseif ($request->payment_method == 'Cheque') {
                    $addSalePayment->cheque_no = $request->cheque_no;
                } elseif ($request->payment_method == 'Bank-Transfer') {
                    $addSalePayment->account_no = $request->account_no;
                } elseif ($request->payment_method == 'Custom') {
                    $addSalePayment->transaction_no = $request->transaction_no;
                }

                $addSalePayment->admin_id = auth()->user()->id;
                $addSalePayment->save();

                if ($request->account_id) {
                    // update account
                    $account = Account::where('id', $request->account_id)->first();
                    $account->credit += $request->paying_amount;
                    $account->balance += $request->paying_amount;
                    $account->save();

                    // Add cash flow
                    $addCashFlow = new CashFlow();
                    $addCashFlow->account_id = $request->account_id;
                    $addCashFlow->credit = $request->paying_amount;
                    $addCashFlow->balance = $account->balance;
                    $addCashFlow->sale_payment_id = $addSalePayment->id;
                    $addCashFlow->transaction_type = 2;
                    $addCashFlow->cash_type = 2;
                    $addCashFlow->date = date('d-m-Y');
                    $addSalePayment->time = date('h:i:s a');
                    $addCashFlow->report_date = date('Y-m-d');
                    $addCashFlow->month = date('F');
                    $addCashFlow->year = date('Y');
                    $addCashFlow->admin_id = auth()->user()->id;
                    $addCashFlow->save();
                }

                if ($request->customer_id) {
                    $addCustomerLedger = new CustomerLedger();
                    $addCustomerLedger->customer_id = $request->customer_id;
                    $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                    $addCustomerLedger->row_type = 2;
                    $addCustomerLedger->save();
                }
            }
        }
    }

    public function branchStock(Request $request)
    {
        $products = '';
        $warehouse = '';
        $branch = '';
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $warehouse = DB::table('warehouses')
                ->where('id', $request->warehouse_id)
                ->select('warehouse_name', 'warehouse_code')->first();
            $products = DB::table('product_warehouses')
                ->leftJoin('product_warehouse_variants', 'product_warehouses.id', 'product_warehouse_variants.product_warehouse_id')
                ->leftJoin('product_variants', 'product_warehouse_variants.product_variant_id', 'product_variants.id')
                ->leftJoin('products', 'product_warehouses.product_id', 'products.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->select(
                    'product_warehouses.product_quantity',
                    'products.name as pro_name',
                    'products.product_code as pro_code',
                    'product_variants.variant_name as var_name',
                    'product_variants.variant_code as var_code',
                    'product_warehouse_variants.variant_quantity',
                    'units.code_name as u_code',
                )
                ->where('warehouse_id', $request->warehouse_id)
                ->get();
        } else {
            $branch = DB::table('branches')
                ->where('id', auth()->user()->branch_id)
                ->select('name', 'branch_code')->first();
            $products = DB::table('product_branches')
                ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
                ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
                ->leftJoin('products', 'product_branches.product_id', 'products.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->select(
                    'product_branches.product_quantity',
                    'products.name as pro_name',
                    'products.product_code as pro_code',
                    'product_variants.variant_name as var_name',
                    'product_variants.variant_code as var_code',
                    'product_branch_variants.variant_quantity',
                    'units.code_name as u_code',
                )
                ->where('branch_id', auth()->user()->branch_id)
                ->get();
        }

        return view('sales.pos.ajax_view.stock', compact('products', 'warehouse', 'branch'));
    }

    public function searchExchangeableInv(Request $request)
    {
        $sale = Sale::with([
            'customer',
            'sale_products',
            'sale_products.product',
            'sale_products.variant',
            'sale_payments',
        ])->where('invoice_id', $request->invoice_id)->first();

        if ($sale) {
            return view('sales.pos.ajax_view.exchange_able_invoice', compact('sale'));
        } else {
            return response()->json(['errorMsg' => 'Invoice Not Fount']);
        }
    }

    public function prepareExchange(Request $request)
    {
        //return $request->all();
        
        $sale_id = $request->sale_id;
        $sale = Sale::where('id', $sale_id)->first();
        $ex_quantities = $request->ex_quantities;
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $product_row_ids = $request->product_row_ids;
        $sold_prices_inc_tax = $request->sold_prices_inc_tax;
        $sold_quantities = $request->sold_quantities;
        $sold_subtotals = $request->sold_subtotals;
        $unit_tax_amounts = $request->unit_tax_amounts;
        $unit_tax_percents = $request->unit_tax_percents;

        $index = 0;
        $exchange_item_total_price = 0;
        $sold_item_total_price = 0;
        foreach ($ex_quantities as $ex_quantity) {
            $__ex_qty = $ex_quantity ? $ex_quantity : 0;
            $soldProduct = SaleProduct::where('id', $product_row_ids[$index])->first();
            if ($__ex_qty != 0) {
                $exchange_item_total_price += $__ex_qty * $sold_prices_inc_tax[$index];
                $sold_item_total_price += $sold_quantities[$index] * $sold_prices_inc_tax[$index];
                $soldProduct->ex_quantity = $__ex_qty;
                $soldProduct->ex_status = 1;
                $soldProduct->save();
            } else {
                $soldProduct->ex_status = 0;
                $soldProduct->save();
            }
            $index++;
        }

        $ex_items = SaleProduct::with('product', 'variant')->where('sale_id', $sale->id)
            ->where('ex_status', 1)->get();

        $qty_limits = [];
        foreach ($ex_items as $sale_product) {
            if ($sale_product->sale->branch_id) {
                $productBranch = ProductBranch::where('branch_id', $sale_product->sale->branch_id)
                    ->where('product_id', $sale_product->product_id)->first();
                if ($sale_product->product->type == 2) {
                    $qty_limits[] = 500000;
                } elseif ($sale_product->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id', $sale_product->product_variant_id)
                        ->first();
                    $qty_limits[] = $productBranchVariant->variant_quantity;
                } else {
                    $qty_limits[] = $productBranch->product_quantity;
                }
            } else {
                $productWarehouse = ProductWarehouse::where('warehouse_id', $sale_product->sale->warehouse_id)
                    ->where('product_id', $sale_product->product_id)->first();
                if ($sale_product->product->type == 2) {
                    $qty_limits[] = 500000;
                } elseif ($sale_product->product_variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id', $sale_product->product_variant_id)
                        ->first();
                    $qty_limits[] = $productWarehouseVariant->variant_quantity;
                } else {
                    $qty_limits[] = $productWarehouse->product_quantity;
                }
            }
        }

        return response()->json([
            'sale' => $sale,
            'ex_items' => $ex_items,
            'qty_limits' => $qty_limits,
            'exchange_item_total_price' => $exchange_item_total_price,
            'sold_item_total_price' => $sold_item_total_price
        ]);
    }

    public function exchangeConfirm(Request $request)
    {
        //return $request->all();
        if ($request->action != 1) {
            return response()->json(['errorMsg' => 'You can not create another entry when item exchange in going on.']);
        }

        $updateSale = Sale::with('customer')->where('id', $request->ex_sale_id)->first();

        if ($request->total_due > 0 && $updateSale->customer_id == NULL) {
            return response()->json(['errorMsg' => 'Listed Customer is required when exchange is due or partial.']);
        }

        if ($updateSale->customer) {
            $updateSale->customer->total_sale_due = $updateSale->customer->total_sale_due + $request->total_due;
            $updateSale->customer->total_sale = $updateSale->customer->total_sale + $request->total_payable_amount;
            $updateSale->customer->total_paid = $updateSale->customer->total_paid + $request->paying_amount;
            $updateSale->customer->save();
        }

        $updateSale->net_total_amount = $updateSale->net_total_amount + $request->net_total_amount;
        $updateSale->total_payable_amount = $updateSale->total_payable_amount + $request->total_payable_amount;
        $updateSale->paid = $updateSale->paid + $request->paying_amount;
        $updateSale->due = $request->total_due;
        $updateSale->ex_status = 1;
        $updateSale->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $descriptions = $request->descriptions;
        $quantities = $request->quantities;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $subtotals = $request->subtotals;
        $unit_discount_types = $request->unit_discount_types;
        $unit_discounts = $request->unit_discounts;
        $unit_discount_amounts = $request->unit_discount_amounts;
        $unit_tax_percents = $request->unit_tax_percents;
        $unit_tax_amounts = $request->unit_tax_amounts;
        $unit_prices_inc_tax = $request->unit_prices_inc_tax;
        $units = $request->units;

        $index = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $saleProduct = SaleProduct::where('sale_id', $request->ex_sale_id)
                ->where('product_id', $product_id)->where('product_variant_id', $variant_id)->first();

            if ($updateSale->branch_id) {
                $productBranch = ProductBranch::where('branch_id', $saleProduct->branch_id)
                    ->where('product_id', $product_id)
                    ->first();
                $productBranch->product_quantity -= $saleProduct->quantity;
                $productBranch->save();
                if ($variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                        ->where('product_id', $product_id)
                        ->where('product_variant_id', $variant_id)
                        ->first();
                    $productBranchVariant->variant_quantity -= $quantities[$index];
                    $productBranchVariant->save();
                }
            } else {
                $productWarehouse = ProductWarehouse::where('warehouse_id', $updateSale->warehouse_id)
                    ->where('product_id', $product_id)
                    ->first();
                $productWarehouse->product_quantity -= $quantities[$index];
                $productWarehouse->save();
                if ($variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
                        ->where('product_id', $product_id)
                        ->where('product_variant_id', $variant_id)
                        ->first();
                    $productWarehouseVariant->variant_quantity -= $quantities[$index];
                    $productWarehouseVariant->save();
                }
            }
           
            if ($saleProduct) {
                if ($saleProduct->ex_status == 1) {
                    $saleProduct->quantity += $quantities[$index];
                    $saleProduct->ex_quantity = $quantities[$index];
                    $saleProduct->description = $descriptions[$index];
                    $saleProduct->subtotal += $subtotals[$index];
                    $saleProduct->ex_status = 2;
                    $saleProduct->save();
                }else {
                    $saleProduct->sale_id = $request->ex_sale_id;
                    $saleProduct->product_id = $product_ids[$index];
                    $saleProduct->product_variant_id = $variant_id;
                    $saleProduct->quantity = $quantities[$index];
                    $saleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                    $saleProduct->unit_price_inc_tax = $unit_prices_inc_tax[$index];
                    $saleProduct->unit_discount_type = $unit_discount_types[$index];
                    $saleProduct->unit_discount = $unit_discounts[$index];
                    $saleProduct->unit_discount_amount = $unit_discount_amounts[$index];
                    $saleProduct->unit_tax_percent = $unit_tax_percents[$index];
                    $saleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                    $saleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                    $saleProduct->unit = $units[$index];
                    $saleProduct->description = $descriptions[$index];
                    $saleProduct->subtotal = $subtotals[$index];
                    $saleProduct->save();
                }
            } else {
                $addSaleProduct = new SaleProduct();
                $addSaleProduct->sale_id = $request->ex_sale_id;
                $addSaleProduct->product_id = $product_ids[$index];
                $addSaleProduct->product_variant_id = $variant_id;
                $addSaleProduct->quantity = $quantities[$index];
                $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                $addSaleProduct->unit_price_inc_tax = $unit_prices_inc_tax[$index];
                $addSaleProduct->unit_discount_type = $unit_discount_types[$index];
                $addSaleProduct->unit_discount = $unit_discounts[$index];
                $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$index];
                $addSaleProduct->unit_tax_percent = $unit_tax_percents[$index];
                $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                $addSaleProduct->unit = $units[$index];
                $addSaleProduct->description = $descriptions[$index];
                $addSaleProduct->subtotal = $subtotals[$index];
                $addSaleProduct->save();
            }
            $index++;
        }

        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $i = 5;
        $a = 0;
        $invoiceId = '';
        while ($a < $i) {
            $invoiceId .= rand(1, 9);
            $a++;
        }
        // Add new payment 
        if ($request->paying_amount > 0) {
            $addSalePayment = new SalePayment();
            $addSalePayment->invoice_id = ($paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPI') . date('ymd') . $invoiceId;
            $addSalePayment->sale_id = $request->ex_sale_id;
            $addSalePayment->customer_id = $request->customer_id ? $request->customer_id : NULL;
            $addSalePayment->account_id = $request->account_id;
            $addSalePayment->paid_amount = $request->paying_amount;
            $addSalePayment->date = date('d-m-Y');
            $addSalePayment->time = date('h:i:s');
            $addSalePayment->report_date = date('Y-m-d');
            $addSalePayment->month = date('F');
            $addSalePayment->year = date('Y');
            $addSalePayment->pay_mode = $request->payment_method;
            $addSalePayment->note = $request->payment_note;

            if ($request->payment_method == 'Card') {
                $addSalePayment->card_no = $request->card_no;
                $addSalePayment->card_holder = $request->card_holder_name;
                $addSalePayment->card_transaction_no = $request->card_transaction_no;
                $addSalePayment->card_type = $request->card_type;
                $addSalePayment->card_month = $request->month;
                $addSalePayment->card_year = $request->year;
                $addSalePayment->card_secure_code = $request->secure_code;
            } elseif ($request->payment_method == 'Cheque') {
                $addSalePayment->cheque_no = $request->cheque_no;
            } elseif ($request->payment_method == 'Bank-Transfer') {
                $addSalePayment->account_no = $request->account_no;
            } elseif ($request->payment_method == 'Custom') {
                $addSalePayment->transaction_no = $request->transaction_no;
            }

            $addSalePayment->admin_id = auth()->user()->id;
            $addSalePayment->save();

            if ($request->account_id) {
                // update account
                $account = Account::where('id', $request->account_id)->first();
                $account->credit += $request->paying_amount;
                $account->balance += $request->paying_amount;
                $account->save();

                // Add cash flow
                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->credit = $request->paying_amount;
                $addCashFlow->balance = $account->balance;
                $addCashFlow->sale_payment_id = $addSalePayment->id;
                $addCashFlow->transaction_type = 2;
                $addCashFlow->cash_type = 2;
                $addCashFlow->date = date('d-m-Y');
                $addCashFlow->report_date = date('Y-m-d');
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
            }

            if ($request->customer_id) {
                $addCustomerLedger = new CustomerLedger();
                $addCustomerLedger->customer_id = $request->customer_id;
                $addCustomerLedger->sale_payment_id = $addSalePayment->id;
                $addCustomerLedger->row_type = 2;
                $addCustomerLedger->save();
            }
        }

        $previous_due = 0;
        $total_payable_amount = $request->total_payable_amount;
        $paying_amount = $request->paying_amount;
        $total_due = $request->total_due;
        $change_amount = $request->change_amount;

        $sale = Sale::with(['customer', 'branch', 'sale_products', 'sale_products.product', 'sale_products.variant'])->where('id', $request->ex_sale_id)->first();
        return view('sales.save_and_print_template.pos_sale_print', compact(
            'sale',
            'previous_due',
            'total_payable_amount',
            'paying_amount',
            'total_due',
            'change_amount'
        ));
    }
}
