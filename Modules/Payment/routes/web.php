<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\app\Http\Controllers\Web\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::post('/verify/sepehr', [PaymentController::class, 'verifySepehr'])
        ->name('verify-sepehr');

    Route::get('/test', [PaymentController::class, 'test']);

    Route::get('/{identify}', [PaymentController::class, 'pay'])
        ->name('pay')
        ->whereAlphaNumeric('identify');
});
