<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| REST API Route Files Registration
|--------------------------------------------------------------------------
*/

Route::middleware(['api', InitializeTenancyByDomainOrSubdomain::class, PreventAccessFromCentralDomains::class])->prefix('api')->group(function () {
    Route::get('me', function () {
        return tenant()->id;
    });
});
