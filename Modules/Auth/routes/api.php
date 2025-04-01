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

// Apply rate limiting to prevent brute force attacks
// Allow 5 attempts per minute for authentication endpoints
Route::middleware(['throttle:5,1'])->group(function () {
    // Authentication routes
    Route::post('/login', [LoginController::class, 'index']);

    Route::post('/verify', [VerifyController::class, 'index']);

    Route::post('/resend', [ResendOtpController::class, 'index']);
});

// Development routes - should be disabled in production
if (config('app.env') !== 'production') {
    Route::post('/dev-login', [LoginController::class, 'devLogin'])
        ->middleware(['ip.restrict:127.0.0.1,192.168.0.1']);
} else {
    // Redirect any attempts to access dev routes in production
    Route::fallback(function () {
        return response()->json(['error' => 'Not Found'], 404);
    });
}
