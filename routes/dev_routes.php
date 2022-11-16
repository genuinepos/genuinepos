<?php

use App\Models\Purchase;
use App\Models\User;
use App\Mail\WelcomeUserMail;
use Doctrine\DBAL\Types\Type;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('rp', function() {
    $rp = RolePermission::find(32)->toArray();
    $values = array_values($rp);
    unset($values[0]);
    unset($values[1]);
    unset($values[22]);
    $values = array_values($values);
    $arr = \Arr::collapse($values);
    $arr = array_keys($arr);
    dd($arr);
    foreach($rp as $k => $v) {
        echo $k . '<br>' . $v;
    }
});

Route::get('test-mail', function() {
    $data = [
        'name' => 'Mr. Random',
        'message' => 'Your random message goes here',
    ];
    \Mail::to('random@gmai.com')->send(new WelcomeUserMail($data));
    return 'Sent at: ' . now();
});

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
   
    
});

// Route::get('dbal', function() {
//     dd(\Doctrine\DBAL\Types\Type::getTypesMap());
// });
