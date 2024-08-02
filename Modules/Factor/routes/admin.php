<?php

use Modules\Factor\app\Http\Controllers\Admin\FactorController;
use Modules\Factor\app\Http\Controllers\Admin\FactorCustomerOfferController;
use Modules\Factor\app\Http\Controllers\Admin\FactorManualController;
use Modules\Factor\app\Http\Controllers\Admin\FactorStatusController;
use Modules\Factor\app\Http\Controllers\Admin\FactorUpdateController;
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

    /* STATUS ROUTES */
    Route::group(['prefix' => 'status', 'as' => 'status.'], function () {
        Route::get('/', [FactorStatusController::class, 'index'])->name('index');
        Route::get('/data', [FactorStatusController::class, 'data'])->name('data');
        Route::get('/create', [FactorStatusController::class, 'create'])->name('create');
        Route::get('/{factorStatusForward}', [FactorStatusController::class, 'edit'])->name('edit');
        Route::post('/', [FactorStatusController::class, 'store'])->name('store');
        Route::patch('/{factorStatusForward}', [FactorStatusController::class, 'update'])->name('update');
        Route::delete('/{factorStatusForward}', [FactorStatusController::class, 'destroy'])->name('destroy');
    });

    /* FACTOR CUSTOMER OFFER ROUTES */
    Route::group(['prefix' => 'customer-offer', 'as' => 'customer-offer.'], function () {
        Route::get('/', [FactorCustomerOfferController::class, 'index'])->name('index');
        Route::get('/data', [FactorCustomerOfferController::class, 'data'])->name('data');
        Route::get('/{factor}', [FactorCustomerOfferController::class, 'edit'])->name('edit');
        Route::patch('/{factor}', [FactorCustomerOfferController::class, 'update'])->name('update');
    });

    /* FACTOR MANUAL ROUTES */
    Route::group(['prefix' => 'manual', 'as' => 'manual.'], function () {
        Route::get('/', [FactorManualController::class, 'index'])->name('index');
        Route::get('/data', [FactorManualController::class, 'data'])->name('data');
        Route::get('/{factor}', [FactorManualController::class, 'edit'])->name('edit');
        Route::patch('/{factor}', [FactorManualController::class, 'update'])->name('update');
    });

    /* FACTOR ROUTES & REMOVE SELF PROJECT SCOPE */
    Route::group(['middleware' => 'admin.scope.remove.project.self'], function () {
        Route::get('/', [FactorController::class, 'index'])->name('index');
        Route::get('/data', [FactorController::class, 'data'])->name('data');
        Route::get('/create', [FactorController::class, 'create'])->name('create');
        Route::post('/', [FactorController::class, 'store'])->name('store');

        Route::group([], function () {
            Route::get('/{factor}', [FactorUpdateController::class, 'index'])->name('edit');
            Route::get('/{factor}/show', [FactorController::class, 'show'])->name('show');

            Route::put('/{factor}', [FactorUpdateController::class, 'update'])->name('update');
            Route::delete('/{factor}', [FactorController::class, 'destroy'])->name('destroy');
        })->whereNumber('factor');

        Route::get('/view/item', [MakeViewController::class, 'getItem'])->name('item.view');
    });

});
