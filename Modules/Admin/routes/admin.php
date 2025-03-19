<?php

use Modules\Admin\app\Http\Controllers\Admin\AdminController;
use Modules\Admin\app\Http\Controllers\Admin\BonusController;
use Modules\Admin\app\Http\Controllers\Admin\DeductionController;
use Modules\Admin\app\Http\Controllers\Admin\GoalController;
use Modules\Admin\app\Http\Controllers\Admin\Google2FAController;
use Modules\Admin\app\Http\Controllers\Admin\JobTitleController;
use Modules\Admin\app\Http\Controllers\Admin\PasswordController;
use Modules\Admin\app\Http\Controllers\Admin\PersonnelAssistanceController;
use Modules\Admin\app\Http\Controllers\Admin\PersonnelSalaryController;
use Modules\Admin\app\Http\Controllers\Admin\PersonnelReportController;
use Modules\Admin\app\Http\Controllers\Admin\VariableAmountController;
use Modules\Admin\app\Http\Controllers\Admin\FixedAmountController;

use Modules\Admin\app\Http\Controllers\Admin\ProfileController;
use Modules\Admin\app\Http\Controllers\Admin\ReasonController;
use Modules\Admin\app\Http\Controllers\Admin\DailyActivityController;
use Modules\Admin\app\Http\Controllers\Admin\MonthlyActivityController;
use Illuminate\Support\Facades\Route;

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

    Route::group(['prefix' => 'personnel-assistance', 'as' => 'personnel-assistance.'], function () {
        Route::get('/', [PersonnelAssistanceController::class, 'index'])->name('index');
        Route::get('/create', [PersonnelAssistanceController::class, 'create'])->name('create');
        Route::get('/{personnelAssistance}', [PersonnelAssistanceController::class, 'edit'])->name('edit');
        Route::post('/', [PersonnelAssistanceController::class, 'store'])->name('store');
        Route::patch('/{personnelAssistance}', [PersonnelAssistanceController::class, 'update'])->name('update');
        Route::delete('/{personnelAssistance}', [PersonnelAssistanceController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'personnel-salary', 'as' => 'personnel-salary.'], function () {
        Route::get('/', [PersonnelSalaryController::class, 'index'])->name('index');
        Route::get('/create', [PersonnelSalaryController::class, 'create'])->name('create');
        Route::get('/{personnelSalary}', [PersonnelSalaryController::class, 'edit'])->name('edit');
        Route::post('/', [PersonnelSalaryController::class, 'store'])->name('store');
        Route::patch('/{personnelSalary}', [PersonnelSalaryController::class, 'update'])->name('update');
        Route::delete('/{personnelSalary}', [PersonnelSalaryController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'personnel-report', 'as' => 'personnel-report.'], function () {
        Route::get('/', [PersonnelReportController::class, 'index'])->name('index');
        Route::get('/approve/{personnelReport}', [PersonnelReportController::class, 'approve'])->name('approve');
        Route::post('/reject/{personnelReport}', [PersonnelReportController::class, 'reject'])->name('reject');
      
    });
    Route::group(['prefix' => 'variable-amount', 'as' => 'variable-amount.'], function () {
        Route::get('/', [VariableAmountController::class, 'index'])->name('index');
        Route::get('/create', [VariableAmountController::class, 'create'])->name('create');
        Route::get('/{variableAmount}', [VariableAmountController::class, 'edit'])->name('edit');
        Route::post('/', [VariableAmountController::class, 'store'])->name('store');
        Route::patch('/{variableAmount}', [VariableAmountController::class, 'update'])->name('update');
        Route::delete('/{variableAmount}', [VariableAmountController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'fixed-amount', 'as' => 'fixed-amount.'], function () {
        Route::get('/', [FixedAmountController::class, 'index'])->name('index');
        Route::get('/create', [FixedAmountController::class, 'create'])->name('create');
        Route::get('/{fixedAmount}', [FixedAmountController::class, 'edit'])->name('edit');
        Route::post('/', [FixedAmountController::class, 'store'])->name('store');
        Route::patch('/{fixedAmount}', [FixedAmountController::class, 'update'])->name('update');
        Route::delete('/{fixedAmount}', [FixedAmountController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'bonuses', 'as' => 'bonuses.'], function () {
        Route::get('/', [BonusController::class, 'index'])->name('index');
        Route::get('/create', [BonusController::class, 'create'])->name('create');
        Route::get('/{bonusesDeduction}', [BonusController::class, 'edit'])->name('edit');
        Route::post('/', [BonusController::class, 'store'])->name('store');
        Route::patch('/{bonusesDeduction}', [BonusController::class, 'update'])->name('update');
        Route::delete('/{bonusesDeduction}', [BonusController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'deductions', 'as' => 'deductions.'], function () {
        Route::get('/', [DeductionController::class, 'index'])->name('index');
        Route::get('/create', [DeductionController::class, 'create'])->name('create');
        Route::get('/{bonusesDeduction}', [DeductionController::class, 'edit'])->name('edit');
        Route::post('/', [DeductionController::class, 'store'])->name('store');
        Route::patch('/{bonusesDeduction}', [DeductionController::class, 'update'])->name('update');
        Route::delete('/{bonusesDeduction}', [DeductionController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'reason', 'as' => 'reason.'], function () {
        Route::get('/', [ReasonController::class, 'index'])->name('index');
        Route::get('/create', [ReasonController::class, 'create'])->name('create');
        Route::get('/{reason}', [ReasonController::class, 'edit'])->name('edit');
        Route::post('/', [ReasonController::class, 'store'])->name('store');
        Route::patch('/{reason}', [ReasonController::class, 'update'])->name('update');
        Route::delete('/{reason}', [ReasonController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('daily-activity')->name('daily-activity.')->group(function () {
        Route::get('/', [DailyActivityController::class, 'index'])->name('index');
        
        Route::get('/approve/{activity}', [DailyActivityController::class, 'approveEdit'])->name('approve');
        Route::post('/reject/{activity}', [DailyActivityController::class, 'rejectEdit'])->name('reject');
    });
    Route::prefix('monthly-activity')->name('monthly-activity.')->group(function () {
        Route::get('/', [MonthlyActivityController::class, 'index'])->name('index');
        
        Route::get('/daily-details/{userId}/{startOfMonth}/{endOfMonth}', [MonthlyActivityController::class, 'dailyDetails'])->name('daily-details');

        
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
