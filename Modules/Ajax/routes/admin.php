<?php

use Illuminate\Support\Facades\Route;
use Modules\Ajax\app\Http\Controllers\Admin\AdminController;
use Modules\Ajax\app\Http\Controllers\Admin\CalendarController;
use Modules\Ajax\app\Http\Controllers\Admin\ProjectController;
use Modules\Ajax\app\Http\Controllers\Admin\UserController;

Route::group(['guard' => 'admin'], function () {

    Route::post('/calendar/calc', [CalendarController::class, 'calcFreeDays'])->name('calendar.calc');

    Route::post('/project/view-item', [ProjectController::class, 'singleViewItem'])->name('project.single');
    Route::get('/project/remote-select', [ProjectController::class, 'remoteSelect'])->name('project.remote-select');

    Route::get('/user/remote-select', [UserController::class, 'remoteSelect'])->name('user.remote-select');
    Route::get('/admin/remote-select', [AdminController::class, 'remoteSelect'])->name('admin.remote-select');

});
