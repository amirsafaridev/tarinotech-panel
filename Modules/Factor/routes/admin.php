<?php

use Modules\Factor\app\Http\Controllers\Admin\FactorController;
use Modules\Factor\app\Http\Controllers\Admin\MakeViewController;

Route::group(['guard' => 'admin'], function () {

    Route::group(['guard' => 'admin'], function () {
        Route::get('/', [FactorController::class, 'index'])->name('index');
        Route::get('/create', [FactorController::class, 'create'])->name('create');
        Route::post('/', [FactorController::class, 'store'])->name('store');

        Route::group([], function () {
            Route::get('/{factor}', [FactorController::class, 'edit'])->name('edit');
            Route::get('/{factor}/show', [FactorController::class, 'show'])->name('show');

            Route::put('/{factor}', [FactorController::class, 'update'])->name('update');
            Route::delete('/{factor}', [FactorController::class, 'destroy'])->name('destroy');
        })->whereNumber('factor');
    });

    Route::get('/view/item', [MakeViewController::class, 'getItem'])->name('item.view');
    Route::post('/view/installment', [MakeViewController::class, 'getInstallment'])->name('installment.vue');

});
