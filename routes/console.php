<?php

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Jobs\SaleMailJob;
use App\Mail\PurchaseCreated;
use App\Mail\NewProductArrived;
use App\Mail\CustomerRegistered;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schema;
use App\Services\CodeGenerationService;
use Illuminate\Support\Facades\Artisan;
use Modules\Communication\Interface\EmailServiceInterface;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Artisan::command('hello', function(EmailServiceInterface  $emailService) {
//     $customer = new Customer();
//     $customer->name = '...';
//     .....
//     $customer->save();

//     $customer = Customer::first();
//     if(isset($customer)) {
//         $emailService->send($customer->email, new CustomerRegistered($customer));
//     }
// });
// Artisan::command('product', function(EmailServiceInterface  $emailService) {
//     $customers = Customer::pluck('email', 'id')->toArray();
//     $product = Product::latest()->first();
//     $emailService->sendMultiple(array_values($customers), new NewProductArrived($customers, $product));

// });
Artisan::command('purchaseCreated', function (EmailServiceInterface  $emailService) {
    // $supplier = Supplier::findOrFail($request->supplier_id);
    $supplier = Supplier::find(3);
    // $purchase = Purchase::where('id', $addPurchase->id)->first();
    $purchase = Purchase::find(1);
    $emailService->send($supplier->email, new PurchaseCreated($purchase));
});


Artisan::command('a', function (CodeGenerationService $s) {

    $res1 = $s->generateAndTypeWiseWithoutYearMonth('contacts', 'contact_id', 'type', '1', 'C');
    $res2 = $s->generateAndTypeWiseWithoutYearMonth('contacts', 'contact_id', 'type', '2', 'S');
    dd($res1, $res2, PHP_INT_MAX);
});

// Just merged this line of text.
