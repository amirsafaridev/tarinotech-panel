<?php

use Modules\Login\app\Http\Controllers\Admin\LoginController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [LoginController::class, 'index'])->name('index');
});
