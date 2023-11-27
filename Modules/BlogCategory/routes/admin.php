<?php

use Modules\BlogCategory\app\Http\Controllers\Admin\BlogCategoryController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [BlogCategoryController::class, 'index'])->name('index');
    Route::get('/data', [BlogCategoryController::class, 'data'])->name('data');
    Route::get('/create', [BlogCategoryController::class, 'create'])->name('create');
    Route::get('/{blogCategory}/edit', [BlogCategoryController::class, 'edit'])->name('edit');
    Route::post('/', [BlogCategoryController::class, 'store'])->name('store');
    Route::patch('/{blogCategory}', [BlogCategoryController::class, 'update'])->name('update');
    Route::delete('/{blogCategory}', [BlogCategoryController::class, 'destroy'])->name('destroy');
});
