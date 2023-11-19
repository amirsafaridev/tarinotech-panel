<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\app\Http\Controllers\Admin\PermissionController;
use Modules\Role\app\Http\Controllers\Admin\RoleController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::get('/{role}', [RoleController::class, 'edit'])->name('edit')
        ->whereNumber('role');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::patch('/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');

    Route::get('/permission/sync', [PermissionController::class, 'sync'])->name('permission.sync');

});
