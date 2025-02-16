<?php

use Illuminate\Support\Facades\Route;
use Modules\Package\app\Http\Controllers\Admin\PackageController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [PackageController::class, 'index'])->name('index');
    Route::get('/data', [PackageController::class, 'data'])->name('data');
    Route::get('/create', [PackageController::class, 'create'])->name('create');
    Route::get('/{package}', [PackageController::class, 'edit'])->name('edit');
    Route::post('/', [PackageController::class, 'store'])->name('store');
    Route::patch('/{package}', [PackageController::class, 'update'])->name('update');
    Route::delete('/{package}', [PackageController::class, 'destroy'])->name('destroy');
});
