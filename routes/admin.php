<?php

use App\Http\Controllers\Admin\AutoMessageController;
use App\Http\Controllers\Admin\GroupGoalController;
use App\Http\Controllers\Admin\Report\GoalGroupController as GoalGroupControllerReport;
use App\Http\Controllers\Admin\Report\LoginController;
use Illuminate\Support\Facades\Route;
use Modules\Admin\app\Http\Controllers\Admin\GoalReportController as GoalControllerReport;
use Modules\Dashboard\app\Http\Controllers\Admin\HomeController;

/**
 * TODO
 * Update Route Name
 * Update Controls And Homogenization
 */
Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {
    /* this function for help to route ui dashboard */
    Route::get('/'.config('routes.admin-prefix'), [HomeController::class, 'redirect']);

    Route::controller(GroupGoalController::class)->group(function () {
        Route::get('/group-goal', 'index')->name('admin.group-goal');
        Route::post('/group-goal', 'save')->name('admin.group-goal.save');
    });

    Route::controller(GoalControllerReport::class)->group(function () {
        Route::get('/report/goal', 'index')->name('report.goal');
    });

    Route::controller(GoalGroupControllerReport::class)->group(function () {
        Route::get('/report/goal-group', 'index')->name('report.goal-group');
    });

    Route::controller(LoginController::class)->group(function () {
        Route::get('/report/login', 'index')->name('report.login');
        Route::get('/report/{login}/login', 'show')->name('report.login-show')
            ->whereUlid('login');
    });

    Route::controller(AutoMessageController::class)->group(function () {
        Route::get('/auto-message', 'index')->name('auto-message.index');
        Route::get('/auto-message/data', 'data')->name('auto-message.data');
        Route::get('/auto-message/{sampleMessage}/edit', 'edit')->name('auto-message.edit');
        Route::patch('/auto-message/{sampleMessage}/update', 'update')->name('auto-message.update');
    });
});
