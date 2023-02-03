<?php


Route::get('change/lang/{lang}', [DashboardController::class, 'changeLang'])->name('change.lang');

Route::get('maintenance/mode', function () {

    return view('maintenance/maintenance');
})->name('maintenance.mode');

Route::get('add-user', function () {

    $addAdmin = new User();
    $addAdmin->prefix = 'Mr.';
    $addAdmin->name = 'Super';
    $addAdmin->last_name = 'Admin';
    $addAdmin->email = 'superadmin@gmail.com';
    $addAdmin->username = 'superadmin';
    $addAdmin->password = Hash::make('12345');
    $addAdmin->role_type = 3;
    $addAdmin->role_permission_id = 1;
    $addAdmin->allow_login = 1;
    $addAdmin->save();
    //1=super_admin;2=admin;3=Other;

});

Route::get('/test', function () {

    //return str_pad(10, 10, "0", STR_PAD_LEFT);
    // $purchases = Purchase::all();
    // foreach ($purchases as $p) {
    //     $p->is_last_created = 0;
    //     $p->save();
    // }

    // $customers = DB::table('customers')->get();

    // foreach ($customers as $customer){

    //     $customerOpeningBalance = new CustomerOpeningBalance();
    //     $customerOpeningBalance->customer_id = $customer->id;
    //     $customerOpeningBalance->amount = $customer->opening_balance;
    //     $customerOpeningBalance->created_by_id = auth()->user()->id;
    //     $customerOpeningBalance->save();

    //     $customerCreditLimit = new CustomerCreditLimit();
    //     $customerCreditLimit->customer_id = $customer->id;
    //     $customerCreditLimit->credit_limit = $customer->credit_limit ? $customer->credit_limit : 0;
    //     $customerCreditLimit->created_by_id = auth()->user()->id;
    //     $customerCreditLimit->save();
    // }

    // $suppliers = DB::table('suppliers')->get();

    // foreach ($suppliers as $supplier){

    //     $supplierOpeningBalance = new SupplierOpeningBalance();
    //     $supplierOpeningBalance->supplier_id = $supplier->id;
    //     $supplierOpeningBalance->amount = $supplier->opening_balance;
    //     $supplierOpeningBalance->created_by_id = auth()->user()->id;
    //     $supplierOpeningBalance->save();
    // }

    return $supplierPayments = DB::table('supplier_payments')
        ->leftJoin('supplier_payment_invoices', 'supplier_payments.id', 'supplier_payment_invoices.supplier_payment_id')
        ->select(
            'supplier_payments.id',
            'supplier_payments.payment_method_id',
            'supplier_payments.account_id',
            'supplier_payments.date',
            'supplier_payments.voucher_no',
            'supplier_payments.paid_amount',
            // DB::raw('SUM(supplier_payment_invoices.paid_amount) as total_invoice_paid_amount'),
            DB::raw('SUM(- IFNULL(supplier_payment_invoices.paid_amount, 0)) + supplier_payments.paid_amount as left_amount')
        )
        ->having('left_amount', '!=', 0)
        ->groupBy('supplier_payments.id')
        ->groupBy('supplier_payments.voucher_no')
        ->groupBy('supplier_payment_invoices.supplier_payment_id')
        ->get();
});

// Route::get('dbal', function() {
//     dd(\Doctrine\DBAL\Types\Type::getTypesMap());
// });


// enum VoucherType : int
// {
//     case Disabled = 'disabled';
//     case Enabled = 1;
//     case Pending = 2;
//     case Rejected = 3;
// }

// enum Gender : string
// {
//     case MALE = 0;
//     case FEMALE = 1;
//     case OTHER = 2;
// }

// class App
// {
//     public function logicalMethod()
//     {
//         $voucherType = VoucherType::Disabled;
//         $gender = Gender::MALE;
//     }
// }
