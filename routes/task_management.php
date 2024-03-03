<?php

use App\Http\Controllers\Essentials\MemoController;
use App\Http\Controllers\Essentials\MessageController;
use App\Http\Controllers\TaskManagement\TodoController;
use App\Http\Controllers\TaskManagement\WorkSpaceController;
use App\Http\Controllers\TaskManagement\WorkSpaceTaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'task-management'], function () {

    Route::group(['prefix' => 'workspaces'], function () {
        Route::get('/', [WorkSpaceController::class, 'index'])->name('workspace.index');
        Route::get('view/docs/{id}', [WorkSpaceController::class, 'viewDocs'])->name('workspace.view.docs');
        Route::get('show/{id}', [WorkSpaceController::class, 'show'])->name('workspace.show');
        Route::post('store', [WorkSpaceController::class, 'store'])->name('workspace.store');
        Route::get('edit/{id}', [WorkSpaceController::class, 'edit'])->name('workspace.edit');
        Route::post('update/{id}', [WorkSpaceController::class, 'update'])->name('workspace.update');
        Route::delete('delete/{id}', [WorkSpaceController::class, 'delete'])->name('workspace.delete');
        Route::delete('delete/doc/{docId}', [WorkSpaceController::class, 'deleteDoc'])->name('workspace.delete.doc');

        Route::group(['prefix' => 'tasks'], function () {
            Route::get('{workspaceId}', [WorkSpaceTaskController::class, 'index'])->name('workspace.task.index');
            Route::post('store', [WorkSpaceTaskController::class, 'store'])->name('workspace.task.store');
            Route::get('list/{workspaceId}', [WorkSpaceTaskController::class, 'taskList'])->name('workspace.task.list');
            Route::get('assign/user/{id}', [WorkSpaceTaskController::class, 'assignUser'])->name('workspace.task.assign.user');
            Route::get('change/status/{id}', [WorkSpaceTaskController::class, 'changeStatus'])->name('workspace.task.status');
            Route::get('change/priority/{id}', [WorkSpaceTaskController::class, 'changePriority'])->name('workspace.task.priority');
            Route::post('update', [WorkSpaceTaskController::class, 'update']);
            Route::delete('delete/{id}', [WorkSpaceTaskController::class, 'delete'])->name('workspace.task.delete');
        });
    });

    Route::controller(TodoController::class)->prefix('todo')->group(function () {

        Route::get('/', 'index')->name('todo.index');
        Route::get('show/{id}', 'show')->name('todo.show');
        Route::post('store', 'store')->name('todo.store');
        Route::get('create', 'create')->name('todo.create');
        Route::get('assign/user/{id}', 'assignUser')->name('todo.assign.user');
        Route::get('change/status/{id}', 'changeStatusModal')->name('todo.change.status');
        Route::post('change/status/{id}', 'changeStatus')->name('todo.change.status');
        Route::get('change/priority/{id}', 'changePriority')->name('todo.priority');
        Route::get('edit/{id}', 'edit')->name('todo.edit');
        Route::post('update/{id}', 'update')->name('todo.update');
        Route::delete('delete/{id}', 'delete')->name('todo.delete');
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

    Route::group(['prefix' => 'memos'], function () {
        Route::get('/', [MemoController::class, 'index'])->name('memos.index');
        Route::get('show/{id}', [MemoController::class, 'show'])->name('memos.show');
        Route::post('store', [MemoController::class, 'store'])->name('memos.store');
        Route::get('edit/{id}', [MemoController::class, 'edit'])->name('memos.edit');
        Route::post('update', [MemoController::class, 'update'])->name('memos.update');
        Route::delete('delete/{id}', [MemoController::class, 'delete'])->name('memos.delete');
        Route::get('add/user/view/{id}', [MemoController::class, 'addUserView'])->name('memos.add.user.view');
        Route::post('add/user/{id}', [MemoController::class, 'addUsers'])->name('memos.add.users');
    });

    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');
        Route::get('all', [MessageController::class, 'allMessage'])->name('messages.all');
        Route::post('store', [MessageController::class, 'store'])->name('messages.store');
        Route::delete('delete/{id}', [MessageController::class, 'delete'])->name('messages.delete');
    });
});
