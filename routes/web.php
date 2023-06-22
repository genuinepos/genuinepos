<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => 'Welcome page' . asset('favicon.png'));
// Auth::routes();

