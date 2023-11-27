<?php

use Modules\Factor\app\Http\Controllers\Admin\FactorController;
use Modules\Factor\app\Http\Controllers\Admin\MakeViewController;
use Modules\Factor\app\Http\Controllers\Admin\TransactionCategoryController;

Route::group(['guard' => 'admin'], function () {

    /* CATEGORY ROUTES */
    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('/', [TransactionCategoryController::class, 'index'])->name('index');
        Route::get('/data', [TransactionCategoryController::class, 'data'])->name('data');
        Route::get('/create', [TransactionCategoryController::class, 'create'])->name('create');
        Route::get('/{transaction_category}', [TransactionCategoryController::class, 'edit'])->name('edit');
        Route::post('/', [TransactionCategoryController::class, 'store'])->name('store');
        Route::patch('/{transaction_category}', [TransactionCategoryController::class, 'update'])->name('update');
        Route::delete('/{transaction_category}', [TransactionCategoryController::class, 'destroy'])->name('destroy');
    });
    /* CATEGORY ROUTES */

    Route::get('/', [FactorController::class, 'index'])->name('index');
    Route::get('/data', [FactorController::class, 'data'])->name('data');
    Route::get('/create', [FactorController::class, 'create'])->name('create');
    Route::post('/', [FactorController::class, 'store'])->name('store');

    Route::group([], function () {
        Route::get('/{factor}', [FactorController::class, 'edit'])->name('edit');
        Route::get('/{factor}/show', [FactorController::class, 'show'])->name('show');

        Route::put('/{factor}', [FactorController::class, 'update'])->name('update');
        Route::delete('/{factor}', [FactorController::class, 'destroy'])->name('destroy');
    })->whereNumber('factor');

    Route::get('/view/item', [MakeViewController::class, 'getItem'])->name('item.view');

});
