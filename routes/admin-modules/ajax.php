<?php

use App\Http\Controllers\Admin\Ajax\AdminController;
use App\Http\Controllers\Admin\Ajax\CalendarController;
use App\Http\Controllers\Admin\Ajax\ProjectController;
use App\Http\Controllers\Admin\Ajax\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin', 'prefix' => 'ajax', 'as' => 'ajax.'], function () {

    Route::controller(CalendarController::class)->group(function () {
        Route::post('/calendar/calc-day-work', 'calculateWorkDaysWithFreeDays')->name('calendar.calc.day.work');
    });

    Route::controller(ProjectController::class)->group(function () {
        Route::post('/project/view-item', 'singleViewItem')->name('project.single');
        Route::get('/project/remote-select', 'remoteSelect')->name('project.remote-select');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user/remote-select', 'remoteSelect')->name('user.remote-select');
    });

    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/remote-select', 'remoteSelect')->name('admin.remote-select');
    });
});
