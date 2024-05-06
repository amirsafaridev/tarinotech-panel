<?php

use Illuminate\Support\Facades\Route;
use Modules\Content\app\Http\Controllers\Admin\BlogController;
use Modules\Content\app\Http\Controllers\Admin\CategoryController;
use Modules\Content\app\Http\Controllers\Admin\SliderController;

Route::group(['guard' => 'admin', 'as' => 'slider.', 'prefix' => 'slider'], function () {
    Route::get('/', [SliderController::class, 'index'])->name('index');
    Route::get('/data', [SliderController::class, 'data'])->name('data');
    Route::get('/create', [SliderController::class, 'create'])->name('create');
    Route::get('/{slider}/edit', [SliderController::class, 'edit'])->name('edit');
    Route::post('/', [SliderController::class, 'store'])->name('store');
    Route::patch('/{slider}', [SliderController::class, 'update'])->name('update');
    Route::delete('/{slider}', [SliderController::class, 'destroy'])->name('destroy');
});

Route::group(['guard' => 'admin', 'as' => 'blog.category.', 'prefix' => 'blog/category'], function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/data', [CategoryController::class, 'data'])->name('data');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::get('/{blogCategory}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::patch('/{blogCategory}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{blogCategory}', [CategoryController::class, 'destroy'])->name('destroy');
});

Route::group(['guard' => 'admin', 'as' => 'blog.', 'prefix' => 'blog'], function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/data', [BlogController::class, 'data'])->name('data');
    Route::get('/create', [BlogController::class, 'create'])->name('create');
    Route::get('/{blog}', [BlogController::class, 'edit'])->name('edit')
        ->whereNumber('blog');
    Route::post('/', [BlogController::class, 'store'])->name('store');
    Route::patch('/{blog}', [BlogController::class, 'update'])->name('update');
    Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');
});
