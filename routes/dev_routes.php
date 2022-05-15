<?php

use Illuminate\Support\Facades\Route;

Route::get('route-list', function () {
    if (env('APP_DEBUG') === true) {
        Artisan::call('route:list --columns=Method,URI,Name,Action');
        return '<pre>' . Artisan::output() . '</pre>';
    } else {
        echo '<h1>Access Denied</h1>';
        return null;
    }
});

Route::get('add-user', function () {
    $addAdmin = new AdminAndUser();
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
    return $saleProducts = DB::table('sale_products')
        ->where('sale_products.sale_id', 31)
        ->leftJoin('products', 'sale_products.product_id', 'products.id')
        ->leftJoin('warranties', 'products.warranty_id', 'warranties.id')
        ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
        ->select(
            'sale_products.description',
            // 'sale_products.quantity',
            'sale_products.unit_price_inc_tax',
            'sale_products.unit_discount_amount',
            'sale_products.unit_tax_percent',
            'sale_products.subtotal',
            'products.name',
            'products.warranty_id',
            'product_variants.variant_name',
            'warranties.duration',
            'warranties.duration_type',
            'warranties.description as w_description',
            'warranties.type',
            DB::raw('SUM(sale_products.quantity) as quantity')
        )
        ->groupBy('sale_products.description')
        // ->groupBy('sale_products.quantity')
        ->groupBy('sale_products.unit_price_inc_tax')
        ->groupBy('sale_products.unit_discount_amount')
        ->groupBy('sale_products.unit_tax_percent')
        ->groupBy('sale_products.subtotal')
        ->groupBy('products.warranty_id')
        ->groupBy('products.name')
        ->groupBy('warranties.duration')
        ->groupBy('warranties.duration_type')
        ->groupBy('warranties.type')
        ->groupBy('warranties.description')
        ->groupBy('product_variants.variant_name')
        ->get();
});

// Route::get('dbal', function() {
//     dd(\Doctrine\DBAL\Types\Type::getTypesMap());
// });
