<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\app\Http\Controllers\Admin\PermissionController;
use Modules\Role\app\Http\Controllers\Admin\RoleController;

Route::group(['guard' => 'admin'], function () {

    Route::group(['as' => 'role.', 'prefix' => 'role'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/data', [RoleController::class, 'data'])->name('data');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::get('/{role}', [RoleController::class, 'edit'])->name('edit')->whereNumber('role');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::patch('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });

    Route::group(['as' => 'permission.', 'prefix' => 'permission'], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/sync', [PermissionController::class, 'sync'])->name('sync');
        Route::get('/data', [PermissionController::class, 'data'])->name('data');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::get('/{permission}', [PermissionController::class, 'edit'])->name('edit')->whereNumber('permission');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::patch('/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
    });

});
