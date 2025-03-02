<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\app\Http\Controllers\Api\LoginController;
use Modules\Auth\app\Http\Controllers\Api\ResendOtpController;
use Modules\Auth\app\Http\Controllers\Api\VerifyController;

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

Route::post('/login', [LoginController::class, 'index']);
Route::post('/verify', [VerifyController::class, 'index']);
Route::post('/resend', [ResendOtpController::class, 'index']);

Route::post('/dev-login', [LoginController::class, 'devLogin']);
