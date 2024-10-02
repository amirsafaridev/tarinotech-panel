<?php

use Modules\Factor\app\Http\Controllers\Admin\ChequeController;
use Modules\Factor\app\Http\Controllers\Admin\CustomerOfferController;
use Modules\Factor\app\Http\Controllers\Admin\IndexController;
use Modules\Factor\app\Http\Controllers\Admin\MakeViewController;
use Modules\Factor\app\Http\Controllers\Admin\ManualController;
use Modules\Factor\app\Http\Controllers\Admin\StatusController;
use Modules\Factor\app\Http\Controllers\Admin\TransactionCategoryController;
use Modules\Factor\app\Http\Controllers\Admin\UpdateController;

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

    /* STATUS ROUTES */
    Route::group(['prefix' => 'status', 'as' => 'status.'], function () {
        Route::get('/', [StatusController::class, 'index'])->name('index');
        Route::get('/data', [StatusController::class, 'data'])->name('data');
        Route::get('/create', [StatusController::class, 'create'])->name('create');
        Route::get('/{factorStatusForward}', [StatusController::class, 'edit'])->name('edit');
        Route::post('/', [StatusController::class, 'store'])->name('store');
        Route::patch('/{factorStatusForward}', [StatusController::class, 'update'])->name('update');
        Route::delete('/{factorStatusForward}', [StatusController::class, 'destroy'])->name('destroy');
    });

    /* FACTOR CUSTOMER OFFER ROUTES */
    Route::group(['prefix' => 'customer-offer', 'as' => 'customer-offer.'], function () {
        Route::get('/', [CustomerOfferController::class, 'index'])->name('index');
        Route::get('/data', [CustomerOfferController::class, 'data'])->name('data');
        Route::get('/{factor}', [CustomerOfferController::class, 'edit'])->name('edit');
        Route::patch('/{factor}', [CustomerOfferController::class, 'update'])->name('update');
    });

    /* FACTOR MANUAL ROUTES */
    Route::group(['prefix' => 'manual', 'as' => 'manual.'], function () {
        Route::get('/', [ManualController::class, 'index'])->name('index');
        Route::get('/data', [ManualController::class, 'data'])->name('data');
        Route::get('/{factor}', [ManualController::class, 'edit'])->name('edit');
        Route::patch('/{factor}', [ManualController::class, 'update'])->name('update');
    });

    /* FACTOR CHEQUE ROUTES */
    Route::group(['prefix' => 'cheque', 'as' => 'cheque.'], function () {
        Route::get('/', [ChequeController::class, 'index'])->name('index');
        Route::get('/data', [ChequeController::class, 'data'])->name('data');
        Route::get('/{factor}', [ChequeController::class, 'edit'])->name('edit');
        Route::patch('/{factor}', [ChequeController::class, 'update'])->name('update');
    });

    /* FACTOR ROUTES */
    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/create', [IndexController::class, 'create'])->name('create');
    Route::post('/', [IndexController::class, 'store'])->name('store');

    Route::group([], function () {
        Route::get('/{factor}', [UpdateController::class, 'index'])->name('edit');
        Route::get('/{factor}/show', [IndexController::class, 'show'])->name('show');

        Route::put('/{factor}', [UpdateController::class, 'update'])->name('update');
        Route::delete('/{factor}', [IndexController::class, 'destroy'])->name('destroy');
    })->whereNumber('factor');

    Route::get('/view/item', [MakeViewController::class, 'getItem'])->name('item.view');

});
