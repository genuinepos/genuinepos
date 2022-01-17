<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\ProductBranch;
use App\Models\CustomerLedger;
use App\Models\SupplierLedger;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOpeningStock;
use App\Utils\InvoiceVoucherRefIdUtil;

class Util
{
    protected $invoiceVoucherRefIdUtil;
    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil
    ) {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function addQuickProductFromAddSale($request)
    {
        $addProduct = new Product();
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $request->validate(
            [
                'name' => 'required',
                'unit_id' => 'required',
                'product_price' => 'required',
                'product_cost' => 'required',
                'product_cost_with_tax' => 'required',
            ],
            [
                'unit_id.required' => 'Product unit field is required.',
            ]
        );

        // generate Customer ID
        $l = 6;
        $b = 0;
        $code = '';
        while ($b < $l) {
            $code .= rand(1, 9);
            $b++;
        }

        $addProduct->type = 1;
        $addProduct->name = $request->name;
        $addProduct->product_code = $request->product_code ? $request->product_code : $code;
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
        $addProduct->tax_type = 1;
        $addProduct->product_details = $request->product_details;
        $addProduct->is_purchased = 1;
        $addProduct->barcode_type = $request->barcode_type;
        $addProduct->warranty_id = $request->warranty_id;
        $addProduct->is_purchased = 1;
        $addProduct->is_show_in_ecom = isset($request->is_show_in_ecom) ? 1 : 0;
        $addProduct->is_show_emi_on_pos = isset($request->is_show_emi_on_pos) ? 1 : 0;
        $addProduct->quantity = $request->quantity ? $request->quantity : 0;
        $addProduct->save();

        //Add opening stock
        $addOpeningStock = new ProductOpeningStock();
        $addOpeningStock->branch_id = $request->branch_id;
        $addOpeningStock->product_id  = $addProduct->id;
        $addOpeningStock->unit_cost_inc_tax = $request->unit_cost_inc_tax;
        $addOpeningStock->quantity = $request->quantity;
        $addOpeningStock->subtotal = $request->subtotal;
        $addOpeningStock->save();

        // Add product Branch
        $addProductBranch = new ProductBranch();
        $addProductBranch->branch_id = $request->branch_id;
        $addProductBranch->product_id = $addProduct->id;
        $addProductBranch->product_quantity = $request->quantity;
        $addProductBranch->save();
        return response()->json($addProduct);
    }

    public function storeQuickCustomer($request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        $generalSettings = DB::table('general_settings')->first('prefix');
        $cusIdPrefix = json_decode($generalSettings->prefix, true)['customer_id'];
        $addCustomer = Customer::create([
            'contact_id' => $request->contact_id ? $request->contact_id : $cusIdPrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('customers'), 4, "0", STR_PAD_LEFT),
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
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

        $addCustomerLedger = new CustomerLedger();
        $addCustomerLedger->customer_id = $addCustomer->id;
        $addCustomerLedger->row_type = 3;
        $addCustomerLedger->report_date = date('Y-m-d');
        $addCustomerLedger->amount = $request->opening_balance ? $request->opening_balance : 0.00;
        $addCustomerLedger->save();

        return response()->json($addCustomer);
    }

    public function storeQuickSupplier($request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);

        $generalSettings = DB::table('general_settings')->first('prefix');
        $subIdPrefix = json_decode($generalSettings->prefix, true)['supplier_id'];
        $firstLetterOfSupplier = str_split($request->name)[0];
        $addSupplier = Supplier::create([
            'contact_id' => $request->contact_id ? $request->contact_id : $subIdPrefix . str_pad($this->invoiceVoucherRefIdUtil->getLastId('suppliers'), 4, "0", STR_PAD_LEFT),
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'alternative_phone' => $request->alternative_phone,
            'landline' => $request->landline,
            'date_of_birth' => $request->date_of_birth,
            'tax_number' => $request->tax_number,
            'pay_term' => $request->pay_term,
            'pay_term_number' => $request->pay_term_number,
            'prefix' => $firstLetterOfSupplier . $this->invoiceVoucherRefIdUtil->getLastId('suppliers'),
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
            $addSupplierLedger->report_date = date('Y-m-d');
            $addSupplierLedger->amount = $request->opening_balance;
            $addSupplierLedger->save();
        }

        return response()->json($addSupplier);
    }

    public function addQuickProductFromPurchase($request)
    {
        $addProduct = new Product();
        $tax_id = NULL;
        if ($request->tax_id) {
            $tax_id = explode('-', $request->tax_id)[0];
        }

        $request->validate(
            [
                'name' => 'required',
                'product_code' => 'required',
                'unit_id' => 'required',
                'product_price' => 'required',
                'product_cost' => 'required',
                'product_cost_with_tax' => 'required',
            ],
            [
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

    public static function stockAccountingMethods()
    {
        return  [
            1 => 'FIFO (FIRST IN - LAST OUT)',
            2 => 'LIFO (LAST IN - FAST OUT)',
        ];
    }

    public static function getStockAccountingMethod($index)
    {
        return self::stockAccountingMethods()[$index];
    }
}
