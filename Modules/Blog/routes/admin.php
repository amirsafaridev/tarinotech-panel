<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\app\Http\Controllers\Admin\BlogController;
use Modules\Blog\app\Http\Controllers\Admin\CategoryController;

Route::group(['guard' => 'admin', 'as' => 'category.', 'prefix' => 'category'], function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/data', [CategoryController::class, 'data'])->name('data');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::get('/{blogCategory}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::patch('/{blogCategory}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{blogCategory}', [CategoryController::class, 'destroy'])->name('destroy');
});

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/data', [BlogController::class, 'data'])->name('data');
    Route::get('/create', [BlogController::class, 'create'])->name('create');
    Route::get('/{chat}', [BlogController::class, 'edit'])->name('edit')
        ->whereNumber('chat');
    Route::post('/', [BlogController::class, 'store'])->name('store');
    Route::patch('/{chat}', [BlogController::class, 'update'])->name('update');
    Route::delete('/{chat}', [BlogController::class, 'destroy'])->name('destroy');
});
