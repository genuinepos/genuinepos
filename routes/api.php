<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| REST API Route Files Registration
|--------------------------------------------------------------------------
*/

Route::middleware([InitializeTenancyByDomainOrSubdomain::class, PreventAccessFromCentralDomains::class])->group(function () {
    Route::get('me', function () {
        // TODO:
    });
});
