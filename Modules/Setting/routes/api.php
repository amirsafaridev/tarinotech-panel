<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\app\Http\Controllers\Api\SettingController;

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

Route::get('/', [SettingController::class, 'index']);
Route::get('/enums', [SettingController::class, 'enums']);
