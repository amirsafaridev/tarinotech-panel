<?php

use Illuminate\Support\Facades\Route;
use Modules\Stream\app\Http\Controllers\StreamController;
use Modules\Stream\app\Http\Middleware\StreamMiddleware;

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

Route::get('/{path}', [StreamController::class, 'index'])->name('read')
    ->middleware(['web', StreamMiddleware::class]);
