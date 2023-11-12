<?php

use Modules\Log\app\Http\Controllers\Admin\LogController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [LogController::class, 'index'])->name('index');
    Route::get('/{activity}/show', [LogController::class, 'show'])->name('show');
});
