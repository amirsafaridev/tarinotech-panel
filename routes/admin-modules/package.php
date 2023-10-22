<?php

use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PackagePriceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {

    Route::controller(PackagePriceController::class)->group(function () {
        Route::get('/package/{package}/price/{packagePrice}/edit', 'edit')->name('package-price.edit');
        Route::patch('/package/{package}/price/{packagePrice}/update', 'update')->name('package-price.update');
        Route::delete('/package/{package}/price/{packagePrice}/destroy', 'destroy')->name('package-price.destroy');
    });

    Route::controller(PackageController::class)->group(function () {
        Route::get('/package', 'index')->name('package.index');
        Route::get('/package/data', 'data')->name('package.data');
        Route::get('/package/create', 'create')->name('package.create');
        Route::post('/package/store', 'store')->name('package.store');
        Route::get('/package/{package}/edit', 'edit')->name('package.edit');
        Route::patch('/package/{package}/update', 'update')->name('package.update');
        Route::delete('/package/{package}/destroy', 'destroy')->name('package.destroy');
    });

});
