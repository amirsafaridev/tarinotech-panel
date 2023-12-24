<?php

use Illuminate\Support\Facades\Route;
use Modules\Support\app\Http\Controllers\Admin\GroupController;
use Modules\Support\app\Http\Controllers\Admin\SupportController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [SupportController::class, 'index'])->name('index');

    Route::group(['as' => 'group.', 'prefix' => 'group'], function () {
        Route::get('/create', [GroupController::class, 'create'])->name('create');
        Route::get('/{chat}', [GroupController::class, 'edit'])->name('edit');
        Route::post('/', [GroupController::class, 'store'])->name('store');
        Route::patch('/{chat}', [GroupController::class, 'update'])->name('update');
        Route::delete('/{chat}', [GroupController::class, 'destroy'])->name('destroy');
    });
});
