<?php

use Illuminate\Support\Facades\Route;
use Modules\Personnel\app\Http\Controllers\Admin\PayslipController;

Route::group(['guard' => 'admin'], function () {
    Route::group(['prefix' => 'payslip', 'as' => 'payslip.'], function () {
    Route::get('/', [PayslipController::class, 'index'])->name('index');
    Route::get('/show/{payslip}', [PayslipController::class, 'show'])->name('show');
    Route::get('/approved/{payslip}', [PayslipController::class, 'approved'])->name('approved');
    Route::post('/canceled/{payslip}', [PayslipController::class, 'canceled'])->name('canceled');

    });
});
