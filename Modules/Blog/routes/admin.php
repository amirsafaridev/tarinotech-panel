<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\app\Http\Controllers\Admin\BlogController;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {
    /*Route::controller(BlogCategoryController::class)->group(function () {
        Route::get('/blog/category', 'index')->name('blog.category.index');
        Route::get('/blog/category/data', 'data')->name('blog.category.data');
        Route::get('/blog/category/create', 'create')->name('blog.category.create');
        Route::post('/blog/category/store', 'store')->name('blog.category.store');
        Route::get('/blog/category/{blogCategory}/edit', 'edit')->name('blog.category.edit');
        Route::patch('/blog/category/{blogCategory}/update', 'update')->name('blog.category.update');
        Route::delete('/blog/category/{blogCategory}/destroy', 'destroy')->name('blog.category.destroy');
    });*/

    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/create', [BlogController::class, 'create'])->name('create');
    Route::get('/{blog}', [BlogController::class, 'edit'])->name('edit')
        ->whereNumber('blog');
    Route::post('/', [BlogController::class, 'store'])->name('store');
    Route::patch('/{blog}', [BlogController::class, 'update'])->name('update');
    Route::delete('/{blog}', [BlogController::class, 'destroy'])->name('destroy');

});
