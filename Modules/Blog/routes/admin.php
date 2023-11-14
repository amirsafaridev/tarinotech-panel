<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\app\Http\Controllers\Admin\BlogController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/data', [BlogController::class, 'data'])->name('data');
    Route::get('/create', [BlogController::class, 'create'])->name('create');
    Route::get('/{blog}', [BlogController::class, 'edit'])->name('edit')
        ->whereNumber('blog');
    Route::post('/', [BlogController::class, 'store'])->name('store');
    Route::patch('/{blog}', [BlogController::class, 'update'])->name('update');
    Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');
});
