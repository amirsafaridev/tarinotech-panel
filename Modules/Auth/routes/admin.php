<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\app\Http\Controllers\Admin\ForgotPasswordController;
use Modules\Auth\app\Http\Controllers\Admin\LoginController;
use Modules\Auth\app\Http\Controllers\Admin\ResetPasswordController;
use Modules\Auth\App\Http\Controllers\Admin\VerifyController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

// Login routes
Route::get('/', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit')
    ->middleware('block.and.throttle');

// Verification routes
Route::group(['middleware' => 'check.otp.session'], function () {
    Route::get('/verify', [VerifyController::class, 'index'])
        ->name('verify');

    Route::post('/verify', [VerifyController::class, 'verify'])
        ->name('verify.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Reset Password
Route::group(['prefix' => '/password', 'as' => 'password.'], function () {
    Route::get('/forget', [ForgotPasswordController::class, 'index'])->name('forget');
    Route::post('/sendOtpCode', [ForgotPasswordController::class, 'sendOtpCode'])->name('email');
    Route::get('/reset', [ResetPasswordController::class, 'index'])->name('reset');
    Route::post('/reset', [ResetPasswordController::class, 'reset'])->name('update');
});
