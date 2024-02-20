<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\RoleController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\UserProfileController;

Route::controller(UserController::class)->prefix('users')->group(function () {

    Route::get('/', 'index')->name('users.index');
    Route::get('create', 'create')->name('users.create');
    Route::post('store', 'store')->name('users.store');
    Route::get('edit/{id}', 'edit')->name('users.edit');
    Route::post('update/{id}', 'update')->name('users.update');
    Route::delete('delete/{id}', 'delete')->name('users.delete');
    Route::get('show/{id}', 'show')->name('users.show');
    Route::post('change/branch', 'changeBranch')->name('users.change.branch');

    Route::controller(RoleController::class)->prefix('roles')->group(function () {

        Route::get('/', 'index')->name('users.role.index');
        Route::get('create', 'create')->name('users.role.create');
        Route::post('store', 'store')->name('users.role.store');
        Route::get('edit/{id}', 'edit')->name('users.role.edit');
        Route::post('update/{id}', 'update')->name('users.role.update');
        Route::delete('delete/{id}', 'delete')->name('users.role.delete');
    });

    Route::controller(UserProfileController::class)->prefix('profile')->group(function () {

        Route::get('/', 'index')->name('users.profile.index');
        Route::post('update', 'update')->name('users.profile.update');
        Route::get('view/{id}', 'view')->name('users.profile.view');
    });
});
