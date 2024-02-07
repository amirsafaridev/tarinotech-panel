<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\app\Http\Controllers\Api\BlogController;
use Modules\Blog\app\Http\Controllers\Api\CategoryController;

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

Route::group(['prefix' => 'category', 'as' => 'category'], function () {
    Route::get('/', [CategoryController::class, 'index']);
});

Route::get('/', [BlogController::class, 'index']);
Route::get('/{slug}', [BlogController::class, 'single']);
