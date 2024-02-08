<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\app\Http\Controllers\Api\AttachmentController;
use Modules\Chat\app\Http\Controllers\Api\ChatController;
use Modules\Chat\app\Http\Controllers\Api\MessageController;
use Modules\Chat\app\Http\Middleware\Api\UserChatAccess;

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

Route::group(['middleware' => ['auth:sanctum', UserChatAccess::class]], function () {
    Route::get('/{chatId}/message', [MessageController::class, 'index']);
    Route::post('/{chatId}/message', [MessageController::class, 'store']);
    Route::patch('/{chatId}/message', [MessageController::class, 'update']);
    Route::delete('/{chatId}/message', [MessageController::class, 'delete']);
})->whereNumber('chatId');

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'attachment'], function () {
    Route::post('/', [AttachmentController::class, 'upload']);
    Route::delete('/', [AttachmentController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/', [ChatController::class, 'index']);
});
