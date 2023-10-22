<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index')->name('user.index');
        Route::get('/user/data', 'data')->name('user.data');
        Route::get('/user/create', 'create')->name('user.create');
        Route::post('/user/store', 'store')->name('user.store');
        Route::get('/user/{user}/edit', 'edit')->name('user.edit');
        Route::get('/user/{user}/show', 'show')->name('user.show');
        Route::patch('/user/{user}/update', 'update')->name('user.update');
        Route::delete('/user/{user}/destroy', 'destroy')->name('user.destroy');
    });

});
