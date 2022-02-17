<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\ProductBranch;
use App\Models\CustomerLedger;
use App\Models\SupplierLedger;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOpeningStock;
use App\Utils\InvoiceVoucherRefIdUtil;

class Util
{
    protected $invoiceVoucherRefIdUtil;
    protected $supplierUtil;
    protected $customerUtil;
    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        SupplierUtil $supplierUtil,
        CustomerUtil $customerUtil
    ) {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->supplierUtil = $supplierUtil;
        $this->customerUtil = $customerUtil;
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
        while ($b < $l) {$code .= rand(1, 9);$b++;}

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

        // Add opening stock
        $this->productUtil->addOpeningStock(
            branch_id: $request->branch_id,
            product_id: $addProduct->id,
            variant_id: NULL,
            unit_cost_inc_tax: $request->product_cost_with_tax,
            quantity: $request->quantity,
            subtotal: $request->subtotal
        );

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
            'credit_limit' => $request->credit_limit,
            'opening_balance' => $request->opening_balance ? $request->opening_balance : 0.00,
            'total_sale_due' => $request->opening_balance ? $request->opening_balance : 0.00,
        ]);

        // Add Customer Ledger
        $this->customerUtil->addCustomerLedger(
            voucher_type_id: 0,
            customer_id: $addCustomer->id,
            date: date('Y-m-d'),
            trans_id: NULL,
            amount: $request->opening_balance ? $request->opening_balance : 0
        );

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

        // Add supplier Ledger
        $this->supplierUtil->addSupplierLedger(
            voucher_type_id: 0,
            supplier_id: $addSupplier->id,
            date: date('Y-m-d'),
            trans_id: NULL,
            amount: $request->opening_balance ? $request->opening_balance : 0
        );

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

        // Add product Branch
        $addProductBranch = new ProductBranch();
        $addProductBranch->branch_id = auth()->user()->branch_id;
        $addProductBranch->product_id = $addProduct->id;
        $addProductBranch->save();

        return response()->json($addProduct);
    }

    public static function stockAccountingMethods()
    {
        return [
            1 => 'FIFO (FIRST IN - FIRST OUT)',
            2 => 'LIFO (LAST IN - FIRST OUT)',
        ];
    }

    public static function getStockAccountingMethod($index)
    {
        return self::stockAccountingMethods()[$index];
    }
    
    public static function accountType($index)
    {
        $types = [
            0 => 'N/A',
            1 => 'Cash-In-Hand',
            2 => 'Bank A/C',
            3 => 'Purchase A/C',
            4 => 'Purchase Return A/C',
            5 => 'Sales A/C',
            6 => 'Sales Return A/C',
            24 => 'Direct Income',
            25 => 'Indirect Income',
            26 => 'Capital A/C',
            7 => 'Direct Expense A/C',
            8 => 'Indirect Expense A/C',
            9 => 'Current Assets A/C',
            10 => 'Current liabilities A/C',
            11 => 'Misc. Expense A/C',
            12 => 'Misc. Income A/C',
            13 => 'Loans & Liabilities A/C',
            14 => 'Loans & Advances A/C',
            15 => 'Fixed Asset A/C',
            16 => 'Investments A/C',
            17 => 'Bank OD A/C',
            18 => 'Deposit A/C',
            19 => 'Provision A/C',
            20 => 'Reserves & Surplus A/C',
            21 => 'Payroll A/C',
            22 => 'Stock Adjustment A/C',
            23 => 'Production A/C',
        ];

        return $types[$index];
    }

    public static function allAccountTypes($forFilter = 0)
    {
        $data = [
            1 => 'Cash-In-Hand',
            2 => 'Bank A/C',
            3 => 'Purchase A/C',
            4 => 'Purchase Return A/C',
            5 => 'Sales A/C',
            6 => 'Sales Return A/C',
            7 => 'Direct Expense A/C',
            8 => 'Indirect Expense A/C',
            24 => 'Direct Income',
            25 => 'Indirect Income',
            26 => 'Capital A/C',
            9 => 'Current Assets A/C',
            10 => 'Current liabilities A/C',
            11 => 'Misc. Expense A/C',
            12 => 'Misc. Income A/C',
            13 => 'Loans & Liabilities A/C',
            14 => 'Loans & Advances A/C',
            15 => 'Fixed Asset A/C',
            16 => 'Investments A/C',
            17 => 'Bank OD A/C',
            18 => 'Deposit A/C',
            19 => 'Provision A/C',
            20 => 'Reserves & Surplus A/C',
            21 => 'Payroll A/C',
            22 => 'Stock Adjustment A/C',
            23 => 'Production A/C',
        ];

        if ($forFilter == 0) {
            if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
                return $data;
            } else {
                return $filteredType =  array_filter($data, function ($val, $key) {
                    return $key != 2;
                }, ARRAY_FILTER_USE_BOTH);
            }
        }else {
            return $data;
        }
    }
}
