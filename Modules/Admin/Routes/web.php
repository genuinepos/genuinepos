<?php

Route::prefix('admin/demo')->group(function () {
    Route::view('login', 'admin::demo.auth.login');
    Route::view('register', 'admin::demo.auth.register');
    Route::view('dashboard', 'admin::demo.dashboard.index');
});
