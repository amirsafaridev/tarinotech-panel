<?php

use App\Http\Controllers\Admin\Blog\BlogCategoryController;
use App\Http\Controllers\Admin\Blog\BlogController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {

    Route::controller(BlogCategoryController::class)->group(function () {
        Route::get('/blog/category', 'index')->name('blog.category.index');
        Route::get('/blog/category/data', 'data')->name('blog.category.data');
        Route::get('/blog/category/create', 'create')->name('blog.category.create');
        Route::post('/blog/category/store', 'store')->name('blog.category.store');
        Route::get('/blog/category/{blogCategory}/edit', 'edit')->name('blog.category.edit');
        Route::patch('/blog/category/{blogCategory}/update', 'update')->name('blog.category.update');
        Route::delete('/blog/category/{blogCategory}/destroy', 'destroy')->name('blog.category.destroy');
    });

    Route::controller(BlogController::class)->group(function () {
        Route::get('/blog', 'index')->name('blog.index');
        Route::get('/blog/data', 'data')->name('blog.data');
        Route::get('/blog/create', 'create')->name('blog.create');
        Route::post('/blog/store', 'store')->name('blog.store');
        Route::get('/blog/{blog}/edit', 'edit')->name('blog.edit');
        Route::patch('/blog/{blog}/update', 'update')->name('blog.update');
        Route::delete('/blog/{blog}/destroy', 'destroy')->name('blog.destroy');
    });

});
