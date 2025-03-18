<?php

use Illuminate\Support\Facades\Route;
use Modules\Personnel\app\Http\Controllers\Admin\PayslipController;
use Modules\Personnel\app\Http\Controllers\Admin\PersonnelAssistanceController;
use Modules\Personnel\app\Http\Controllers\Admin\DailyActivityController;
use Modules\Personnel\app\Http\Controllers\Admin\PersonnelReportController;

Route::group(['guard' => 'admin'], function () {
    Route::group(['prefix' => 'payslip', 'as' => 'payslip.'], function () {
    Route::get('/', [PayslipController::class, 'index'])->name('index');
    Route::get('/show/{payslip}', [PayslipController::class, 'show'])->name('show');
    Route::get('/approved/{payslip}', [PayslipController::class, 'approved'])->name('approved');
    Route::post('/canceled/{payslip}', [PayslipController::class, 'canceled'])->name('canceled');

    });
    Route::group(['prefix' => 'personnel-assistance', 'as' => 'personnel-assistance.'], function () {
        Route::get('/', [PersonnelAssistanceController::class, 'index'])->name('index');
        Route::get('/{personnelAssistance}', [PersonnelAssistanceController::class, 'edit'])->name('edit');
        Route::patch('/{personnelAssistance}', [PersonnelAssistanceController::class, 'update'])->name('update');
        Route::delete('/{personnelAssistance}', [PersonnelAssistanceController::class, 'destroy'])->name('destroy');
        Route::get('/approved/{personnelAssistance}', [PersonnelAssistanceController::class, 'approved'])->name('approved');
        Route::get('/canceled/{personnelAssistance}', [PersonnelAssistanceController::class, 'canceled'])->name('canceled');

    });
        Route::prefix('daily-activity')->name('daily-activity.')->group(function () {
            Route::get('/', [DailyActivityController::class, 'index'])->name('index');
            Route::post('/toggle', [DailyActivityController::class, 'toggle'])->name('toggle');
            Route::post('/request-edit/{activity}', [DailyActivityController::class, 'requestEdit'])->name('request-edit');
        });
        Route::group(['prefix' => 'personnel-report', 'as' => 'personnel-report.'], function () {
            Route::get('/', [PersonnelReportController::class, 'index'])->name('index');
            Route::get('/create', [PersonnelReportController::class, 'create'])->name('create');
            Route::post('/', [PersonnelReportController::class, 'store'])->name('store');
          
        });
    });



