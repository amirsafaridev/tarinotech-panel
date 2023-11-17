<?php

use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\Admin\UserController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/data', [UserController::class, 'data'])->name('data');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::get('/{user}', [UserController::class, 'edit'])->name('edit');
    Route::get('/{user}/show', [UserController::class, 'show'])->name('show');

    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::patch('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});
