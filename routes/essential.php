<?php

use App\Http\Controllers\Essentials\MemoController;
use App\Http\Controllers\Essentials\MessageController;
use App\Http\Controllers\Essentials\TodoController;
use App\Http\Controllers\Essentials\WorkSpaceController;
use App\Http\Controllers\Essentials\WorkSpaceTaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'essentials'], function () {

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

    Route::group(['prefix' => 'todo'], function () {
        Route::get('/', [TodoController::class, 'index'])->name('todo.index');
        Route::get('show/{id}', [TodoController::class, 'show'])->name('todo.show');
        Route::post('store', [TodoController::class, 'store'])->name('todo.store');
        Route::get('assign/user/{id}', [TodoController::class, 'assignUser'])->name('todo.assign.user');
        Route::get('change/status/modal/{id}', [TodoController::class, 'changeStatusModal'])->name('todo.status.modal');
        Route::post('change/status/{id}', [TodoController::class, 'changeStatus'])->name('todo.status');
        Route::get('change/priority/{id}', [TodoController::class, 'changePriority'])->name('todo.priority');
        Route::get('edit/{id}', [TodoController::class, 'edit'])->name('todo.edit');
        Route::post('update/{id}', [TodoController::class, 'update'])->name('todo.update');
        Route::delete('delete/{id}', [TodoController::class, 'delete'])->name('todo.delete');
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
