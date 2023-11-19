<?php

use Illuminate\Support\Facades\Route;
use Modules\Login\app\Http\Controllers\Admin\ForgotPasswordController;
use Modules\Login\app\Http\Controllers\Admin\LoginController;
use Modules\Login\app\Http\Controllers\Admin\ResetPasswordController;

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

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Reset Password
Route::get('password/forget', [ForgotPasswordController::class, 'index'])->name('password.forget');
Route::post('password/sendOtpCode', [ForgotPasswordController::class, 'sendOtpCode'])->name('password.email');

Route::get('password/reset', [ResetPasswordController::class, 'index'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
