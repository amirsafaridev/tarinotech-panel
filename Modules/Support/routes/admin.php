<?php

use Illuminate\Support\Facades\Route;
use Modules\Support\app\Http\Controllers\Admin\GroupController;
use Modules\Support\app\Http\Controllers\Admin\NotifyController;
use Modules\Support\app\Http\Controllers\Admin\SampleMessageController;
use Modules\Support\app\Http\Controllers\Admin\SupportController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [SupportController::class, 'index'])->name('index');

    Route::group(['as' => 'group.', 'prefix' => 'group'], function () {
        Route::get('/', [GroupController::class, 'index'])->name('index');
        Route::get('/create', [GroupController::class, 'create'])->name('create');
        Route::get('/{chat}', [GroupController::class, 'edit'])->name('edit');
        Route::post('/', [GroupController::class, 'store'])->name('store');
        Route::patch('/{chat}', [GroupController::class, 'update'])->name('update');
        Route::delete('/{chat}', [GroupController::class, 'destroy'])->name('destroy');
    });

    Route::group(['guard' => 'admin', 'as' => 'notify.', 'prefix' => 'notify'], function () {
        Route::get('/', [NotifyController::class, 'index'])->name('index');
        Route::get('/{chat}', [NotifyController::class, 'edit'])->name('edit');
        Route::patch('/{chat}', [NotifyController::class, 'update'])->name('update');
    });

    Route::group(['as' => 'sample-message.', 'prefix' => 'sample-message'], function () {
        Route::get('/', [SampleMessageController::class, 'index'])->name('index');
        Route::get('/data', [SampleMessageController::class, 'data'])->name('data');
        Route::get('/messages', [SampleMessageController::class, 'message'])->name('message');
        Route::get('/create', [SampleMessageController::class, 'create'])->name('create');
        Route::post('/store', [SampleMessageController::class, 'store'])->name('store');
        Route::get('/{sampleMessage}/edit', [SampleMessageController::class, 'edit'])->name('edit');
        Route::patch('/{sampleMessage}/update', [SampleMessageController::class, 'update'])->name('update');
        Route::delete('/{sampleMessage}/destroy', [SampleMessageController::class, 'destroy'])->name('destroy');
    });
});
