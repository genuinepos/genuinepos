<?php

namespace App\Http\Controllers;

use App\Utils\Util;
use App\Models\Sale;
use App\Utils\SmsUtil;
use App\Models\Account;
use App\Models\Product;
use App\Utils\SaleUtil;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Jobs\SaleMailJob;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\AdminAndUser;
use Illuminate\Http\Request;
use App\Models\ProductBranch;
use App\Utils\NameSearchUtil;
use App\Models\CustomerLedger;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Utils\AccountUtil;
use App\Utils\CustomerUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\ProductStockUtil;

class SaleController extends Controller
{
    protected $nameSearchUtil;
    protected $saleUtil;
    protected $smsUtil;
    protected $util;
    protected $customerUtil;
    protected $productStockUtil;
    protected $accountUtil;
    protected $invoiceVoucherRefIdUtil;
    public function __construct(
        NameSearchUtil $nameSearchUtil,
        SaleUtil $saleUtil,
        SmsUtil $smsUtil,
        Util $util,
        CustomerUtil $customerUtil,
        ProductStockUtil $productStockUtil,
        AccountUtil $accountUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {
        $this->nameSearchUtil = $nameSearchUtil;
        $this->saleUtil = $saleUtil;
        $this->smsUtil = $smsUtil;
        $this->util = $util;
        $this->customerUtil = $customerUtil;
        $this->productStockUtil = $productStockUtil;
        $this->accountUtil = $accountUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->middleware('auth:admin_and_user');
    }

    public function index2(Request $request)
    {
        if (auth()->user()->permission->sale['sale_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->saleUtil->addSaleTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);
        return view('sales.index2', compact('branches', 'customers'));
    }

    public function posList(Request $request)
    {
        if (auth()->user()->permission->sale['sale_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            return $this->saleUtil->posSaleTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);
        return view('sales.pos.index', compact('branches', 'customers'));
    }

    public function soldProductList(Request $request)
    {
        if ($request->ajax()) {
            return $this->saleUtil->soldProductListTable($request);
        }

        $categories = DB::table('categories')->where('parent_category_id', NULL)->get(['id', 'name']);
        $branches = DB::table('branches')->get(['id', 'name', 'branch_code']);
        $customers = DB::table('customers')->get(['id', 'name', 'phone']);
        return view('sales.sold_product_list', compact('branches', 'categories', 'customers'));
    }

    public function show($saleId)
    {
        $sale = Sale::with([
            'branch',
            'branch.add_sale_invoice_layout',
            'customer',
            'admin',
            'admin.role',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'sale_payments',
        ])->where('id', $saleId)->first();
        return view('sales.ajax_view.product_details_modal', compact('sale'));
    }

    public function posShow($saleId)
    {
        $sale = Sale::with([
            'branch',
            'branch.pos_sale_invoice_layout',
            'customer',
            'admin',
            'admin.role',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'sale_payments',
        ])->where('id', $saleId)->first();
        return view('sales.pos.ajax_view.show', compact('sale'));
    }

    // Draft list view 
    public function drafts(Request $request)
    {
        if ($request->ajax()) {
            return $this->saleUtil->saleDraftTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.drafts', compact('branches'));
    }

    // Quotations list view 
    public function quotations(Request $request)
    {
        if ($request->ajax()) {
            return $this->saleUtil->saleQuotationTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.quotations', compact('branches'));
    }

    // Quotation Details
    public function quotationDetails($quotationId)
    {
        $quotation = Sale::with([
            'branch', 'branch.add_sale_invoice_layout', 'customer', 'admin', 'admin.role', 'sale_products', 'sale_products.product', 'sale_products.variant', 'sale_payments',
        ])->where('id', $quotationId)->first();
        return view('sales.ajax_view.quotation_details', compact('quotation'));
    }

    // Create sale view
    public function create()
    {
        if (auth()->user()->permission->sale['sale_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $customers = DB::table('customers')
            ->where('status', 1)->select('id', 'name', 'phone')
            ->orderBy('id', 'desc')->get();
        $invoice_schemas = DB::table('invoice_schemas')->get(['format', 'prefix', 'start_from']);
        $accounts = DB::table('accounts')->get(['id', 'name', 'account_number']);
        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);
        return view('sales.create', compact('customers', 'accounts', 'price_groups', 'invoice_schemas'));
    }

    // Add Sale method
    public function store(Request $request)
    {
        $prefixSettings = DB::table('general_settings')
            ->select(['id', 'prefix', 'send_es_settings'])
            ->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $branchInvoiceSchema = DB::table('branches')
            ->leftJoin('invoice_schemas', 'branches.invoice_schema_id', 'invoice_schemas.id')
            ->where('branches.id', auth()->user()->branch_id)
            ->select(
                'branches.*',
                'invoice_schemas.id as schema_id',
                'invoice_schemas.prefix',
                'invoice_schemas.format',
                'invoice_schemas.start_from',
            )->first();

        $invoicePrefix = '';
        if ($request->invoice_schema) {
            $invoicePrefix = $request->invoice_schema;
        } else {
            if ($branchInvoiceSchema && $branchInvoiceSchema->prefix !== null) {
                $invoicePrefix = $branchInvoiceSchema->format == 2 ? date('Y') . $branchInvoiceSchema->start_from : $branchInvoiceSchema->prefix . $branchInvoiceSchema->start_from . date('ymd');
            } else {
                $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();
                $invoicePrefix = $defaultSchemas->format == 2 ? date('Y') . $defaultSchemas->start_from : $defaultSchemas->prefix . $defaultSchemas->start_from . date('ymd');
            }
        }

        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty']);
        }

        if ($request->paying_amount < $request->total_payable_amount && !$request->customer_id) {
            return response()->json(['errorMsg' => 'Listed customer is required when sale is due or partial.']);
        }

        $this->validate($request, [
            'status' => 'required',
        ]);

        $addSale = new Sale();
        $addSale->invoice_id = $request->invoice_id ? $request->invoice_id : $invoicePrefix . $this->invoiceVoucherRefIdUtil->getLastId('sales');
        $addSale->admin_id = auth()->user()->id;
        $addSale->branch_id = auth()->user()->branch_id;

        $customer_id = $request->customer_id != 0 ? $request->customer_id : NULL;
        $addSale->customer_id = $customer_id;
        $addSale->status = $request->status;

        if ($request->status == 1) {
            $addSale->is_fixed_challen = 1;
        }

        $addSale->pay_term = $request->pay_term;
        $addSale->date = $request->date;
        $addSale->time = date('h:i:s a');
        $addSale->report_date = date('Y-m-d', strtotime($request->date));
        $addSale->pay_term_number = $request->pay_term_number;
        $addSale->total_item = $request->total_item;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount;
        $addSale->order_discount_amount = $request->order_discount_amount;
        $addSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addSale->shipment_details = $request->shipment_details;
        $addSale->shipment_address = $request->shipment_address;
        $addSale->shipment_status = $request->shipment_status;
        $addSale->delivered_to = $request->delivered_to;
        $addSale->sale_note = $request->sale_note;
        $addSale->payment_note = $request->payment_note;
        $addSale->month = date('F');
        $addSale->year = date('Y');

        // Update customer due
        if ($request->status == 1) {
            $changedAmount = $request->change_amount > 0 ? $request->change_amount : 0;
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
                $addSale->paid = $request->change_amount > 0 ? $request->total_invoice_payable : $request->paying_amount;
                $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;
                $addSale->due = $request->total_due > 0 ? $request->total_due : 0.00;
            }
            $addSale->save();

            if ($customer_id) {
                $addCustomerLedger = new CustomerLedger();
                $addCustomerLedger->customer_id = $customer_id;
                $addCustomerLedger->sale_id = $addSale->id;
                $addCustomerLedger->row_type = 1;
                $addCustomerLedger->report_date = date('Y-m-d', strtotime($request->date));
                $addCustomerLedger->save();
            }
        } else {
            $addSale->total_payable_amount = $request->total_invoice_payable;
            $addSale->save();
        }

        // update product quantity
        $quantities = $request->quantities;
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
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $descriptions = $request->descriptions;
        // update product quantity and add sale product
        $branch_id = auth()->user()->branch_id;

        $__index = 0;
        foreach ($product_ids as $product_id) {
            $addSaleProduct = new SaleProduct();
            $addSaleProduct->sale_id = $addSale->id;
            $addSaleProduct->product_id = $product_id;
            $addSaleProduct->product_variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
            $addSaleProduct->quantity = $quantities[$__index];
            $addSaleProduct->unit_discount_type = $unit_discount_types[$__index];
            $addSaleProduct->unit_discount = $unit_discounts[$__index];
            $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$__index];
            $addSaleProduct->unit_tax_percent = $unit_tax_percents[$__index];
            $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$__index];
            $addSaleProduct->unit = $units[$__index];
            $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$__index];
            $addSaleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$__index];
            $addSaleProduct->unit_price_inc_tax = $unit_prices[$__index];
            $addSaleProduct->subtotal = $subtotals[$__index];
            $addSaleProduct->description = $descriptions[$__index] ? $descriptions[$__index] : NULL;
            $addSaleProduct->save();
            $__index++;
        }

        // Add sale payment
        $sale = Sale::with([
            'customer',
            'branch',
            'branch.add_sale_invoice_layout',
            'sale_products',
            'sale_products.product',
            'sale_products.product.warranty',
            'sale_products.variant',
            'admin'
        ])->where('id', $addSale->id)->first();

        if ($request->status == 1) {
            $this->saleUtil->__getSalePaymentForAddSaleStore($request, $sale, $paymentInvoicePrefix, $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'));
            if ($sale->customer_id) {
                $this->customerUtil->adjustCustomerAmountForSalePaymentDue($sale->customer_id);
            }

            $__index = 0;
            foreach ($product_ids as $product_id) {
                $variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
                $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);
                $this->productStockUtil->adjustBranchStock($product_id, $variant_id, $branch_id);
                $__index++;
            }
        }

        $previous_due = $request->previous_due;
        $total_payable_amount = $request->total_payable_amount;
        $paying_amount = $request->paying_amount;
        $total_due = $request->total_due;
        $change_amount = $request->change_amount;

        if (
            env('MAIL_ACTIVE') == 'true' &&
            json_decode($prefixSettings->send_es_settings, true)['send_inv_via_email'] == '1'
        ) {
            if ($sale->customer && $sale->customer->email) {
                SaleMailJob::dispatch($sale->customer->email, $sale)
                    ->delay(now()->addSeconds(5));
            }
        }

        if (
            env('SMS_ACTIVE') == 'true' &&
            json_decode($prefixSettings->send_es_settings, true)['send_notice_via_sms'] == '1'
        ) {
            if ($sale->customer && $sale->customer->phone) {
                $this->smsUtil->singleSms($sale);
            }
        }

        if ($request->action == 'save_and_print') {
            if ($request->status == 1) {
                return view('sales.save_and_print_template.sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
            } elseif ($request->status == 2) {
                return view('sales.save_and_print_template.draft_print', compact('sale'));
            } elseif ($request->status == 4) {
                return view('sales.save_and_print_template.quotation_print', compact('sale'));
            }
        } else {
            if ($request->status == 1) {
                session()->flash('successMsg', 'Sale created successfully');
                return response()->json(['finalMsg' => 'Sale created successfully']);
            } elseif ($request->status == 2) {
                session()->flash('successMsg', 'Sale draft created successfully');
                return response()->json(['draftMsg' => 'Sale draft created successfully']);
            } elseif ($request->status == 4) {
                session()->flash('successMsg', 'Sale quotation created successfully');
                return response()->json(['quotationMsg' => 'Sale quotation created successfully']);
            }
        }
    }

    // Sale edit view
    public function edit($saleId)
    {
        if (auth()->user()->permission->sale['sale_access'] == '0') {
            abort(403, 'Access Forbidden.');
        }

        $saleId = $saleId;
        $sale = Sale::where('id', $saleId)->select(['id', 'date', 'branch_id'])->first();
        $price_groups = DB::table('price_groups')->where('status', 'Active')->get();
        return view('sales.edit', compact('saleId', 'sale', 'price_groups'));
    }

    // Get editable sale
    public function editableSale($saleId)
    {
        $sale = Sale::with([
            'sale_products',
            'customer',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts',
            'sale_products.product.comboProducts.parentProduct',
            'sale_products.product.comboProducts.product_variant',
        ])->where('id', $saleId)->first();

        $qty_limits = [];
        foreach ($sale->sale_products as $sale_product) {
            if ($sale_product->product->is_manage_stock == 0) {
                $qty_limits[] = PHP_INT_MAX;
            }else {
                $productBranch = ProductBranch::where('branch_id', $sale->branch_id)
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
            } 
        }

        return response()->json(['sale' => $sale, 'qty_limits' => $qty_limits]);
    }

    // Update Sale 
    public function update(Request $request, $saleId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $invoicePrefix = json_decode($prefixSettings->prefix, true)['sale_invoice'];
        if ($request->product_ids == null) {
            return response()->json(['errorMsg' => 'product table is empty']);
        }

        $this->validate($request, [
            'status' => 'required',
            'date' => 'required',
        ]);

        $updateSale = Sale::with([
            'sale_products',
            'sale_products.product',
            'sale_products.variant',
            'sale_products.product.comboProducts'
        ])->where('id', $saleId)->first();

        foreach ($updateSale->sale_products as $sale_product) {
            $sale_product->delete_in_update = 1;
            $sale_product->save();
        }

        $updateSale->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : '') . date('my') . $this->invoiceVoucherRefIdUtil->getLastId('sales');
        $updateSale->status = $request->status;
        $updateSale->pay_term = $request->pay_term;
        $updateSale->date = $request->date;
        $updateSale->pay_term_number = $request->pay_term_number;
        $updateSale->total_item = $request->total_item;
        $updateSale->net_total_amount = $request->net_total_amount;
        $updateSale->order_discount_type = $request->order_discount_type;
        $updateSale->order_discount = $request->order_discount;
        $updateSale->order_discount_amount = $request->order_discount_amount;
        $updateSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $updateSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updateSale->total_payable_amount = $request->total_payable_amount;
        $updateSale->shipment_details = $request->shipment_details;
        $updateSale->shipment_address = $request->shipment_address;
        $updateSale->shipment_status = $request->shipment_status;
        $updateSale->delivered_to = $request->delivered_to;
        $updateSale->sale_note = $request->sale_note;
        $updateSale->report_date = date('Y-m-d', strtotime($request->date));
        $updateSale->save();
        
        if ($updateSale->ledger) {
            $updateSale->ledger->report_date = $updateSale->report_date;
            $updateSale->ledger->save();
        }else {
            if ($updateSale->status == 1 && $updateSale->customer_id) {
                $addCustomerLedger = new CustomerLedger();
                $addCustomerLedger->customer_id = $updateSale->customer_id;
                $addCustomerLedger->sale_id = $updateSale->id;
                $addCustomerLedger->row_type = 1;
                $addCustomerLedger->report_date = date('Y-m-d', strtotime($updateSale->date));
                $addCustomerLedger->save();
            } 
        }

        // update product quantity
        $quantities = $request->quantities;
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
        $unit_prices = $request->unit_prices;
        $subtotals = $request->subtotals;
        $descriptions = $request->descriptions;
        $index = 0;

        // Update sale product rows
        $__index = 0;
        foreach ($product_ids as $product_id) {
            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $saleProduct = SaleProduct::where('sale_id', $updateSale->id)->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)->first();

            if ($saleProduct) {
                $saleProduct->quantity = $quantities[$__index];
                $saleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$__index];
                $saleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$__index];
                $saleProduct->unit_price_inc_tax = $unit_prices[$__index];
                $saleProduct->unit_discount_type = $unit_discount_types[$__index];
                $saleProduct->unit_discount = $unit_discounts[$__index];
                $saleProduct->unit_discount_amount = $unit_discount_amounts[$__index];
                $saleProduct->unit_tax_percent = $unit_tax_percents[$__index];
                $saleProduct->unit_tax_amount = $unit_tax_amounts[$__index];
                $saleProduct->unit = $units[$__index];
                $saleProduct->subtotal = $subtotals[$__index];
                $saleProduct->description = $descriptions[$__index] ? $descriptions[$__index] : NULL;
                $saleProduct->delete_in_update = 0;
                $saleProduct->save();
            } else {
                $addSaleProduct = new SaleProduct();
                $addSaleProduct->sale_id = $updateSale->id;
                $addSaleProduct->product_id = $product_id;
                $addSaleProduct->product_variant_id = $variant_ids[$__index] != 'noid' ? $variant_ids[$__index] : NULL;
                $addSaleProduct->quantity = $quantities[$__index];
                $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$__index];
                $addSaleProduct->unit_price_exc_tax = $unit_prices_exc_tax[$__index];
                $addSaleProduct->unit_price_inc_tax = $unit_prices[$__index];
                $addSaleProduct->unit_discount_type = $unit_discount_types[$__index];
                $addSaleProduct->unit_discount = $unit_discounts[$__index];
                $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$__index];
                $addSaleProduct->unit_tax_percent = $unit_tax_percents[$__index];
                $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$__index];
                $addSaleProduct->unit = $units[$__index];
                $addSaleProduct->subtotal = $subtotals[$__index];
                $addSaleProduct->description = $descriptions[$__index] ? $descriptions[$__index] : NULL;
                $addSaleProduct->save();
            }
            $__index++;
        }

        $deleteNotFoundSaleProducts = SaleProduct::where('sale_id', $updateSale->id)
            ->where('delete_in_update', 1)->get();
        foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {
            $storedProductId = $deleteNotFoundSaleProduct->product_id;
            $storedVariantId = $deleteNotFoundSaleProduct->product_variant_id ? $deleteNotFoundSaleProduct->product_variant_id : NULL;
            $deleteNotFoundSaleProduct->delete();
            $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);
            $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId, auth()->user()->branch_id);
        }

        if ($request->status == 1) {
            $this->saleUtil->adjustSaleInvoiceAmounts($updateSale);
            $customer = Customer::where('id', $updateSale->customer_id)->first();
            if ($customer) {
                $this->customerUtil->adjustCustomerAmountForSalePaymentDue($customer->id);
            }

            $sale_products = DB::table('sale_products')->where('sale_id', $updateSale->id)->get();
            foreach ($sale_products as $saleProduct) {
                $variant_id = $saleProduct->product_variant_id ? $saleProduct->product_variant_id : NULL;
                $this->productStockUtil->adjustMainProductAndVariantStock($saleProduct->product_id, $variant_id);
                $this->productStockUtil->adjustBranchStock($saleProduct->product_id, $variant_id, auth()->user()->branch_id);
            }
        }

        if ($request->status == 1) {
            session()->flash('successMsg', 'Sale updated successfully');
            return response()->json(['successMsg' => 'Sale updated successfully']);
        } elseif ($request->status == 2) {
            session()->flash('successMsg', 'Sale draft updated successfully');
            return response()->json(['successMsg' => 'Sale draft updated successfully']);
        } elseif ($request->status == 4) {
            session()->flash('successMsg', 'Sale quotation updated successfully');
            return response()->json(['successMsg' => 'Sale quotation updated successfully']);
        }
    }

    // Delete Sale
    public function delete(Request $request, $saleId)
    {
        $this->saleUtil->deleteSale($request, $saleId);
        return response()->json('Sale deleted successfully');
    }

    // Sale Packing Slip
    public function packingSlip($saleId)
    {
        $sale = Sale::with(['branch', 'customer'])->where('id', $saleId)->first();
        return view('sales.ajax_view.print_packing_slip', compact('sale'));
    }

    // Shipments View
    public function shipments(Request $request)
    {
        if ($request->ajax()) {
            return $this->saleUtil->saleShipmentListTable($request);
        }

        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('sales.shipments', compact('branches'));
    }

    // Update shipment
    public function updateShipment(Request $request, $saleId)
    {
        $sale = Sale::where('id', $saleId)->first();
        $sale->shipment_details = $request->shipment_details;
        $sale->shipment_address = $request->shipment_address;
        $sale->shipment_status = $request->shipment_status;
        $sale->delivered_to = $request->delivered_to;
        $sale->save();
        return response()->json('Successfully shipment is updated.');
    }

    // Get all customers requested by ajax
    public function getAllCustomer()
    {
        $customers = Customer::select('id', 'name',  'pay_term', 'pay_term_number', 'phone', 'total_sale_due')
            ->where('is_walk_in_customer', 0)
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($customers);
    }

    // Get customer info
    public function customerInfo($customerId)
    {
        $customer = DB::table('customers')->where('id', $customerId)
            ->select('pay_term', 'pay_term_number', 'total_sale_due', 'point')->first();
        return response()->json($customer);
    }

    // Get all user requested by ajax
    public function getAllUser()
    {
        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $users = AdminAndUser::with(['role'])
                ->select(['id', 'prefix',  'name', 'last_name', 'role_type', 'role_id', 'email'])->where('allow_login', 1)->get();
            return response()->json($users);
        } else {
            $users = AdminAndUser::with(['role'])->where('branch_id', auth()->user()->branch_id)
                ->select(['id', 'prefix',  'name', 'last_name', 'role_type', 'role_id', 'email'])
                ->where('allow_login', 1)
                ->get();
            return response()->json($users);
        }
    }

    // Search product by code
    public function searchProduct($product_code)
    {
        $product_code = (string)$product_code;
        $branch_id = auth()->user()->branch_id;
        $product = Product::with(['product_variants', 'tax', 'unit'])
            ->where('product_code', $product_code)
            ->select([
                'id',
                'name',
                'type',
                'product_code',
                'product_price',
                'profit',
                'product_cost_with_tax',
                'thumbnail_photo',
                'unit_id',
                'tax_id',
                'tax_type',
                'is_show_emi_on_pos',
                'is_manage_stock',
            ])->first();

        if ($product) {
            if ($product->is_manage_stock == 0) {
                return response()->json(
                    [
                        'product' => $product,
                        'qty_limit' => PHP_INT_MAX
                    ]
                );
            }
         
            $productBranch = DB::table('product_branches')
                ->where('branch_id', $branch_id)
                ->where('product_id', $product->id)
                ->select('product_quantity')
                ->first();

            if ($productBranch) {
                if ($product->type == 2) {
                    return response()->json(['errorMsg' => 'Combo product is not sellable in this demo']);
                } else {
                    if ($productBranch->product_quantity > 0) {
                        return response()->json(
                            [
                                'product' => $product,
                                'qty_limit' => $productBranch->product_quantity
                            ]
                        );
                    } else {
                        return response()->json(['errorMsg' => 'Stock is out of this product of this branch/shop']);
                    }
                }
            } else {
                return response()->json(['errorMsg' => 'This product is not available in this branch/shop. ']);
            }
        } else {
            $variant_product = ProductVariant::with('product', 'product.tax', 'product.unit')
                ->where('variant_code', $product_code)
                ->select([
                    'id', 'product_id', 'variant_name', 'variant_code', 'variant_quantity', 'variant_cost', 'variant_cost_with_tax', 'variant_profit', 'variant_price'
                ])->first();
            if ($variant_product) {
                if ($variant_product->product->is_manage_stock == 0) {
                    return response()->json([
                        'variant_product' => $variant_product,
                        'qty_limit' => PHP_INT_MAX
                    ]);
                }

                if ($variant_product) {
                    $productBranch = DB::table('product_branches')
                        ->where('branch_id', $branch_id)
                        ->where('product_id', $variant_product->product_id)
                        ->first();

                    if (is_null($productBranch)) {
                        return response()->json(['errorMsg' => 'This product is not available in this shop']);
                    }

                    $productBranchVariant = DB::table('product_branch_variants')
                        ->where('product_branch_id', $productBranch->id)
                        ->where('product_id', $variant_product->product_id)
                        ->where('product_variant_id', $variant_product->id)
                        ->select('variant_quantity')
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

        return $this->nameSearchUtil->nameSearching($product_code);
    }

    // Check Branch Single product Stock
    public function checkBranchSingleProductStock($product_id)
    {
        return $this->nameSearchUtil->checkBranchSingleProductStock($product_id, auth()->user()->branch_id);
    }

    // Check Branch variant product Stock 
    public function checkBranchProductVariant($product_id, $variant_id)
    {
        return $this->nameSearchUtil->checkBranchVariantProductStock($product_id, $variant_id, auth()->user()->branch_id);
    }

    public function editShipment($saleId)
    {
        $sale = Sale::where('id', $saleId)->first();
        return view('sales.ajax_view.edit_shipment', compact('sale'));
    }

    public function viewPayment($saleId)
    {
        $sale = Sale::with(['customer', 'branch', 'sale_payments'])->where('id', $saleId)->first();
        return view('sales.ajax_view.payment_view', compact('sale'));
    }

    // Show payment modal
    public function paymentModal($saleId)
    {
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        $sale = Sale::with('branch', 'customer')->where('id', $saleId)->first();
        return view('sales.ajax_view.add_payment', compact('sale', 'accounts'));
    }

    public function paymentAdd(Request $request, $saleId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];
        $sale = Sale::where('id', $saleId)->first();

        // Add sale payment
        $this->saleUtil->addPayment($paymentInvoicePrefix, $request, $request->amount, $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'), $saleId);
        if ($sale->customer_id) {
            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($sale->customer_id);
        }

        $this->saleUtil->adjustSaleInvoiceAmounts($sale);
        return response()->json('Payment added successfully.');
    }

    // Show payment modal
    public function paymentEdit($paymentId)
    {
        $accounts =  Account::orderBy('id', 'DESC')->where('status', 1)->get();
        $payment = SalePayment::with('sale', 'sale.customer', 'sale.branch')->where('id', $paymentId)->first();
        return view('sales.ajax_view.edit_payment', compact('payment', 'accounts'));
    }

    // Payment update
    public function paymentUpdate(Request $request, $paymentId)
    {
        $updateSalePayment = SalePayment::with(
            'account',
            'customer',
            'sale',
            'cashFlow'
        )->where('id', $paymentId)->first();

        // update sale payment
        $updateSalePayment->account_id = $request->account_id;
        $updateSalePayment->pay_mode = $request->payment_method;
        $updateSalePayment->paid_amount = $request->amount;
        $updateSalePayment->date = $request->date;
        $updateSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updateSalePayment->month = date('F');
        $updateSalePayment->year = date('Y');
        $updateSalePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updateSalePayment->card_no = $request->card_no;
            $updateSalePayment->card_holder = $request->card_holder_name;
            $updateSalePayment->card_transaction_no = $request->card_transaction_no;
            $updateSalePayment->card_type = $request->card_type;
            $updateSalePayment->card_month = $request->month;
            $updateSalePayment->card_year = $request->year;
            $updateSalePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updateSalePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updateSalePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updateSalePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updateSalePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updateSalePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updateSalePayment->attachment));
                }
            }
            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $updateSalePayment->attachment = $salePaymentAttachmentName;
        }
        $updateSalePayment->save();

        if ($request->account_id) {
            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)->where('sale_payment_id', $updateSalePayment->id)->first();
            if ($cashFlow) {
                $cashFlow->credit = $request->amount;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->save();
                $cashFlow->balance = $this->accountUtil->adjustAccountBalance($updateSalePayment->account_id);
                $cashFlow->save();
            } else {
                if ($updateSalePayment->cashFlow) {
                    $storedAccountId = $updateSalePayment->cashFlow->account_id;
                    $updateSalePayment->cashFlow->delete();
                    $this->accountUtil->adjustAccountBalance($storedAccountId);
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->credit = $request->amount;
                $addCashFlow->sale_payment_id = $updateSalePayment->id;
                $addCashFlow->transaction_type = 2;
                $addCashFlow->cash_type = 2;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
                $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                $addCashFlow->save();
            }
        } else {
            if ($updateSalePayment->cashFlow) {
                $storedAccountId = $updateSalePayment->cashFlow->account_id;
                $updateSalePayment->cashFlow->delete();
                $this->accountUtil->adjustAccountBalance($storedAccountId);
            }
        }

        $this->saleUtil->adjustSaleInvoiceAmounts($updateSalePayment->sale);
        if ($updateSalePayment->sale->customer_id) {
            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($updateSalePayment->sale->customer_id);
        }

        return response()->json('Payment updated successfully.');
    }

    // Show payment modal
    public function returnPaymentModal($saleId)
    {
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        $sale = Sale::with('branch', 'customer')->where('id', $saleId)->first();
        return view('sales.ajax_view.add_return_payment', compact('sale', 'accounts'));
    }

    public function returnPaymentAdd(Request $request, $saleId)
    {
        $sale = Sale::with(['sale_return'])->where('id', $saleId)->first();
        if ($sale->sale_return) {
            $sale->sale_return->total_return_due -= $request->amount;
            $sale->sale_return->total_return_due_pay += $request->amount;
            $sale->sale_return->save();
        }

        // Add sale payment
        $addSalePayment = new SalePayment();
        $addSalePayment->invoice_id = 'SRPV' . date('my') . $this->invoiceVoucherRefIdUtil->getLastId('sale_payments');
        $addSalePayment->sale_id = $sale->id;
        $addSalePayment->customer_id = $sale->customer_id ? $sale->customer_id : NULL;
        $addSalePayment->account_id = $request->account_id;
        $addSalePayment->pay_mode = $request->payment_method;
        $addSalePayment->payment_type = 2;
        $addSalePayment->paid_amount = $request->amount;
        $addSalePayment->date = $request->date;
        $addSalePayment->time = date('h:i:s a');
        $addSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $addSalePayment->month = date('F');
        $addSalePayment->year = date('Y');
        $addSalePayment->note = $request->note;

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

        if ($request->hasFile('attachment')) {
            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $addSalePayment->attachment = $salePaymentAttachmentName;
        }
        $addSalePayment->save();

        if ($request->account_id) {
            // Add cash flow
            $addCashFlow = new CashFlow();
            $addCashFlow->account_id = $request->account_id;
            $addCashFlow->debit = $request->amount;
            $addCashFlow->sale_payment_id = $addSalePayment->id;
            $addCashFlow->transaction_type = 2;
            $addCashFlow->cash_type = 1;
            $addCashFlow->date = $request->date;
            $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
            $addCashFlow->month = date('F');
            $addCashFlow->year = date('Y');
            $addCashFlow->admin_id = auth()->user()->id;
            $addCashFlow->save();
            $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
            $addCashFlow->save();
        }

        $this->saleUtil->adjustSaleInvoiceAmounts($sale);
        if ($sale->customer_id) {
            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($sale->customer_id);
        }

        if ($sale->customer_id) {
            $addCustomerLedger = new CustomerLedger();
            $addCustomerLedger->customer_id = $sale->customer_id;
            $addCustomerLedger->sale_payment_id = $addSalePayment->id;
            $addCustomerLedger->row_type = 2;
            $addCustomerLedger->report_date = date('Y-m-d', strtotime($request->date));
            $addCustomerLedger->save();
        }

        return response()->json('Return amount paid successfully.');
    }

    public function returnPaymentEdit($paymentId)
    {
        $accounts = Account::orderBy('id', 'DESC')->where('status', 1)->get();
        $payment = SalePayment::with('sale', 'sale.customer', 'sale.branch')->where('id', $paymentId)->first();
        return view('sales.ajax_view.edit_return_payment', compact('payment', 'accounts'));
    }

    public function returnPaymentUpdate(Request $request, $paymentId)
    {
        $updateSalePayment = SalePayment::with(
            'account',
            'customer',
            'sale',
            'sale.sale_return',
            'cashFlow'
        )->where('id', $paymentId)->first();

        // Update sale return
        if ($updateSalePayment->sale->sale_return) {
            $updateSalePayment->sale->sale_return->total_return_due += $updateSalePayment->paid_amount;
            $updateSalePayment->sale->sale_return->total_return_due -= $request->amount;
            $updateSalePayment->sale->sale_return->total_return_due_pay += $updateSalePayment->paid_amount;
            $updateSalePayment->sale->sale_return->total_return_due_pay -= $request->amount;
            $updateSalePayment->sale->sale_return->save();
        }

        // update sale payment
        $updateSalePayment->account_id = $request->account_id;
        $updateSalePayment->pay_mode = $request->payment_method;
        $updateSalePayment->paid_amount = $request->amount;
        $updateSalePayment->date = $request->date;
        $updateSalePayment->report_date = date('Y-m-d', strtotime($request->date));
        $updateSalePayment->month = date('F');
        $updateSalePayment->year = date('Y');
        $updateSalePayment->note = $request->note;

        if ($request->payment_method == 'Card') {
            $updateSalePayment->card_no = $request->card_no;
            $updateSalePayment->card_holder = $request->card_holder_name;
            $updateSalePayment->card_transaction_no = $request->card_transaction_no;
            $updateSalePayment->card_type = $request->card_type;
            $updateSalePayment->card_month = $request->month;
            $updateSalePayment->card_year = $request->year;
            $updateSalePayment->card_secure_code = $request->secure_code;
        } elseif ($request->payment_method == 'Cheque') {
            $updateSalePayment->cheque_no = $request->cheque_no;
        } elseif ($request->payment_method == 'Bank-Transfer') {
            $updateSalePayment->account_no = $request->account_no;
        } elseif ($request->payment_method == 'Custom') {
            $updateSalePayment->transaction_no = $request->transaction_no;
        }

        if ($request->hasFile('attachment')) {
            if ($updateSalePayment->attachment != null) {
                if (file_exists(public_path('uploads/payment_attachment/' . $updateSalePayment->attachment))) {
                    unlink(public_path('uploads/payment_attachment/' . $updateSalePayment->attachment));
                }
            }
            $salePaymentAttachment = $request->file('attachment');
            $salePaymentAttachmentName = uniqid() . '-' . '.' . $salePaymentAttachment->getClientOriginalExtension();
            $salePaymentAttachment->move(public_path('uploads/payment_attachment/'), $salePaymentAttachmentName);
            $updateSalePayment->attachment = $salePaymentAttachmentName;
        }
        $updateSalePayment->save();
        $updateSalePayment->ledger->report_date = $updateSalePayment->report_date;
        $updateSalePayment->ledger->save();

        if ($request->account_id) {
            // Add or update cash flow
            $cashFlow = CashFlow::where('account_id', $request->account_id)->where('sale_payment_id', $updateSalePayment->id)->first();
            if ($cashFlow) {
                $cashFlow->debit = $request->amount;
                $cashFlow->date = $request->date;
                $cashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $cashFlow->month = date('F');
                $cashFlow->year = date('Y');
                $cashFlow->save();
                $cashFlow->balance = $this->accountUtil->adjustAccountBalance($updateSalePayment->account_id);
                $cashFlow->save();
            } else {
                if ($updateSalePayment->cashFlow) {
                    $storedAccountId = $updateSalePayment->cashFlow->account_id;
                    $updateSalePayment->cashFlow->delete();
                    $this->accountUtil->adjustAccountBalance($storedAccountId->account_id);
                }

                $addCashFlow = new CashFlow();
                $addCashFlow->account_id = $request->account_id;
                $addCashFlow->debit = $request->amount;
                $addCashFlow->sale_payment_id = $updateSalePayment->id;
                $addCashFlow->transaction_type = 2;
                $addCashFlow->cash_type = 1;
                $addCashFlow->date = $request->date;
                $addCashFlow->report_date = date('Y-m-d', strtotime($request->date));
                $addCashFlow->month = date('F');
                $addCashFlow->year = date('Y');
                $addCashFlow->admin_id = auth()->user()->id;
                $addCashFlow->save();
                $addCashFlow->balance = $this->accountUtil->adjustAccountBalance($request->account_id);
                $addCashFlow->save();
            }
        } else {
            if ($updateSalePayment->cashFlow) {
                $storedAccountId = $updateSalePayment->cashFlow->account_id;
                $updateSalePayment->cashFlow->delete();
                $this->accountUtil->adjustAccountBalance($storedAccountId->account_id);
            }
        }

        $this->saleUtil->adjustSaleInvoiceAmounts($updateSalePayment->sale);
        if ($updateSalePayment->sale->customer_id) {
            $this->customerUtil->adjustCustomerAmountForSalePaymentDue($updateSalePayment->sale->customer_id);
        }
        return response()->json('Payment is updated successfully.');
    }

    // payment details
    public function paymentDetails($paymentId)
    {
        $payment = SalePayment::with('sale', 'sale.branch', 'sale.customer')->where('id', $paymentId)->first();
        return view('sales.ajax_view.payment_details', compact('payment'));
    }

    // Delete sale payment
    public function paymentDelete(Request $request, $paymentId)
    {
        $deleteSalePayment = SalePayment::with('account', 'customer', 'sale', 'sale.sale_return', 'cashFlow')
            ->where('id', $paymentId)->first();

        if (!is_null($deleteSalePayment)) {
            //Update customer due 
            if ($deleteSalePayment->payment_type == 1) {
                // Update sale 
                $storedCustomerId = $deleteSalePayment->sale->customer_id;
                $storedSale = $deleteSalePayment->sale;
                $storedAccountId = $deleteSalePayment->account_id;
                if ($deleteSalePayment->attachment != null) {
                    if (file_exists(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment))) {
                        unlink(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment));
                    }
                }

                $deleteSalePayment->delete();

                $this->saleUtil->adjustSaleInvoiceAmounts($storedSale);
                if ($storedCustomerId) {
                    $this->customerUtil->adjustCustomerAmountForSalePaymentDue($storedCustomerId);
                }

                if ($storedAccountId) {
                    $this->accountUtil->adjustAccountBalance($storedAccountId);
                }
            } elseif ($deleteSalePayment->payment_type == 2) {
                $storedCustomerId = $deleteSalePayment->sale->customer_id;
                $storedSale = $deleteSalePayment->sale;
                $storedAccountId = $deleteSalePayment->account_id;

                // Update sale return
                $deleteSalePayment->sale->sale_return->total_return_due += $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->sale_return->total_return_due_pay -= $deleteSalePayment->paid_amount;
                $deleteSalePayment->sale->sale_return->save();

                if ($deleteSalePayment->attachment != null) {
                    if (file_exists(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment))) {
                        unlink(public_path('uploads/payment_attachment/' . $deleteSalePayment->attachment));
                    }
                }

                $deleteSalePayment->delete();

                $this->saleUtil->adjustSaleInvoiceAmounts($storedSale);
                if ($storedCustomerId) {
                    $this->customerUtil->adjustCustomerAmountForSalePaymentDue($storedCustomerId);
                }

                if ($storedAccountId) {
                    $this->accountUtil->adjustAccountBalance($storedAccountId);
                }
            }
        }
        return response()->json('Payment deleted successfully.');
    }

    // Add product modal view with data
    public function addProductModalVeiw()
    {
        $units = DB::table('units')->select('id', 'name')->get();
        $warranties =  DB::table('warranties')->select('id', 'name', 'type')->get();
        $taxes = DB::table('taxes')->select(['id', 'tax_name', 'tax_percent'])->get();
        $categories = DB::table('categories')->where('parent_category_id', NULL)->orderBy('id', 'DESC')->get();
        $brands = DB::table('brands')->select('id', 'name')->get();
        return view('sales.ajax_view.add_product_modal_view', compact('units', 'warranties', 'taxes', 'categories', 'brands'));
    }

    public function getAllSubCategory($categoryId)
    {
        $sub_categories = DB::table('categories')->where('parent_category_id', $categoryId)->get();
        return response()->json($sub_categories);
    }

    public function addProduct(Request $request)
    {
        return $this->util->addQuickProductFromAddSale($request);
    }

    // Get recent added product which has been added from pos
    public function getRecentProduct($product_id)
    {
        $branch_id = auth()->user()->branch_id;
        $product = ProductBranch::with(['product', 'product.tax', 'product.unit'])
            ->where('branch_id', $branch_id)
            ->where('product_id', $product_id)
            ->first();

        if ($product->product_quantity > 0) {
            return view('sales.ajax_view.recent_product_view', compact('product'));
        } else {
            return response()->json([
                'errorMsg' => 'Product is not added in the sale table, cause you did not add any number of opening stock in this branch.'
            ]);
        }
        
    }

    // Get sale for printing
    public function print($saleId)
    {
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
        ])->where('id', $saleId)->first();

        $previous_due = 0;
        $total_payable_amount = $sale->total_payable_amount;
        $paying_amount = $sale->paid;
        $total_due = $sale->due;
        $change_amount = 0;

        if ($sale->status == 1) {
            if ($sale->created_by == 1) {
                return view('sales.save_and_print_template.sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
            } else {
                return view('sales.save_and_print_template.pos_sale_print', compact(
                    'sale',
                    'previous_due',
                    'total_payable_amount',
                    'paying_amount',
                    'total_due',
                    'change_amount'
                ));
            }
        } elseif ($sale->status == 2) {
            return view('sales.save_and_print_template.draft_print', compact('sale'));
        } elseif ($sale->status == 4) {
            return view('sales.save_and_print_template.quotation_print', compact('sale'));
        }
    }

    // Get product price group
    public function getProductPriceGroup()
    {
        return DB::table('price_group_products')->get(['id', 'price_group_id', 'product_id', 'variant_id', 'price']);
    }

    // Recent Add sale
    public function recentSale()
    {
        $sales = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 1)
            ->where('created_by', 1)
            ->where('is_return_available', 0)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.ajax_view.recent_sale_list', compact('sales'));
    }

    // Get all recent quotations ** requested by ajax **
    public function recentQuotations()
    {
        $quotations = Sale::where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 4)
            ->where('created_by', 1)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.ajax_view.recent_quotation_list', compact('quotations'));
    }

    // Get all recent drafts ** requested by ajax **
    public function recentDrafts()
    {
        $drafts = Sale::with('customer')->where('branch_id', auth()->user()->branch_id)
            ->where('admin_id', auth()->user()->id)
            ->where('status', 2)
            ->where('created_by', 1)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return view('sales.ajax_view.recent_draft_list', compact('drafts'));
    }

    // Get notification form method
    public function getNotificationForm($saleId)
    {
    }
}
