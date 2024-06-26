<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskManagement\MemoController;
use App\Http\Controllers\TaskManagement\TodoController;
use App\Http\Controllers\TaskManagement\MessageController;
use App\Http\Controllers\TaskManagement\WorkSpaceController;
use App\Http\Controllers\TaskManagement\WorkSpaceTaskController;
use App\Http\Controllers\TaskManagement\WorkSpaceAttachmentController;

Route::group(['prefix' => 'task-management'], function () {

    Route::controller(TodoController::class)->prefix('todo')->group(function () {

        Route::get('/', 'index')->name('todo.index');
        Route::get('show/{id}', 'show')->name('todo.show');
        Route::post('store', 'store')->name('todo.store');
        Route::get('create', 'create')->name('todo.create');
        Route::get('assign/user/{id}', 'assignUser')->name('todo.assign.user');
        Route::get('change/status/modal/{id}', 'changeStatusModal')->name('todo.change.status.modal');
        Route::post('change/status/{id}', 'changeStatus')->name('todo.change.status');
        Route::get('change/priority/{id}', 'changePriority')->name('todo.priority');
        Route::get('edit/{id}', 'edit')->name('todo.edit');
        Route::post('update/{id}', 'update')->name('todo.update');
        Route::delete('delete/{id}', 'delete')->name('todo.delete');
    });

    Route::controller(WorkSpaceController::class)->prefix('workspaces')->group(function () {

        Route::get('/', 'index')->name('workspaces.index');
        Route::get('create', 'create')->name('workspaces.create');
        Route::get('show/{id}', 'show')->name('workspaces.show');
        Route::post('store', 'store')->name('workspaces.store');
        Route::get('edit/{id}', 'edit')->name('workspaces.edit');
        Route::post('update/{id}', 'update')->name('workspaces.update');
        Route::delete('delete/{id}', 'delete')->name('workspaces.delete');

        Route::controller(WorkSpaceAttachmentController::class)->prefix('attachments')->group(function () {

            Route::get('index/{workspaceId}', 'index')->name('workspaces.attachments.index');
            Route::delete('delete/{id}', 'delete')->name('workspaces.attachments.delete');
        });

        Route::controller(WorkSpaceTaskController::class)->prefix('tasks')->group(function () {

            Route::get('index/{workspaceId}', 'index')->name('workspaces.task.index');
            Route::post('store', 'store')->name('workspaces.task.store');
            Route::get('list/{workspaceId}', 'taskList')->name('workspaces.task.list');
            Route::get('assign/user/{id}', 'assignUser')->name('workspaces.task.assign.user');
            Route::get('change/status/{id}', 'changeStatus')->name('workspaces.task.status');
            Route::get('change/priority/{id}', 'changePriority')->name('workspaces.task.priority');
            Route::post('update', 'update')->name('workspaces.task.update');
            Route::delete('delete/{id}', 'delete')->name('workspaces.task.delete');
        });
    });

    // Route::group(['prefix' => 'documents'], function()
    // {
    //     Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
    //     Route::get('show/{id}', [DocumentController::class, 'show'])->name('documents.show');
    //     Route::post('store', [DocumentController::class, 'store'])->name('documents.store');
    //     Route::get('edit/{id}', [DocumentController::class, 'edit'])->name('documents.edit');
    //     Route::post('update/{id}', [DocumentController::class, 'update'])->name('documents.update');
    //     Route::delete('delete/{id}', [DocumentController::class, 'delete'])->name('documents.delete');
    // });

    Route::controller(MessageController::class)->prefix('messages')->group(function () {

        Route::get('/', 'index')->name('messages.index');
        Route::get('all', 'allMessage')->name('messages.all');
        Route::post('store', 'store')->name('messages.store');
        Route::delete('delete/{id}', 'delete')->name('messages.delete');
    });
});
