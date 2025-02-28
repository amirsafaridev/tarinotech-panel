<?php

use Modules\Admin\app\Http\Controllers\Admin\AdminController;
use Modules\Admin\app\Http\Controllers\Admin\GoalController;
use Modules\Admin\app\Http\Controllers\Admin\Google2FAController;
use Modules\Admin\app\Http\Controllers\Admin\JobTitleController;
use Modules\Admin\app\Http\Controllers\Admin\PasswordController;
use Modules\Admin\app\Http\Controllers\Admin\PersonnelAssistanceController;
use Modules\Admin\app\Http\Controllers\Admin\PersonnelSalaryController;
use Modules\Admin\app\Http\Controllers\Admin\PersonnelReportController;

use Modules\Admin\app\Http\Controllers\Admin\ProfileController;

Route::group(['guard' => 'admin'], function () {

    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/data', [AdminController::class, 'data'])->name('data');

    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::post('/', [AdminController::class, 'store'])->name('store');

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');

        Route::get('/profile/enable2aGoogle', [Google2FAController::class, 'enable'])->name('enable2aGoogle');
        Route::get('/profile/disable2aGoogle', [Google2FAController::class, 'disable'])->name('disable2aGoogle');

        Route::get('/profile/logout', [ProfileController::class, 'logout'])->name('logout');

        Route::get('/profile/password', [ProfileController::class, 'password'])->name('password');
        Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    Route::group(['prefix' => 'job-title', 'as' => 'job-title.'], function () {
        Route::get('/', [JobTitleController::class, 'index'])->name('index');
        Route::get('/data', [JobTitleController::class, 'data'])->name('data');
        Route::get('/create', [JobTitleController::class, 'create'])->name('create');
        Route::get('/{job_title}', [JobTitleController::class, 'edit'])->name('edit');
        Route::post('/', [JobTitleController::class, 'store'])->name('store');
        Route::patch('/{job_title}', [JobTitleController::class, 'update'])->name('update');
        Route::delete('/{job_title}', [JobTitleController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'personnel-assistance' , 'as' => 'personnel-assistance.'], function () {
        Route::get('/', [PersonnelAssistanceController::class, 'index'])->name('index');
        Route::get('/create', [PersonnelAssistanceController::class, 'create'])->name('create');
        Route::get('/{personnelAssistance}', [PersonnelAssistanceController::class, 'edit'])->name('edit');
        Route::post('/', [PersonnelAssistanceController::class, 'store'])->name('store');
        Route::patch('/{personnelAssistance}', [PersonnelAssistanceController::class, 'update'])->name('update');
        Route::delete('/{personnelAssistance}', [PersonnelAssistanceController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'personnel-salary' , 'as' => 'personnel-salary.'], function () {
        Route::get('/', [PersonnelSalaryController::class, 'index'])->name('index');
        Route::get('/create', [PersonnelSalaryController::class, 'create'])->name('create');
        Route::get('/{personnelSalary}', [PersonnelSalaryController::class, 'edit'])->name('edit');
        Route::post('/', [PersonnelSalaryController::class, 'store'])->name('store');
        Route::patch('/{personnelSalary}', [PersonnelSalaryController::class, 'update'])->name('update');
        Route::delete('/{personnelSalary}', [PersonnelSalaryController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'personnel-report' , 'as' => 'personnel-report.'], function () {
        Route::get('/', [PersonnelReportController::class, 'index'])->name('index');
        Route::get('/create', [PersonnelReportController::class, 'create'])->name('create');
        Route::get('/{personnelReport}', [PersonnelReportController::class, 'edit'])->name('edit');
        Route::post('/', [PersonnelReportController::class, 'store'])->name('store');
        Route::patch('/{personnelReport}', [PersonnelReportController::class, 'update'])->name('update');
        Route::delete('/{personnelReport}', [PersonnelReportController::class, 'destroy'])->name('destroy');
    });
    Route::group([], function () {
        Route::get('/{admin}', [AdminController::class, 'edit'])->name('edit');
        Route::get('/{admin}/show', [AdminController::class, 'show'])->name('show');
        Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');

        Route::get('/{admin}/password', [PasswordController::class, 'index'])->name('password');
        Route::patch('/{admin}/password', [PasswordController::class, 'update'])->name('password.update');

        Route::get('/{admin}/goal', [GoalController::class, 'index'])->name('goal');
        Route::post('/{admin}/goal', [GoalController::class, 'save'])->name('goal.save');

    })->whereNumber('admin');
    
    

});