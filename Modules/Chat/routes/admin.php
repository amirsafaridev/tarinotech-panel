<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\app\Http\Controllers\Admin\AttachmentController;
use Modules\Chat\app\Http\Controllers\Admin\AttachmentStreamController;
use Modules\Chat\app\Http\Controllers\Admin\MessageController;
use Modules\Chat\app\Http\Middleware\ChatAccess;

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

Route::group(['guard' => 'admin'], function () {
    Route::group(['as' => 'message.', 'prefix' => 'message'], function () {
        Route::post('/', [MessageController::class, 'index'])
            ->middleware([ChatAccess::class])
            ->name('index');

        Route::get('/{message}', [MessageController::class, 'edit'])->name('edit');

        Route::post('/store', [MessageController::class, 'store'])
            ->middleware([ChatAccess::class])
            ->name('store');

        Route::patch('/{message}', [MessageController::class, 'update'])->name('update');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
    });
});

Route::group(['guard' => 'admin'], function () {
    Route::group(['as' => 'attachment.', 'prefix' => 'attachment'], function () {
        Route::post('/', [AttachmentController::class, 'upload'])
            ->middleware([ChatAccess::class])
            ->name('upload');
    });
});

Route::group(['guard' => 'admin'], function () {
    Route::group(['as' => 'attachment.steam.', 'prefix' => 'attachment/steam'], function () {
        Route::get('/{path}', [AttachmentStreamController::class, 'read'])
            ->name('read');
    });
});
