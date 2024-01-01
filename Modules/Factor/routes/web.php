<?php

use Modules\Factor\app\Http\Controllers\Web\PaymentController;

Route::group(['as' => 'payment.'], function () {
    Route::get('/{identify}', [PaymentController::class, 'index'])
        ->name('index')
        ->whereAlphaNumeric('identify');
    Route::get('/data', [PaymentController::class, 'callback'])->name('callback');
});
