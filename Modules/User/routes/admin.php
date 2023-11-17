<?php

use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\Admin\PresenterController;
use Modules\User\app\Http\Controllers\Admin\UserController;

Route::group(['guard' => 'admin', 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/data', [UserController::class, 'data'])->name('data');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::get('/{user}', [UserController::class, 'edit'])->name('edit');
    Route::get('/{user}/show', [UserController::class, 'show'])->name('show');

    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::patch('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

});

Route::group(['guard' => 'admin', 'prefix' => 'presenter', 'as' => 'presenter.'], function () {
    Route::get('/', [PresenterController::class, 'index'])->name('index');
    Route::get('/data', [PresenterController::class, 'data'])->name('data');
    Route::get('/create', [PresenterController::class, 'create'])->name('create');
    Route::get('/{user}', [PresenterController::class, 'edit'])->name('edit');
    Route::get('/{user}/show', [PresenterController::class, 'show'])->name('show');

    Route::post('/', [PresenterController::class, 'store'])->name('store');
    Route::patch('/{user}', [PresenterController::class, 'update'])->name('update');
    Route::delete('/{user}', [PresenterController::class, 'destroy'])->name('destroy');
})->middleware('ensure.presenter');
