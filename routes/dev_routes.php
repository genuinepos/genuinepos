<?php

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Enums\ContactType;
use App\Models\Sales\Sale;
use App\Enums\PurchaseStatus;
use App\Models\Setups\Currency;
use App\Models\Contacts\Contact;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Enums\AccountLedgerVoucherType;
use App\Enums\ProductLedgerVoucherType;

Route::get('my-test', function () {
    try {
        DB::beginTransaction();

        // return 'ok';
        // $generalSettings = config('generalSettings');
        // $accountStartDate = $generalSettings['business_or_shop__account_start_date'];
        // $supIdPrefix = $generalSettings['prefix__supplier_id'] ? $generalSettings['prefix__supplier_id'] : 'S';
        // $purchaseInvoicePrefix = $generalSettings['prefix__purchase_invoice_prefix'] ? $generalSettings['prefix__purchase_invoice_prefix'] : 'PI';
        // $paymentVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__payment_voucher_prefix'] : 'PV';
        // $salesReturnVoucherPrefix = $generalSettings['prefix__sales_return_prefix'] ? $generalSettings['prefix__sales_return_prefix'] : 'SR';
        // $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
        // $saleInvoicePrefix = $generalSettings['prefix__sales_invoice_prefix'] ? $generalSettings['prefix__sales_invoice_prefix'] : 'SI';
        // $receiptVoucherPrefix = $generalSettings['prefix__payment_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'PV';
        // $purchaseReturnVoucherPrefix = $generalSettings['prefix__purchase_return_prefix'] ? $generalSettings['prefix__purchase_return_prefix'] : 'PR';

        // $accountGroupService = new \App\Services\Accounts\AccountGroupService();
        // $accountService = new \App\Services\Accounts\AccountService();
        // $accountOpeningBalanceService = new \App\Services\Accounts\AccountOpeningBalanceService();
        // $contactService = new \App\Services\Contacts\ContactService();
        // $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
        // $purchaseService = new \App\Services\Purchases\PurchaseService();
        // $dayBookService = new \App\Services\Accounts\DayBookService();
        // $productLedgerService = new \App\Services\Products\ProductLedgerService();
        // $accountingVoucherService = new \App\Services\Accounts\AccountingVoucherService();
        // $accountingVoucherDescriptionService = new \App\Services\Accounts\AccountingVoucherDescriptionService();
        // $accountingVoucherDescriptionReferenceService = new \App\Services\Accounts\AccountingVoucherDescriptionReferenceService();
        // $stockChainService = new \App\Services\Products\StockChainService();
        // $saleService = new \App\Services\Sales\SaleService();
        // $purchaseProductService = new \App\Services\Purchases\PurchaseProductService();
        // $salesReturnService = new \App\Services\Sales\SalesReturnService();
        // $productStockService = new \App\Services\Products\ProductStockService();
        // $codeGenerator = new \App\Services\CodeGenerationService();

        // $supplierType = ContactType::Supplier->value;
        // $customerType = ContactType::Customer->value;

        // $customerAccountGroup = $accountGroupService->singleAccountGroupByAnyCondition()
        //     ->where('sub_sub_group_number', 6)->where('is_reserved', BooleanType::True->value)->first();

        // $salesAccount = DB::table('accounts')->where('id', 15)->first();

        // $supplierAccountGroup = $accountGroupService->singleAccountGroupByAnyCondition()
        //     ->where('sub_sub_group_number', 10)->where('is_reserved', BooleanType::True->value)->first();

        // $purchaseAccount = DB::table('accounts')->where('id', 16)->first();

        // $password = env('DB_PASSWORD');
        // $host = env('DB_HOST');
        // $port = env('DB_PORT');

        // config([
        //     'database.connections.bondhon' => [
        //         'driver' => 'mysql',
        //         'host' => $host,
        //         'port' => $port,
        //         'database' => 'bondhon',
        //         'username' => 'root',
        //         'password' => $password,
        //         'charset' => 'utf8mb4',
        //         'collation' => 'utf8mb4_unicode_ci',
        //         'prefix' => '',
        //         'strict' => true,
        //         'engine' => null,
        //     ]
        // ]);

        // ///////// Add category section
        // $dbCategories = DB::connection('bondhon')->table('categories')->get();
        // $categoryService = new \App\Services\Products\CategoryService();

        // foreach ($dbCategories as $key => $dbCategory) {

        //     $exists = DB::table('categories')->where('name', $dbCategory->name)->exists();

        //     if (!$exists) {

        //         $categoryReq = new \stdClass();
        //         $categoryReq->name = $dbCategory->name;
        //         $categoryReq->description = null;
        //         $categoryReq->photo = null;

        //         $addCategory = $categoryService->addCategory(request: $categoryReq, codeGenerator: $codeGenerator);
        //     }
        // }
        // /// Add category section End

        // ////////Add category section
        // $dbBrands = DB::connection('bondhon')->table('brands')->get();
        // $brandService = new \App\Services\Products\BrandService();

        // foreach ($dbBrands as $key => $dbBrand) {

        //     $exists = DB::table('brands')->where('name', $dbBrand->name)->exists();

        //     if (!$exists) {

        //         $brandReq = new \stdClass();
        //         $brandReq->name = $dbBrand->name;
        //         $brandReq->photo = null;

        //         $addBrand = $brandService->addBrand(request: $brandReq, codeGenerator: $codeGenerator);
        //     }
        // }
        // //////////Add category section End

        // ////////Add product section
        // $unitService = new \App\Services\Products\UnitService();
        // $dbProducts = DB::connection('bondhon')->table('products')
        //     ->leftJoin('categories', 'products.category_id', 'categories.id')
        //     ->leftJoin('brands', 'products.brand_id', 'brands.id')
        //     ->leftJoin('units', 'products.unit_id', 'units.id')
        //     ->select(
        //         'products.*',
        //         'categories.name as cate_name',
        //         'brands.id as brand_id',
        //         'brands.name as brand_name',
        //         'units.name as unit_name',
        //         'units.code_name as unit_code',
        //     )->get();

        // foreach ($dbProducts as $key => $dbProduct) {

        //     $exists = DB::table('products')->where('name', $dbProduct->name)->where('product_code', $dbProduct->product_code)->exists();

        //     if (!$exists) {

        //         $cate = DB::table('categories')->where('name', $dbProduct->cate_name)->select('id')->first();
        //         $brand = DB::table('brands')->where('name', $dbProduct->brand_name)->select('id')->first();

        //         $unit = DB::table('units')->where('name', $dbProduct->unit_name)->select('id')->first();
        //         $unitId = isset($unit) ? $unit->id : null;

        //         if (!isset($unit)) {

        //             $unitReq = new \stdClass();
        //             $unitReq->name = $dbProduct->unit_name;
        //             $unitReq->short_name = $dbProduct->unit_code;
        //             $unitReq->as_a_multiplier_of_other_unit = 0;

        //             $addUnit = $unitService->addUnit(request: $unitReq, codeGenerator: $codeGenerator);
        //             $unitId = $addUnit->id;
        //         }

        //         $addProduct = new \App\Models\Products\Product();
        //         $addProduct->type = 1;
        //         $addProduct->name = $dbProduct->name;
        //         $addProduct->product_code = $dbProduct->product_code;
        //         $addProduct->category_id = $cate?->id;
        //         $addProduct->brand_id = $brand?->id;
        //         $addProduct->unit_id = $unitId;
        //         // $addProduct->has_batch_no_expire_date = $dbProduct->has_batch_no_expire_date;
        //         $addProduct->is_show_emi_on_pos = $dbProduct->is_show_emi_on_pos;
        //         $addProduct->is_purchased = 1;
        //         $addProduct->product_cost = $dbProduct->product_cost;
        //         $addProduct->product_cost_with_tax = $dbProduct->product_cost_with_tax;
        //         $addProduct->profit = $dbProduct->profit;
        //         $addProduct->product_price = $dbProduct->product_price;
        //         $addProduct->save();

        //         $addProductAccessBranch = new \App\Models\Products\ProductAccessBranch();
        //         $addProductAccessBranch->product_id = $addProduct->id;
        //         $addProductAccessBranch->save();

        //         if (auth()->user()->branch_id) {

        //             $addProductAccessBranch = new \App\Models\Products\ProductAccessBranch();
        //             $addProductAccessBranch->product_id = $addProduct->id;
        //             $addProductAccessBranch->branch_id = auth()->user()->branch_id;
        //             $addProductAccessBranch->save();
        //         }
        //     }
        // }
        // echo 'All product is done' . '</br>';

        // //////////////Add Product Opening Stocks
        // $dbProductOpeningStocks = DB::connection('bondhon')->table('product_opening_stocks')
        //     ->leftJoin('products', 'product_opening_stocks.product_id', 'products.id')
        //     ->select('product_opening_stocks.*', 'products.name as product_name', 'products.product_code')->get();

        // foreach ($dbProductOpeningStocks as $dbProductOpeningStock) {

        //     $date = $accountStartDate;

        //     $product = DB::table('products')
        //         ->where('name', $dbProductOpeningStock->product_name)
        //         ->where('product_code', $dbProductOpeningStock->product_code)->first();

        //     if (isset($product)) {

        //         $addOrEditOpeningStock = '';
        //         $openingStock = \App\Models\Products\ProductOpeningStock::where('branch_id', auth()->user()->branch_id)
        //             ->where('product_id', $product->id)->first();

        //         $warehouseId = null;
        //         if ($openingStock) {

        //             $addOrEditOpeningStock = $openingStock;
        //             // $date = $openingStock->date;
        //         } else {

        //             $addOrEditOpeningStock = new \App\Models\Products\ProductOpeningStock();
        //         }

        //         $addOrEditOpeningStock->branch_id = auth()->user()->branch_id;
        //         $addOrEditOpeningStock->warehouse_id = null;
        //         $addOrEditOpeningStock->product_id = $product->id;
        //         $addOrEditOpeningStock->variant_id = null;
        //         $addOrEditOpeningStock->quantity = $dbProductOpeningStock->quantity;
        //         $addOrEditOpeningStock->unit_cost_inc_tax = $dbProductOpeningStock->unit_cost_inc_tax;
        //         $addOrEditOpeningStock->subtotal = $dbProductOpeningStock->subtotal;
        //         $addOrEditOpeningStock->date = $date;
        //         $addOrEditOpeningStock->date_ts = date('Y-m-d H:i:s', strtotime($date . ' 01:00:00'));
        //         $addOrEditOpeningStock->save();

        //         $productLedgerService->updateProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::OpeningStock->value, date: $addOrEditOpeningStock->date, productId: $addOrEditOpeningStock->product_id, transId: $addOrEditOpeningStock->id, rate: $addOrEditOpeningStock->unit_cost_inc_tax, quantityType: 'in', quantity: $addOrEditOpeningStock->quantity, subtotal: $addOrEditOpeningStock->subtotal, variantId: $addOrEditOpeningStock->variant_id, branchId: auth()->user()->branch_id, warehouseId: $addOrEditOpeningStock->warehouse_id);

        //         $purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'opening_stock_id', transId: $addOrEditOpeningStock->id, branchId: auth()->user()->branch_id, productId: $addOrEditOpeningStock->product_id, variantId: $addOrEditOpeningStock->variant_id, quantity: $addOrEditOpeningStock->quantity, unitCostIncTax: $addOrEditOpeningStock->unit_cost_inc_tax, sellingPrice: 0, subTotal: $addOrEditOpeningStock->subtotal, createdAt: $addOrEditOpeningStock->date_ts);
        //     }
        // }
        // echo 'Product opening stock is done' . '</br>';

        //////// //Add Purchases
        // $dbPurchases = DB::connection('bondhon')->table('purchases')
        //     ->leftJoin('suppliers', 'purchases.supplier_id', 'suppliers.id')
        //     ->select(
        //         'purchases.*',
        //         'suppliers.name',
        //         'suppliers.business_name',
        //         'suppliers.phone',
        //         'suppliers.alternative_phone',
        //         'suppliers.landline',
        //         'suppliers.date_of_birth',
        //         'suppliers.opening_balance',
        //         'suppliers.address',
        //         'suppliers.email',
        //         'suppliers.shipping_address',
        //         'suppliers.city',
        //         'suppliers.state',
        //         'suppliers.country',
        //         'suppliers.zip_code',
        //         'suppliers.tax_number',
        //         'suppliers.pay_term',
        //         'suppliers.pay_term_number',
        //     )->get();

        // foreach ($dbPurchases as $dbPurchase) {

        //     $existsSupplier = DB::table('contacts')
        //         ->where('contacts.type', $supplierType)
        //         ->where('contacts.name', $dbPurchase->name)
        //         ->where('contacts.phone', $dbPurchase->phone)
        //         ->join('accounts', 'contacts.id', 'accounts.contact_id')
        //         ->select('contacts.id', 'accounts.id as supplier_account_id')->first();

        //     $supplierAccountId = isset($existsSupplier) ? $existsSupplier->supplier_account_id : null;

        //     if (!isset($existsSupplier)) {

        //         $addContact = $contactService->addContact(type: $supplierType, codeGenerator: $codeGenerator, contactIdPrefix: $supIdPrefix, name: $dbPurchase->name, phone: $dbPurchase->phone, businessName: $dbPurchase->business_name, email: $dbPurchase->email, alternativePhone: $dbPurchase->alternative_phone, landLine: $dbPurchase->landline, dateOfBirth: $dbPurchase->date_of_birth, taxNumber: $dbPurchase->tax_number, customerGroupId: null, address: $dbPurchase->address, city: $dbPurchase->city, state: $dbPurchase->state, country: $dbPurchase->country, zipCode: $dbPurchase->zip_code, shippingAddress: $dbPurchase->shipping_address, payTerm: $dbPurchase->pay_term, payTermNumber: $dbPurchase->pay_term_number, creditLimit: null, openingBalance: $dbPurchase->opening_balance, openingBalanceType: 'cr');

        //         $addAccount = $accountService->addAccount(name: $dbPurchase->name, accountGroup: $supplierAccountGroup, phone: $dbPurchase->phone, address: $dbPurchase->address, openingBalance: $dbPurchase->opening_balance, openingBalanceType: 'cr', contactId: $addContact->id);

        //         $accountOpeningBalanceService->addOrUpdateAccountOpeningBalance(
        //             branchId: auth()->user()->branch_id,
        //             accountId: $addAccount->id,
        //             openingBalanceType: 'cr',
        //             openingBalance: $dbPurchase->opening_balance ? $dbPurchase->opening_balance : 0,
        //         );

        //         $accountLedgerService->addAccountLedgerEntry(
        //             voucher_type_id: AccountLedgerVoucherType::OpeningBalance->value,
        //             date: '01-01-2023',
        //             account_id: $addAccount->id,
        //             trans_id: $addAccount->id,
        //             amount: $dbPurchase->opening_balance ? $dbPurchase->opening_balance : 0,
        //             amount_type: 'credit',
        //             branch_id: auth()->user()->branch_id,
        //         );

        //         $supplierAccountId = $addAccount?->id;
        //     }

        //     $existsPurchase = DB::table('purchases')
        //         ->where('date', $dbPurchase->date)
        //         ->where('total_item', $dbPurchase->total_item)
        //         ->where('net_total_amount', $dbPurchase->net_total_amount)
        //         ->where('total_purchase_amount', $dbPurchase->total_purchase_amount)
        //         ->first();

        //     if (!isset($existsPurchase)) {

        //         $updateLastCreated = $purchaseService->purchaseByAnyConditions()->where('is_last_created', BooleanType::True->value)->where('branch_id', auth()->user()->branch_id)->select('id', 'is_last_created')->first();

        //         if ($updateLastCreated) {

        //             $updateLastCreated->is_last_created = BooleanType::False->value;
        //             $updateLastCreated->save();
        //         }

        //         $pInvoiceId = $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::Purchase->value, prefix: $purchaseInvoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        //         $addPurchase = new \App\Models\Purchases\Purchase();
        //         $addPurchase->invoice_id = $pInvoiceId;
        //         $addPurchase->branch_id = auth()->user()->branch_id;
        //         $addPurchase->supplier_account_id = $supplierAccountId;
        //         $addPurchase->purchase_account_id = $purchaseAccount->id;
        //         $addPurchase->admin_id = 1;
        //         $addPurchase->total_item = $dbPurchase->total_item;
        //         // $addPurchase->total_qty = $dbPurchase->total_qty;
        //         $addPurchase->order_discount = $dbPurchase->order_discount ? $dbPurchase->order_discount : 0;
        //         $addPurchase->order_discount_type = $dbPurchase->order_discount_type;
        //         $addPurchase->order_discount_amount = $dbPurchase->order_discount_amount;
        //         $addPurchase->purchase_tax_ac_id = null;
        //         $addPurchase->purchase_tax_percent = 0;
        //         $addPurchase->purchase_tax_amount = 0;
        //         $addPurchase->shipment_charge = $dbPurchase->shipment_charge;
        //         $addPurchase->net_total_amount = $dbPurchase->net_total_amount;
        //         $addPurchase->total_purchase_amount = $dbPurchase->total_purchase_amount;
        //         $purchasePaid = $dbPurchase->paid;
        //         $addPurchase->due = $dbPurchase->total_purchase_amount;
        //         $addPurchase->shipment_details = $dbPurchase->shipment_details;
        //         // $addPurchase->purchase_note = $dbPurchase->purchase_note;
        //         $addPurchase->purchase_status = PurchaseStatus::Purchase->value;
        //         $addPurchase->is_purchased = BooleanType::True->value;
        //         $addPurchase->date = $dbPurchase->date;
        //         $addPurchase->report_date = $dbPurchase->report_date;
        //         $addPurchase->is_last_created = BooleanType::True->value;
        //         $addPurchase->purchase_order_id = null;
        //         $addPurchase->save();

        //         // Add Day Book entry for Purchase
        //         $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Purchase->value, date: $dbPurchase->date, accountId: $addPurchase->supplier_account_id, transId: $addPurchase->id, amount: $dbPurchase->total_purchase_amount, amountType: 'credit');

        //         // Add Purchase A/c Ledger Entry
        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Purchase->value, date: $addPurchase->date, account_id: $addPurchase->purchase_account_id, trans_id: $addPurchase->id, amount: $addPurchase->total_purchase_amount, amount_type: 'debit');

        //         // Add supplier A/c ledger Entry For Purchase
        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Purchase->value, account_id: $addPurchase->supplier_account_id, date: $addPurchase->date, trans_id: $addPurchase->id, amount: $addPurchase->total_purchase_amount, amount_type: 'credit');

        //         $dbPurchaseProducts =  DB::connection('bondhon')->table('purchase_products')
        //             ->leftJoin('products', 'purchase_products.product_id', 'products.id')
        //             ->where('purchase_id', $dbPurchase->id)
        //             ->select('purchase_products.*', 'products.name as product_name', 'products.product_code')
        //             ->get();

        //         foreach ($dbPurchaseProducts as $dbPurchaseProduct) {

        //             $product = DB::table('products')
        //                 ->where('name', $dbPurchaseProduct->product_name)
        //                 ->where('product_code', $dbPurchaseProduct->product_code)
        //                 ->select('products.id', 'products.unit_id')
        //                 ->first();

        //             $addPurchaseProduct = new \App\Models\Purchases\PurchaseProduct();
        //             $addPurchaseProduct->purchase_id = $addPurchase->id;
        //             $addPurchaseProduct->product_id = $product?->id;
        //             $addPurchaseProduct->quantity = $dbPurchaseProduct->quantity;
        //             $addPurchaseProduct->label_left_qty = $dbPurchaseProduct->left_qty;
        //             $addPurchaseProduct->left_qty = $dbPurchaseProduct->quantity;
        //             $addPurchaseProduct->unit_id =  $product?->unit_id;
        //             $addPurchaseProduct->unit_cost_exc_tax = $dbPurchaseProduct->unit_cost;
        //             $addPurchaseProduct->unit_discount = $dbPurchaseProduct->unit_discount;
        //             $addPurchaseProduct->unit_cost_with_discount = $dbPurchaseProduct->unit_cost_with_discount;
        //             $addPurchaseProduct->subtotal = $dbPurchaseProduct->subtotal;
        //             $addPurchaseProduct->tax_type = 1;
        //             $addPurchaseProduct->net_unit_cost = $dbPurchaseProduct->net_unit_cost;
        //             $addPurchaseProduct->line_total = $dbPurchaseProduct->line_total;
        //             $addPurchaseProduct->branch_id = auth()->user()->branch_id;

        //             $addPurchaseProduct->profit_margin = $dbPurchaseProduct->profit_margin;
        //             $addPurchaseProduct->selling_price = $dbPurchaseProduct->selling_price;

        //             $addPurchaseProduct->lot_no = $dbPurchaseProduct->lot_no;

        //             // $addPurchaseProduct->batch_number = $dbPurchaseProduct->batch_number;
        //             // $addPurchaseProduct->expire_date = $dbPurchaseProduct->expire_date;
        //             $addPurchaseProduct->created_at = date('Y-m-d H:i:s', strtotime($dbPurchase->date . date(' H:i:s')));

        //             $addPurchaseProduct->save();

        //             // Add Product Ledger Entry
        //             $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Purchase->value, date: $addPurchase->date, productId: $addPurchaseProduct->product_id, transId: $addPurchaseProduct->id, rate: $addPurchaseProduct->net_unit_cost, quantityType: 'in', quantity: $addPurchaseProduct->quantity, subtotal: $addPurchaseProduct->line_total, variantId: $addPurchaseProduct->variant_id, warehouseId: null);
        //         }

        //         if ($purchasePaid > 0) {

        //             $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $addPurchase->date, voucherType: AccountingVoucherType::Payment->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $purchasePaid, creditTotal: $purchasePaid, totalAmount: $purchasePaid, purchaseRefId: $addPurchase->id);

        //             // Add Debit Account Accounting voucher Description
        //             $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $addPurchase->supplier_account_id, paymentMethodId: null, amountType: 'dr', amount: $purchasePaid);

        //             // Add Day Book entry for Payment
        //             $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Payment->value, date: $addPurchase->date, accountId: $addPurchase->supplier_account_id, transId: $addAccountingVoucherDebitDescription->id, amount: $purchasePaid, amountType: 'debit');

        //             // Add Accounting VoucherDescription References
        //             $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $addPurchase->supplier_account_id, amount: $purchasePaid, refIdColName: 'purchase_id', refIds: [$addPurchase->id]);

        //             //Add Debit Ledger Entry
        //             $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $addPurchase->date, account_id: $addPurchase->supplier_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $purchasePaid, amount_type: 'debit', cash_bank_account_id: 14);

        //             // Add Payment Description Credit Entry
        //             $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: 14, paymentMethodId: 1, amountType: 'cr', amount: $purchasePaid, note: null);

        //             //Add Credit Ledger Entry
        //             $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $addPurchase->date, account_id: 14, trans_id: $addAccountingVoucherCreditDescription->id, amount: $purchasePaid, amount_type: 'credit');
        //         }

        //         echo 'Purchase Created-' . $pInvoiceId . '</br>';
        //     }
        // }

        // echo 'All Purchases is Created-' . '</br>';

        // /// Add Sales
        // $dbSales = DB::connection('bondhon')->table('sales')
        //     ->leftJoin('customers', 'sales.customer_id', 'customers.id')
        //     ->select(
        //         'sales.*',
        //         'customers.name',
        //         'customers.business_name',
        //         'customers.phone',
        //         'customers.alternative_phone',
        //         'customers.landline',
        //         'customers.date_of_birth',
        //         'customers.opening_balance',
        //         'customers.address',
        //         'customers.email',
        //         'customers.shipping_address',
        //         'customers.city',
        //         'customers.state',
        //         'customers.country',
        //         'customers.zip_code',
        //         'customers.tax_number',
        //         'customers.pay_term',
        //         'customers.pay_term_number',
        //         'customers.credit_limit',
        //     )
        //     // ->skip(20000) // skip first 20000 rows
        //     // ->take(4000) //current 24000
        //     ->orderBy('sales.id', 'asc')->get();

        // foreach ($dbSales as $dbSale) {

        //     $existsCustomer = DB::table('contacts')
        //         ->where('contacts.type', $customerType)
        //         ->where('contacts.name', $dbSale->name)
        //         ->where('contacts.phone', $dbSale->phone)
        //         ->join('accounts', 'contacts.id', 'accounts.contact_id')
        //         ->select('contacts.id', 'accounts.id as customer_account_id')->first();

        //     $customerAccountId = $existsCustomer?->customer_account_id;
        //     $__customerAccountId = $dbSale?->customer_id == null ? 23 : $customerAccountId;

        //     if (!isset($__customerAccountId)) {

        //         $addContact = $contactService->addContact(type: $customerType, codeGenerator: $codeGenerator, contactIdPrefix: $cusIdPrefix, name: $dbSale->name, phone: $dbSale->phone, businessName: $dbSale->business_name, email: $dbSale->email, alternativePhone: $dbSale->alternative_phone, landLine: $dbSale->landline, dateOfBirth: $dbSale->date_of_birth, taxNumber: $dbSale->tax_number, customerGroupId: null, address: $dbSale->address, city: $dbSale->city, state: $dbSale->state, country: $dbSale->country, zipCode: $dbSale->zip_code, shippingAddress: $dbSale->shipping_address, payTerm: $dbSale->pay_term, payTermNumber: $dbSale->pay_term_number, creditLimit: null, openingBalance: $dbSale->opening_balance, openingBalanceType: 'cr');

        //         $addAccount = $accountService->addAccount(name: $dbSale->name, accountGroup: $customerAccountGroup, phone: $dbSale->phone, address: $dbSale->address, openingBalance: $dbSale->opening_balance, openingBalanceType: 'dr', contactId: $addContact->id);

        //         $accountLedgerService->addAccountLedgerEntry(
        //             voucher_type_id: AccountLedgerVoucherType::OpeningBalance->value,
        //             date: '01-01-2023',
        //             account_id: $addAccount->id,
        //             trans_id: $addAccount->id,
        //             amount: $dbSale->opening_balance ? $dbSale->opening_balance : 0,
        //             amount_type: 'debit',
        //             branch_id: auth()->user()->branch_id,
        //         );

        //         $__customerAccountId = $addAccount?->id;
        //     }

        //     $existsSale = DB::table('sales')
        //         ->where('date', $dbSale->date)
        //         ->where('total_item', $dbSale->total_item)
        //         ->where('net_total_amount', $dbSale->net_total_amount)
        //         ->where('total_invoice_amount', $dbSale->total_payable_amount)
        //         ->where('order_discount_amount', $dbSale->order_discount_amount)
        //         ->first();

        //     if (!isset($existsSale)) {

        //         $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'invoice_id', prefix: $saleInvoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        //         $addSale = new \App\Models\Sales\Sale();
        //         $addSale->invoice_id = $transId;
        //         $addSale->created_by_id = auth()->user()->id;
        //         $addSale->sale_account_id = $salesAccount->id;
        //         $addSale->branch_id = auth()->user()->branch_id;
        //         $addSale->customer_account_id = $__customerAccountId;
        //         $addSale->status = SaleStatus::Final->value;
        //         $addSale->date = $dbSale->date;
        //         $addSale->date_ts = $dbSale->report_date;
        //         $addSale->sale_date_ts = $dbSale->report_date;
        //         $addSale->total_item = $dbSale->total_item;
        //         // $addSale->total_qty = $dbSale->total_qty;
        //         // $addSale->total_sold_qty = $dbSale->total_qty;
        //         $addSale->net_total_amount = $dbSale->net_total_amount;
        //         $addSale->order_discount_type = $dbSale->order_discount_type;
        //         $addSale->order_discount = $dbSale->order_discount;
        //         $addSale->order_discount_amount = $dbSale->order_discount_amount;
        //         $addSale->shipment_charge = $dbSale->shipment_charge;
        //         $addSale->shipment_details = $dbSale->shipment_details;
        //         $addSale->shipment_address = $dbSale->shipment_address;
        //         $addSale->shipment_status = 0;
        //         $addSale->delivered_to = $dbSale->delivered_to;
        //         // $addSale->note = $dbSale->note;
        //         $addSale->change_amount = 0;
        //         $addSale->total_invoice_amount = $dbSale->total_payable_amount;
        //         $salePaid = $dbSale->customer_id == null ? $dbSale->total_payable_amount : $dbSale->paid;
        //         $addSale->due = $dbSale->total_payable_amount;
        //         $addSale->sale_screen = $dbSale->created_by;
        //         $addSale->save();

        //         // Add Day Book entry for Final Sale or Sales Order
        //         $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Sales->value, date: $addSale->date, accountId: $addSale->customer_account_id, transId: $addSale->id, amount: $addSale->total_invoice_amount, amountType: 'debit');

        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, date: $addSale->date, account_id: $addSale->sale_account_id, trans_id: $addSale->id, amount: $addSale->total_invoice_amount, amount_type: 'credit');

        //         // Add supplier A/c ledger Entry For Sales
        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: $addSale->customer_account_id, date: $addSale->date, trans_id: $addSale->id, amount: $addSale->total_invoice_amount, amount_type: 'debit');

        //         $dbSaleProducts =  DB::connection('bondhon')->table('sale_products')
        //             ->leftJoin('products', 'sale_products.product_id', 'products.id')
        //             ->where('sale_id', $dbSale->id)
        //             ->select('sale_products.*', 'products.name as product_name', 'products.product_code')
        //             ->get();

        //         foreach ($dbSaleProducts as $dbSaleProduct) {

        //             $product = DB::table('products')
        //                 ->where('name', $dbSaleProduct->product_name)
        //                 ->where('product_code', $dbSaleProduct->product_code)
        //                 ->select('products.id', 'products.unit_id')
        //                 ->first();

        //             $addSaleProduct = new \App\Models\Sales\SaleProduct();
        //             $addSaleProduct->sale_id = $addSale->id;
        //             $addSaleProduct->branch_id = $addSale->branch_id;
        //             $addSaleProduct->product_id = $product?->id;
        //             $addSaleProduct->quantity = $dbSaleProduct->quantity;
        //             $addSaleProduct->unit_discount_type = $dbSaleProduct->unit_discount_type;
        //             $addSaleProduct->unit_discount = $dbSaleProduct->unit_discount;
        //             $addSaleProduct->unit_discount_amount = $dbSaleProduct->unit_discount_amount;
        //             $addSaleProduct->tax_type = 1;
        //             $addSaleProduct->unit_id = $product?->unit_id;
        //             $addSaleProduct->unit_cost_inc_tax = $dbSaleProduct->unit_cost_inc_tax;
        //             $addSaleProduct->unit_price_exc_tax = $dbSaleProduct->unit_price_exc_tax;
        //             $addSaleProduct->unit_price_inc_tax = $dbSaleProduct->unit_price_inc_tax;
        //             $addSaleProduct->subtotal = $dbSaleProduct->subtotal;
        //             $addSaleProduct->description = $dbSaleProduct->description;
        //             $addSaleProduct->save();

        //             // Add Product Ledger Entry
        //             $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::Sales->value, date: $addSale->date, productId: $addSaleProduct->product_id, transId: $addSaleProduct->id, rate: $addSaleProduct->unit_price_inc_tax, quantityType: 'out', quantity: $addSaleProduct->quantity, subtotal: $addSaleProduct->subtotal, warehouseId: null);
        //         }

        //         if ($salePaid > 0) {

        //             $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $addSale->date, voucherType: AccountingVoucherType::Receipt->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $salePaid, creditTotal: $salePaid, totalAmount: $salePaid, saleRefId: $addSale->id);

        //             // Add Debit Account Accounting voucher Description
        //             $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: 14, paymentMethodId: 1, amountType: 'dr', amount: $salePaid);

        //             //Add Debit Ledger Entry
        //             $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $addSale->date, account_id: 14, trans_id: $addAccountingVoucherDebitDescription->id, amount: $salePaid, amount_type: 'debit');

        //             // Add Payment Description Credit Entry
        //             $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $addSale->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $salePaid, note: null);

        //             // Add Accounting VoucherDescription References
        //             $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: $addSale->customer_account_id, amount: $salePaid, refIdColName: 'sale_id', refIds: [$addSale->id]);

        //             // Add Day Book entry for Receipt
        //             $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Receipt->value, date: $addSale->date, accountId: $addSale->customer_account_id, transId: $addAccountingVoucherCreditDescription->id, amount: $salePaid, amountType: 'credit');

        //             //Add Credit Ledger Entry
        //             $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $addSale->date, account_id: $addSale->customer_account_id, trans_id: $addAccountingVoucherCreditDescription->id, amount: $salePaid, amount_type: 'credit', cash_bank_account_id: 14);
        //         }

        //         echo 'Sales Created-' . $transId . '</br>';
        //     }
        // }
        // echo 'All Sale is done' . '</br>';

        ///Add Purchase Returns
        // $dbPurchaseReturns = DB::connection('bondhon')->table('purchase_returns')
        //     ->leftJoin('suppliers', 'purchase_returns.supplier_id', 'suppliers.id')
        //     ->select(
        //         'purchase_returns.*',
        //         'suppliers.name',
        //         'suppliers.business_name',
        //         'suppliers.phone',
        //         'suppliers.alternative_phone',
        //         'suppliers.landline',
        //         'suppliers.date_of_birth',
        //         'suppliers.opening_balance',
        //         'suppliers.address',
        //         'suppliers.email',
        //         'suppliers.shipping_address',
        //         'suppliers.city',
        //         'suppliers.state',
        //         'suppliers.country',
        //         'suppliers.zip_code',
        //         'suppliers.tax_number',
        //         'suppliers.pay_term',
        //         'suppliers.pay_term_number',
        //     )->get();

        // foreach ($dbPurchaseReturns as $dbPurchaseReturn) {

        //     $existsSupplier = DB::table('contacts')
        //         ->where('contacts.type', $supplierType)
        //         ->where('contacts.name', $dbPurchaseReturn->name)
        //         ->where('contacts.phone', $dbPurchaseReturn->phone)
        //         ->join('accounts', 'contacts.id', 'accounts.contact_id')
        //         ->select('contacts.id', 'accounts.id as supplier_account_id')->first();

        //     $supplierAccountId = isset($existsSupplier) ? $existsSupplier->supplier_account_id : null;

        //     if (!isset($existsSupplier)) {

        //         $addContact = $contactService->addContact(type: $supplierType, codeGenerator: $codeGenerator, contactIdPrefix: $supIdPrefix, name: $dbPurchaseReturn->name, phone: $dbPurchaseReturn->phone, businessName: $dbPurchaseReturn->business_name, email: $dbPurchaseReturn->email, alternativePhone: $dbPurchaseReturn->alternative_phone, landLine: $dbPurchaseReturn->landline, dateOfBirth: $dbPurchaseReturn->date_of_birth, taxNumber: $dbPurchaseReturn->tax_number, customerGroupId: null, address: $dbPurchaseReturn->address, city: $dbPurchaseReturn->city, state: $dbPurchaseReturn->state, country: $dbPurchaseReturn->country, zipCode: $dbPurchaseReturn->zip_code, shippingAddress: $dbPurchaseReturn->shipping_address, payTerm: $dbPurchaseReturn->pay_term, payTermNumber: $dbPurchaseReturn->pay_term_number, creditLimit: null, openingBalance: $dbPurchaseReturn->opening_balance, openingBalanceType: 'cr');

        //         $addAccount = $accountService->addAccount(name: $dbPurchaseReturn->name, accountGroup: $supplierAccountGroup, phone: $dbPurchaseReturn->phone, address: $dbPurchaseReturn->address, openingBalance: $dbPurchaseReturn->opening_balance, openingBalanceType: 'cr', contactId: $addContact->id);

        //         $accountOpeningBalanceService->addOrUpdateAccountOpeningBalance(
        //             branchId: auth()->user()->branch_id,
        //             accountId: $addAccount->id,
        //             openingBalanceType: 'cr',
        //             openingBalance: $dbPurchaseReturn->opening_balance ? $dbPurchaseReturn->opening_balance : 0,
        //         );

        //         $accountLedgerService->addAccountLedgerEntry(
        //             voucher_type_id: AccountLedgerVoucherType::OpeningBalance->value,
        //             date: '01-01-2023',
        //             account_id: $addAccount->id,
        //             trans_id: $addAccount->id,
        //             amount: $dbPurchaseReturn->opening_balance ? $dbPurchaseReturn->opening_balance : 0,
        //             amount_type: 'credit',
        //             branch_id: auth()->user()->branch_id,
        //         );

        //         $supplierAccountId = $addAccount?->id;
        //     }

        //     $existsPurchaseReturn = DB::table('purchase_returns')
        //         ->where('total_return_amount', $dbPurchaseReturn->total_return_amount)
        //         ->where('date', $dbPurchaseReturn->date)
        //         ->first();

        //     if (!isset($existsPurchaseReturn)) {

        //         $voucherNo = $codeGenerator->generateMonthWise(table: 'purchase_returns', column: 'voucher_no', prefix: $purchaseReturnVoucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        //         $addPurchaseReturn = new \App\Models\Purchases\PurchaseReturn();
        //         $addPurchaseReturn->branch_id = auth()->user()->branch_id;
        //         $addPurchaseReturn->voucher_no = $voucherNo;
        //         $addPurchaseReturn->supplier_account_id = $supplierAccountId;
        //         $addPurchaseReturn->purchase_account_id = $purchaseAccount->id;
        //         // $addPurchaseReturn->total_item = $dbPurchaseReturn->total_item;
        //         // $addPurchaseReturn->total_qty = $dbPurchaseReturn->total_qty;
        //         $addPurchaseReturn->net_total_amount = $dbPurchaseReturn->total_return_amount;
        //         $addPurchaseReturn->return_discount_type = 1;
        //         $addPurchaseReturn->total_return_amount = $dbPurchaseReturn->total_return_amount;
        //         $addPurchaseReturn->due = $dbPurchaseReturn->total_return_amount;
        //         $addPurchaseReturn->date = $dbPurchaseReturn->date;
        //         $addPurchaseReturn->date_ts = $dbPurchaseReturn->report_date;
        //         // $addPurchaseReturn->note = $dbPurchaseReturn->note;
        //         $addPurchaseReturn->created_by_id = auth()->user()->id;
        //         $addPurchaseReturn->save();

        //         $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::PurchaseReturn->value, date: $addPurchaseReturn->date, accountId: $addPurchaseReturn->supplier_account_id, transId: $addPurchaseReturn->id, amount: $addPurchaseReturn->total_return_amount, amountType: 'debit');

        //         // Add Purchase A/c Ledger Entry
        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturn->value, date: $addPurchaseReturn->date, account_id: $addPurchaseReturn->purchase_account_id, trans_id: $addPurchaseReturn->id, amount: $addPurchaseReturn->total_return_amount, amount_type: 'credit');

        //         // Add supplier A/c ledger Entry For Purchase
        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::PurchaseReturn->value, account_id: $addPurchaseReturn->supplier_account_id, date: $addPurchaseReturn->date, trans_id: $addPurchaseReturn->id, amount: $addPurchaseReturn->total_return_amount, amount_type: 'debit');

        //         $dbPurchaseReturnProducts =  DB::connection('bondhon')->table('purchase_return_products')
        //             ->leftJoin('products', 'purchase_return_products.product_id', 'products.id')
        //             ->where('purchase_return_products.purchase_return_id', $dbPurchaseReturn->id)
        //             ->select('purchase_return_products.*', 'products.name as product_name', 'products.product_code')
        //             ->get();

        //         foreach ($dbPurchaseReturnProducts as $dbPurchaseReturnProduct) {

        //             $product = DB::table('products')
        //                 ->where('name', $dbPurchaseReturnProduct->product_name)
        //                 ->where('product_code', $dbPurchaseReturnProduct->product_code)
        //                 ->select('products.id', 'products.unit_id')
        //                 ->first();

        //             $addPurchaseReturnProduct = new \App\Models\Purchases\PurchaseReturnProduct();
        //             $addPurchaseReturnProduct->purchase_return_id = $addPurchaseReturn->id;
        //             $addPurchaseReturnProduct->product_id = $product?->id;
        //             $addPurchaseReturnProduct->return_qty = $dbPurchaseReturnProduct->return_qty;
        //             $addPurchaseReturnProduct->purchased_qty = 0;
        //             $addPurchaseReturnProduct->unit_id = $product->unit_id;
        //             $addPurchaseReturnProduct->unit_cost_exc_tax = $dbPurchaseReturnProduct->unit_cost;
        //             $addPurchaseReturnProduct->unit_discount_type = 1;
        //             $addPurchaseReturnProduct->unit_cost_inc_tax = $dbPurchaseReturnProduct->unit_cost;
        //             $addPurchaseReturnProduct->return_subtotal = $dbPurchaseReturnProduct->return_subtotal;
        //             $addPurchaseReturnProduct->save();

        //             // Add Product Ledger Entry
        //             $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::PurchaseReturn->value, date: $addPurchaseReturn->date, productId: $product?->id, transId: $addPurchaseReturnProduct->id, rate: $addPurchaseReturnProduct->unit_cost_inc_tax, quantityType: 'out', quantity: $addPurchaseReturnProduct->return_qty, subtotal: $addPurchaseReturnProduct->return_subtotal, variantId: null, warehouseId: null);
        //         }

        //         echo 'Purchase Return Created-' . $voucherNo . '</br>';
        //     }
        // }
        // echo 'All Purchase Returns is done-' . '</br>';

        // /// Add Sale Returns
        // $dbSaleReturns = DB::connection('bondhon')->table('sale_returns')
        //     ->leftJoin('customers', 'sale_returns.customer_id', 'customers.id')
        //     ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
        //     ->select(
        //         'sale_returns.*',
        //         'sales.report_date as parent_sale_date',
        //         'customers.name',
        //         'customers.business_name',
        //         'customers.phone',
        //         'customers.alternative_phone',
        //         'customers.landline',
        //         'customers.date_of_birth',
        //         'customers.opening_balance',
        //         'customers.address',
        //         'customers.email',
        //         'customers.shipping_address',
        //         'customers.city',
        //         'customers.state',
        //         'customers.country',
        //         'customers.zip_code',
        //         'customers.tax_number',
        //         'customers.pay_term',
        //         'customers.pay_term_number',
        //     )->get();

        // foreach ($dbSaleReturns as $dbSaleReturn) {

        //     $existsCustomer = DB::table('contacts')
        //         ->where('contacts.type', $customerType)
        //         ->where('contacts.name', $dbSaleReturn->name)
        //         ->where('contacts.phone', $dbSaleReturn->phone)
        //         ->join('accounts', 'contacts.id', 'accounts.contact_id')
        //         ->select('contacts.id', 'accounts.id as customer_account_id')->first();

        //     $customerAccountId = $existsCustomer?->customer_account_id;
        //     $__customerAccountId = $dbSaleReturn?->customer_id == null ? 23 : $customerAccountId;

        //     if (!isset($__customerAccountId)) {

        //         $addContact = $contactService->addContact(type: $customerType, codeGenerator: $codeGenerator, contactIdPrefix: $supIdPrefix, name: $dbSaleReturn->name, phone: $dbSaleReturn->phone, businessName: $dbSaleReturn->business_name, email: $dbSaleReturn->email, alternativePhone: $dbSaleReturn->alternative_phone, landLine: $dbSaleReturn->landline, dateOfBirth: $dbSaleReturn->date_of_birth, taxNumber: $dbSaleReturn->tax_number, customerGroupId: null, address: $dbSaleReturn->address, city: $dbSaleReturn->city, state: $dbSaleReturn->state, country: $dbSaleReturn->country, zipCode: $dbSaleReturn->zip_code, shippingAddress: $dbSaleReturn->shipping_address, payTerm: $dbSaleReturn->pay_term, payTermNumber: $dbSaleReturn->pay_term_number, creditLimit: null, openingBalance: $dbSaleReturn->opening_balance, openingBalanceType: 'cr');

        //         $addAccount = $accountService->addAccount(name: $dbSaleReturn->name, accountGroup: $customerAccountGroup, phone: $dbSale->phone, address: $dbSaleReturn->address, openingBalance: $dbSaleReturn->opening_balance, openingBalanceType: 'dr', contactId: $addContact->id);

        //         $accountLedgerService->addAccountLedgerEntry(
        //             voucher_type_id: AccountLedgerVoucherType::OpeningBalance->value,
        //             date: '01-01-2023',
        //             account_id: $addAccount->id,
        //             trans_id: $addAccount->id,
        //             amount: $dbSaleReturn->opening_balance ? $dbSaleReturn->opening_balance : 0,
        //             amount_type: 'debit',
        //             branch_id: auth()->user()->branch_id,
        //         );

        //         $__customerAccountId = $addAccount?->id;
        //     }

        //     $parentSaleId = null;
        //     if ($dbSaleReturn->parent_sale_date) {

        //         $parentSale = DB::table('sales')->where('sale_date_ts', $dbSaleReturn->parent_sale_date)->first();
        //         $parentSaleId = $parentSale?->id;
        //         echo 'Parent Sale Id' . $parentSaleId . '</br>';
        //     }

        //     $existsSaleReturn = DB::table('sale_returns')
        //         ->where('total_return_amount', $dbSaleReturn->total_return_amount)
        //         ->where('date', $dbSaleReturn->date)
        //         ->first();

        //     if (!isset($existsSaleReturn)) {

        //         // generate invoice ID
        //         $voucherNo = $codeGenerator->generateMonthWise(table: 'sale_returns', column: 'voucher_no', prefix: $salesReturnVoucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        //         $addSalesReturn = new \App\Models\Sales\SaleReturn();
        //         $addSalesReturn->branch_id = auth()->user()->branch_id;
        //         $addSalesReturn->voucher_no = $voucherNo;
        //         $addSalesReturn->sale_id = $parentSaleId;
        //         $addSalesReturn->customer_account_id = $__customerAccountId;
        //         $addSalesReturn->sale_account_id = $salesAccount?->id;
        //         $addSalesReturn->total_item = $dbSaleReturn->total_item;
        //         $addSalesReturn->total_qty = $dbSaleReturn->total_qty;
        //         $addSalesReturn->net_total_amount = $dbSaleReturn->net_total_amount;
        //         $addSalesReturn->return_discount = $dbSaleReturn->return_discount ? $dbSaleReturn->return_discount : 0;
        //         $addSalesReturn->return_discount_type = $dbSaleReturn->return_discount_type;
        //         $addSalesReturn->return_discount_amount = $dbSaleReturn->return_discount_amount ? $dbSaleReturn->return_discount_amount : 0;
        //         $addSalesReturn->total_return_amount = $dbSaleReturn->total_return_amount;
        //         $saleReturnPaid = $dbSaleReturn?->customer_id == null ? $dbSaleReturn->total_return_amount : $dbSaleReturn->total_return_due_pay;
        //         $addSalesReturn->due = $dbSaleReturn->total_return_amount;
        //         $addSalesReturn->date = $dbSaleReturn->date;
        //         $addSalesReturn->date_ts = $dbSaleReturn->report_date;
        //         // $addSalesReturn->note = $dbSaleReturn->note;
        //         $addSalesReturn->created_by_id = auth()->user()->id;
        //         $addSalesReturn->save();

        //         $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::SalesReturn->value, date: $addSalesReturn->date, accountId: $addSalesReturn->customer_account_id, transId: $addSalesReturn->id, amount: $addSalesReturn->total_return_amount, amountType: 'credit');

        //         // Add sales A/c Ledger Entry
        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturn->value, date: $addSalesReturn->date, account_id: $addSalesReturn->sale_account_id, trans_id: $addSalesReturn->id, amount: $addSalesReturn->total_return_amount, amount_type: 'debit');

        //         // Add Customer A/c ledger Entry For Sales Return
        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::SalesReturn->value, account_id: $addSalesReturn->customer_account_id, date: $addSalesReturn->date, trans_id: $addSalesReturn->id, amount: $addSalesReturn->total_return_amount, amount_type: 'credit');

        //         $dbSaleReturnProducts =  DB::connection('bondhon')->table('sale_return_products')
        //             ->leftJoin('products', 'sale_return_products.product_id', 'products.id')
        //             ->where('sale_return_products.sale_return_id', $dbSaleReturn->id)
        //             ->select('sale_return_products.*', 'products.name as product_name', 'products.product_code')
        //             ->get();

        //         foreach ($dbSaleReturnProducts as $dbSaleReturnProduct) {

        //             $product = DB::table('products')
        //                 ->where('name', $dbSaleReturnProduct->product_name)
        //                 ->where('product_code', $dbSaleReturnProduct->product_code)
        //                 ->select('products.id', 'products.unit_id')
        //                 ->first();

        //             $saleProduct = DB::table('sale_products')->where('product_id', $product->id)->where('sale_id', $parentSaleId)->first();

        //             $addSaleReturnProduct = new \App\Models\Sales\SaleReturnProduct();
        //             $addSaleReturnProduct->sale_return_id = $addSalesReturn->id;
        //             $addSaleReturnProduct->sale_product_id = $saleProduct?->id;
        //             $addSaleReturnProduct->product_id = $product?->id;
        //             $addSaleReturnProduct->return_qty = $dbSaleReturnProduct->return_qty;
        //             $addSaleReturnProduct->sold_quantity = $dbSaleReturnProduct->sold_quantity;
        //             $addSaleReturnProduct->unit_id = $product->unit_id;
        //             $addSaleReturnProduct->unit_price_exc_tax = $dbSaleReturnProduct->unit_price_exc_tax;
        //             $addSaleReturnProduct->unit_discount = $dbSaleReturnProduct->unit_discount;
        //             $addSaleReturnProduct->unit_discount_type = $dbSaleReturnProduct->unit_discount_type;
        //             $addSaleReturnProduct->unit_discount_amount = $dbSaleReturnProduct->unit_discount_amount;
        //             $addSaleReturnProduct->unit_price_inc_tax = $dbSaleReturnProduct->unit_price_inc_tax;
        //             $addSaleReturnProduct->unit_cost_inc_tax = $dbSaleReturnProduct->unit_cost_inc_tax;
        //             $addSaleReturnProduct->return_subtotal = $dbSaleReturnProduct->return_subtotal;
        //             $addSaleReturnProduct->save();

        //             // Add Product Ledger Entry
        //             $productLedgerService->addProductLedgerEntry(voucherTypeId: ProductLedgerVoucherType::SalesReturn->value, date: $addSalesReturn->date, productId: $addSaleReturnProduct->product_id, transId: $addSaleReturnProduct->id, rate: $addSaleReturnProduct->unit_price_inc_tax, quantityType: 'in', quantity: $addSaleReturnProduct->return_qty, subtotal: $addSaleReturnProduct->return_subtotal, variantId: null, warehouseId: null);

        //             if ($addSaleReturnProduct->return_qty > 0) {

        //                 $purchaseProductService->addOrUpdatePurchaseProductForSalePurchaseChainMaintaining(transColName: 'sale_return_product_id', transId: $addSaleReturnProduct->id, branchId: auth()->user()->branch_id, productId: $addSaleReturnProduct->product_id, variantId: null, quantity: $addSaleReturnProduct->return_qty, unitCostIncTax: $addSaleReturnProduct->unit_cost_inc_tax, sellingPrice: $addSaleReturnProduct->unit_price_inc_tax, subTotal: $addSaleReturnProduct->return_subtotal, createdAt: $addSalesReturn->date_ts);
        //             }
        //         }

        //         if ($saleReturnPaid > 0) {

        //             $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $addSalesReturn->date, voucherType: AccountingVoucherType::Payment->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $paymentVoucherPrefix, debitTotal: $saleReturnPaid, creditTotal: $saleReturnPaid, totalAmount: $saleReturnPaid, saleReturnRefId: $addSalesReturn->id);

        //             // Add Debit Account Accounting voucher Description
        //             $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $addSalesReturn->customer_account_id, paymentMethodId: null, amountType: 'dr', amount: $saleReturnPaid);

        //             // Add Accounting VoucherDescription References
        //             $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherDebitDescription->id, accountId: $addSalesReturn->customer_account_id, amount: $saleReturnPaid, refIdColName: 'sale_return_id', refIds: [$addSalesReturn->id]);

        //             //Add Debit Ledger Entry
        //             $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $addSalesReturn->date, account_id: $addSalesReturn->customer_account_id, trans_id: $addAccountingVoucherDebitDescription->id, amount: $saleReturnPaid, amount_type: 'debit', cash_bank_account_id: 14);

        //             // Add Payment Description Credit Entry
        //             $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: 14, paymentMethodId: 1, amountType: 'cr', amount: $saleReturnPaid, note: null);

        //             //Add Credit Ledger Entry
        //             $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Payment->value, date: $addSalesReturn->date, account_id: 14, trans_id: $addAccountingVoucherCreditDescription->id, amount: $saleReturnPaid, amount_type: 'credit');
        //         }

        //         $return = $salesReturnService->singleSalesReturn(id: $addSalesReturn->id, with: [
        //             'sale',
        //             'branch',
        //             'branch.parentBranch',
        //             'customer',
        //             'saleReturnProducts',
        //             'saleReturnProducts.product',
        //             'saleReturnProducts.variant',
        //             'saleReturnProducts.unit',
        //         ]);

        //         if ($return?->sale) {

        //             $saleService->adjustSaleInvoiceAmounts($return->sale);
        //         }

        //         echo 'Sale Return Created-' . $voucherNo . '</br>';
        //     }
        // }

        // echo 'All Sale Returns is done-' . '</br>';

        // $purchaseProducts = \App\Models\Purchases\PurchaseProduct::all();

        // foreach ($purchaseProducts as $purchaseProduct) {
        //     $purchaseProduct->left_qty = $purchaseProduct->quantity;
        //     $purchaseProduct->save();
        // }

        // $stockChains = \App\Models\Products\StockChain::all();
        // foreach ($stockChains as $key => $stockChain) {
        //     $stockChain->delete();
        // }

        // $sales = \App\Models\Sales\Sale::with('saleProducts', 'saleProducts.product')->where('status', 1)->get();
        // foreach ($sales as $sale) {

        //     $stockChainService->addStockChain(sale: $sale);
        //     echo 'stock Chain -' . $sale->invoice_id . '</br>';
        // }
        // echo 'All stock Chain is Done-' . '</br>';

        // $products = DB::table('products')->get();
        // foreach ($products as $product) {

        //     $productStockService->adjustMainProductAndVariantStock(productId: $product->id, variantId: null);

        //     $productStockService->adjustBranchAllStock(productId: $product->id, variantId: null, branchId: auth()->user()->branch_id);

        //     $productStockService->adjustBranchStock(productId: $product->id, variantId: null, branchId: auth()->user()->branch_id);

        //     echo 'adjust stock -' . $product->id . '-' . $product->name . '</br>';
        // }
        // echo 'adjust stock is done -' . '</br>';

        // ////Add Expenses
        // $directExpenseGroup = DB::table('account_groups')->where('sub_group_number', 10)->first();
        // $dbExpenses = DB::connection('bondhon')->table('expanses')->get();
        // foreach ($dbExpenses as $dbExpense) {

        //     $existsExpense = DB::table('accounting_vouchers')
        //         ->where('voucher_type', AccountingVoucherType::Expense->value)
        //         ->where('date', $dbExpense->date)
        //         ->where('total_amount', $dbExpense->net_total_amount)
        //         ->first();

        //     if (!isset($existsExpense)) {
        //         // Add Accounting Voucher
        //         $expenseVoucherPrefix = $generalSettings['prefix__expense_voucher_prefix'] ? $generalSettings['prefix__expense_voucher_prefix'] : 'EV';

        //         $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $dbExpense->date, voucherType: AccountingVoucherType::Expense->value, remarks: null, reference: null, codeGenerator: $codeGenerator, voucherPrefix: $expenseVoucherPrefix, debitTotal: $dbExpense->net_total_amount, creditTotal: $dbExpense->net_total_amount, totalAmount: $dbExpense->net_total_amount);

        //         $dbExpenseDescriptions = $dbExpenses = DB::connection('bondhon')->table('expense_descriptions')->where('expense_id', $dbExpense->id)
        //             ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
        //             ->select('expense_descriptions.amount', 'expanse_categories.name as expense_category_name')
        //             ->get();

        //         foreach ($dbExpenseDescriptions as $index => $dbExpenseDescription) {

        //             $existsExpenseAccount = DB::table('accounts')->where('accounts.name', $dbExpenseDescription->expense_category_name)->where('accounts.branch_id', auth()->user()->branch_id)->first();
        //             // 25
        //             $expenseAccountId = isset($existsExpenseAccount) ? $existsExpenseAccount->id : null;
        //             if (!isset($existsExpenseAccount)) {

        //                 $addAccount = $accountService->addAccount(
        //                     name: $dbExpenseDescription->expense_category_name,
        //                     accountGroup: $directExpenseGroup,
        //                 );

        //                 $expenseAccountId = $addAccount->id;
        //             }

        //             // Add Expense Description Debit Entry
        //             $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $expenseAccountId, paymentMethodId: null, amountType: 'dr', amount: $dbExpenseDescription->amount);

        //             if ($index == 0) {

        //                 $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Expense->value, date: $addAccountingVoucher->date, accountId: $expenseAccountId, transId: $addAccountingVoucherDebitDescription->id, amount: $addAccountingVoucher->total_amount, amountType: 'debit');
        //             }

        //             //Add Debit Ledger Entry
        //             $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $addAccountingVoucher->date, account_id: $expenseAccountId, trans_id: $addAccountingVoucherDebitDescription->id, amount: $dbExpenseDescription->amount, amount_type: 'debit', cash_bank_account_id: 14);
        //         }

        //         // Add Credit Account Accounting voucher Description
        //         $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: 14, paymentMethodId: 1, amountType: 'cr', amount: $addAccountingVoucher->total_amount, transactionNo: null, chequeNo: null, chequeSerialNo: null);

        //         //Add Credit Ledger Entry
        //         $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $addAccountingVoucher->date, account_id: 14, trans_id: $addAccountingVoucherCreditDescription->id, amount: $addAccountingVoucher->total_amount, amount_type: 'credit');

        //         echo 'Expense Created-' . $expenseVoucherPrefix . '</br>';
        //     }
        // }
        // echo 'All Expense is done Created' . '</br>';


        DB::commit();
    } catch (\Exception $e) {

        DB::rollBack();
        dd($e->getMessage());
    }

    // $dir = __DIR__ . '/../resources/views';
    // $translationKeys = [];

    // function checkDir($dir, &$translationKeys)
    // {
    //     if (is_dir($dir)) {
    //         $files = scandir($dir);

    //         foreach ($files as $file) {
    //             if ($file != "." && $file != "..") {
    //                 $path = "$dir/$file";

    //                 if (is_dir($path)) {
    //                     checkDir($path, $translationKeys);
    //                 } else {
    //                     // Only process .blade.php files
    //                     if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
    //                         $content = file_get_contents($path);

    //                         // Use regular expression to match all {{ __('...') }} patterns
    //                         preg_match_all('/__\([\'"](.+?)[\'"]\)/', $content, $matches);

    //                         foreach ($matches[1] as $key) {
    //                             if (!in_array($key, $translationKeys)) {
    //                                 // Set the translation key
    //                                 $translationKeys[] = $key;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    // // Start the directory scan
    // checkDir($dir, $translationKeys);
    // return $translationKeys;

    // $translatedArray = [];

    // // return count($translationKeys) . '-' . count($translatedArray);

    // $c = array_combine($translationKeys, $translatedArray);

    // return $c;
});

Route::get('password', function () {

    return Hash::make(12345);
});

Route::get('t-id', function () {});
