<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/communication', function (Request $request) {
    return $request->user();
});