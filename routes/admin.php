<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminGoalController;
use App\Http\Controllers\Admin\AdminPasswordController;
use App\Http\Controllers\Admin\AutoMessageController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\FreeDayController;
use App\Http\Controllers\Admin\GroupGoalController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Report\GoalController as GoalControllerReport;
use App\Http\Controllers\Admin\Report\GoalGroupController as GoalGroupControllerReport;
use App\Http\Controllers\Admin\Report\LoginController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SampleMessageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TransactionCategoryController;
use Illuminate\Support\Facades\Route;

/**
 * TODO
 * Update Route Name
 * Update Controls And Homogenization
 */
Route::group(['namespace' => 'App\Http\Controllers\Admin'], function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Reset Password
    Route::get('password/forget', 'Auth\ForgotPasswordController@index')->name('password.forget');
    Route::post('password/sendOtpCode', 'Auth\ForgotPasswordController@sendOtpCode')->name('password.email');

    Route::get('password/reset', 'Auth\ResetPasswordController@index')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
});

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {
    /* this function for help to route ui dashboard */
    Route::get('/', [HomeController::class, 'redirect'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/permission/sync', [PermissionController::class, 'sync'])->name('permission.sync');

    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin', 'index')->name('admin.index');
        Route::get('/admin/data', 'data')->name('admin.data');
        Route::get('/admin/create', 'create')->name('admin.create');
        Route::post('/admin/store', 'store')->name('admin.store');
        Route::get('/admin/{admin}/show', 'show')->name('admin.show');
        Route::get('/admin/{admin}/edit', 'edit')->name('admin.edit');
        Route::put('/admin/{admin}/update', 'update')->name('admin.update');
        Route::delete('/admin/{admin}/destroy', 'destroy')->name('admin.destroy');
    });

    Route::controller(AdminPasswordController::class)->group(function () {
        Route::get('/admin/{admin}/password', 'index')->name('admin.password');
        Route::patch('/admin/{admin}/password', 'update')->name('admin.password.update');
    });

    Route::controller(AdminGoalController::class)->group(function () {
        Route::get('/admin/{admin}/goal', 'index')->name('admin.goal');
        Route::post('/admin/{admin}/goal', 'save')->name('admin.goal.save');
    });

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

    Route::controller(RoleController::class)->group(function () {
        Route::get('/role', 'index')->name('role.index');
        Route::get('/role/data', 'data')->name('role.data');
        Route::get('/role/create', 'create')->name('role.create');
        Route::post('/role/store', 'store')->name('role.store');
        Route::get('/role/edit/{role}', 'edit')->name('role.edit');
        Route::patch('/role/update/{role}', 'update')->name('role.update');
        Route::delete('/role/destroy/{role}', 'destroy')->name('role.destroy');
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

    Route::controller(TransactionCategoryController::class)->group(function () {
        Route::get('/transaction-category', 'index')->name('transaction-category.index');
        Route::get('/transaction-category/data', 'data')->name('transaction-category.data');
        Route::get('/transaction-category/create', 'create')->name('transaction-category.create');
        Route::post('/transaction-category/store', 'store')->name('transaction-category.store');
        Route::get('/transaction-category/{transactionCategory}/edit', 'edit')->name('transaction-category.edit');
        Route::patch('/transaction-category/{transactionCategory}/update', 'update')->name('transaction-category.update');
        Route::delete('/transaction-category/{transactionCategory}/destroy', 'destroy')->name('transaction-category.destroy');
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

    Route::controller(FreeDayController::class)->group(function () {
        Route::get('/free-day', 'index')->name('free-day.index');
        Route::get('/free-day/data', 'data')->name('free-day.data');
        Route::get('/free-day/create', 'create')->name('free-day.create');
        Route::post('/free-day/store', 'store')->name('free-day.store');
        Route::get('/free-day/{freeDay}/edit', 'edit')->name('free-day.edit');
        Route::patch('/free-day/{freeDay}/update', 'update')->name('free-day.update');
        Route::delete('/free-day/{freeDay}/destroy', 'destroy')->name('free-day.destroy');
    });

    Route::controller(AutoMessageController::class)->group(function () {
        Route::get('/auto-message', 'index')->name('auto-message.index');
        Route::get('/auto-message/data', 'data')->name('auto-message.data');
        Route::get('/auto-message/{sampleMessage}/edit', 'edit')->name('auto-message.edit');
        Route::patch('/auto-message/{sampleMessage}/update', 'update')->name('auto-message.update');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::get('/profile/logout', 'logout')->name('profile.logout');
        Route::get('/profile/password', 'password')->name('profile.password');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::patch('/profile/password', 'updatePassword')->name('profile.password.update');
    });
});
