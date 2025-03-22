<?php

use Illuminate\Support\Facades\Route;
use Modules\Survey\app\Http\Controllers\Admin\QuestionController;
use Modules\Survey\app\Http\Controllers\Admin\QuestionOptionController;
use Modules\Survey\app\Http\Controllers\Admin\SurveyController;
use Modules\Survey\app\Http\Controllers\Admin\SurveyReportChartController;
use Modules\Survey\app\Http\Controllers\Admin\SurveyReportController;
use Modules\Survey\app\Http\Controllers\Admin\SurveyReportQuestionController;
use Modules\Survey\app\Http\Controllers\Admin\SurveyReportSummaryController;
use Modules\Survey\app\Http\Controllers\Admin\SurveyResponseController;

Route::group(['guard' => 'admin'], function () {

    // Surveys
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::get('/data', [SurveyController::class, 'data'])->name('data');
    Route::get('/create', [SurveyController::class, 'create'])->name('create');
    Route::post('/', [SurveyController::class, 'store'])->name('store');
    Route::get('/{survey}', [SurveyController::class, 'edit'])->name('edit');
    Route::patch('/{survey}', [SurveyController::class, 'update'])->name('update');
    Route::delete('/{survey}', [SurveyController::class, 'destroy'])->name('destroy');
    Route::get('/{survey}/duplicate', [SurveyController::class, 'duplicate'])->name('duplicate');

    // Questions
    Route::group(['as' => 'question.', 'prefix' => '{survey}/question'], function () {
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::get('/create', [QuestionController::class, 'create'])->name('create');
        Route::post('/', [QuestionController::class, 'store'])->name('store');
        Route::get('/{question}', [QuestionController::class, 'edit'])->name('edit');
        Route::patch('/{question}', [QuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [QuestionController::class, 'reorder'])->name('reorder');

        // Question Options
        Route::group(['as' => 'option.', 'prefix' => '{question}/option'], function () {
            Route::get('/', [QuestionOptionController::class, 'index'])->name('index');
            Route::get('/create', [QuestionOptionController::class, 'create'])->name('create');
            Route::post('/', [QuestionOptionController::class, 'store'])->name('store');
            Route::get('/{option}', [QuestionOptionController::class, 'edit'])->name('edit');
            Route::patch('/{option}', [QuestionOptionController::class, 'update'])->name('update');
            Route::delete('/{option}', [QuestionOptionController::class, 'destroy'])->name('destroy');
            Route::post('/reorder', [QuestionOptionController::class, 'reorder'])->name('reorder');
        });
    });

    // Survey Responses
    Route::group(['as' => 'response.', 'prefix' => '{survey}/response'], function () {
        Route::get('/', [SurveyResponseController::class, 'index'])->name('index');
        Route::get('/data', [SurveyResponseController::class, 'data'])->name('data');
        Route::get('/{response}', [SurveyResponseController::class, 'show'])->name('show');
        Route::delete('/{response}', [SurveyResponseController::class, 'destroy'])->name('destroy');
        Route::get('/export', [SurveyResponseController::class, 'export'])->name('export');
    });

    // Survey Reports
    Route::group(['as' => 'report.', 'prefix' => '{survey}/report'], function () {
        Route::get('/', [SurveyReportController::class, 'index'])->name('index');
        Route::get('/summary', [SurveyReportSummaryController::class, 'index'])->name('summary');
        Route::get('/question/{question}', [SurveyReportQuestionController::class, 'index'])->name('question');
        Route::get('/export', [SurveyReportController::class, 'export'])->name('export');
        Route::get('/charts', [SurveyReportChartController::class, 'index'])->name('charts');
    });

});
