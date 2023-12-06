<?php

use App\Http\Controllers\Admin\PackagePriceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {

    Route::controller(PackagePriceController::class)->group(function () {
        Route::get('/package/{package}/price/{packagePrice}/edit', 'edit')->name('package-price.edit');
        Route::patch('/package/{package}/price/{packagePrice}/update', 'update')->name('package-price.update');
        Route::delete('/package/{package}/price/{packagePrice}/destroy', 'destroy')->name('package-price.destroy');
    });

});
