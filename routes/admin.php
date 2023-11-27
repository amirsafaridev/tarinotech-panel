<?php

use App\Http\Controllers\Admin\AutoMessageController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\GroupGoalController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Report\GoalGroupController as GoalGroupControllerReport;
use App\Http\Controllers\Admin\Report\LoginController;
use App\Http\Controllers\Admin\SampleMessageController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;
use Modules\Admin\app\Http\Controllers\Admin\GoalReportController as GoalControllerReport;

/**
 * TODO
 * Update Route Name
 * Update Controls And Homogenization
 */
Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {
    /* this function for help to route ui dashboard */
    Route::get('/', [HomeController::class, 'redirect'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

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

    Route::controller(SettingController::class)->group(function () {
        Route::get('/setting', 'index')->name('setting.index');
        Route::patch('/setting', 'update')->name('setting.update');
    });

    Route::controller(FacilityController::class)->group(function () {
        Route::get('/facility', 'index')->name('facility.index');
        Route::get('/facility/data', 'data')->name('facility.data');
        Route::get('/facility/create', 'create')->name('facility.create');
        Route::post('/facility/store', 'store')->name('facility.store');
        Route::get('/facility/{facility}/edit', 'edit')->name('facility.edit');
        Route::patch('/facility/{facility}/update', 'update')->name('facility.update');
        Route::delete('/facility/{facility}/destroy', 'destroy')->name('facility.destroy');
    });

    Route::controller(SampleMessageController::class)->group(function () {
        Route::get('/sample-message', 'index')->name('sample-message.index');
        Route::get('/sample-message/data', 'data')->name('sample-message.data');
        Route::get('/sample-message/create', 'create')->name('sample-message.create');
        Route::post('/sample-message/store', 'store')->name('sample-message.store');
        Route::get('/sample-message/{sampleMessage}/edit', 'edit')->name('sample-message.edit');
        Route::patch('/sample-message/{sampleMessage}/update', 'update')->name('sample-message.update');
        Route::delete('/sample-message/{sampleMessage}/destroy', 'destroy')->name('sample-message.destroy');
    });

    Route::controller(AutoMessageController::class)->group(function () {
        Route::get('/auto-message', 'index')->name('auto-message.index');
        Route::get('/auto-message/data', 'data')->name('auto-message.data');
        Route::get('/auto-message/{sampleMessage}/edit', 'edit')->name('auto-message.edit');
        Route::patch('/auto-message/{sampleMessage}/update', 'update')->name('auto-message.update');
    });
});
