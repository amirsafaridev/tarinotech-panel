<?php

use Modules\Admin\app\Http\Controllers\Admin\AdminController;
use Modules\Admin\app\Http\Controllers\Admin\GoalController;
use Modules\Admin\app\Http\Controllers\Admin\PasswordController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/data', [AdminController::class, 'data'])->name('data');

    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::post('/', [AdminController::class, 'store'])->name('store');

    Route::group([], function () {
        Route::get('/{admin}', [AdminController::class, 'edit'])->name('edit');
        Route::get('/{admin}/show', [AdminController::class, 'show'])->name('show');
        Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');

        Route::get('/{admin}/password', [PasswordController::class, 'index'])->name('password');
        Route::patch('/{admin}/password', [PasswordController::class, 'update'])->name('password.update');

        Route::get('/{admin}/goal', [GoalController::class, 'index'])->name('goal');
        Route::post('/{admin}/goal', [GoalController::class, 'save'])->name('goal.save');

    })->whereNumber('admin');

});
