<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Content\app\Http\Controllers\Api\BlogController;
use Modules\Content\app\Http\Controllers\Api\CategoryController;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('content', fn (Request $request) => $request->user())->name('content');
});

Route::group(['prefix' => 'category', 'as' => 'category'], function () {
    Route::get('/', [CategoryController::class, 'index']);
});

Route::group(['guard' => 'admin', 'as' => 'blog.', 'prefix' => 'blog'], function () {
});
Route::get('/', [BlogController::class, 'index']);
Route::get('/{slug}', [BlogController::class, 'single']);
