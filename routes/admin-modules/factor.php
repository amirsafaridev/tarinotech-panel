<?php

use App\Http\Controllers\Admin\FactorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {

    Route::controller(FactorController::class)->group(function () {
        Route::get('/factor', 'index')->name('factor.index');
        Route::get('/factor/create', 'create')->name('factor.create');
        Route::post('/factor/store', 'store')->name('factor.store');
        Route::get('/factor/{factor}/edit', 'edit')->name('factor.edit');
        Route::get('/factor/{factor}/show', 'show')->name('factor.show');
        Route::put('/factor/{factor}/update', 'update')->name('factor.update');
        Route::delete('/factor/{factor}/destroy', 'destroy')->name('factor.destroy');
    });
});
