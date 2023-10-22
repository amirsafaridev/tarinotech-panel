<?php

use App\Http\Controllers\Admin\PresenterController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {

    Route::controller(PresenterController::class)->group(function () {
        Route::get('/presenter', 'index')->name('presenter.index');
        Route::get('/presenter/data', 'data')->name('presenter.data');
        Route::get('/presenter/create', 'create')->name('presenter.create');
        Route::post('/presenter/store', 'store')->name('presenter.store');
        Route::get('/presenter/{user}/edit', 'edit')->name('presenter.edit');
        Route::get('/presenter/{user}/show', 'show')->name('presenter.show');
        Route::patch('/presenter/{user}/update', 'update')->name('presenter.update');
        Route::delete('/presenter/{user}/destroy', 'destroy')->name('presenter.destroy');
    })->middleware('ensure.presenter');

});
