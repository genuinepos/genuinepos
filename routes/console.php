<?php

use App\Mail\NewProductArrived;
use App\Mail\CustomerRegistered;

use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

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

Artisan::command('col-migrate', function () {

    Schema::table('sale_products', function (Blueprint $table) {
        $table->unsignedBigInteger('product_unit_id')->after('unit_id')->nullable();
        $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('SET NULL');
    });
});

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

Artisan::command('test', function () {

    $key = "";
    $keyLength = 8;
    for ($x = 1; $x <= $keyLength; $x++) {
        // Set each digit
        $key .= random_int(0, 9);
    }
    echo $key;
});

// Just merged this line of text.
