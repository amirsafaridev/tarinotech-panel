<?php

use Illuminate\Support\Facades\Route;
use Modules\Survey\app\Http\Controllers\Admin\QuestionController;
use Modules\Survey\app\Http\Controllers\Admin\QuestionOptionController;
use Modules\Survey\app\Http\Controllers\Admin\SurveyController;

Route::group(['guard' => 'admin'], function () {

    // Surveys

    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::get('/data', [SurveyController::class, 'data'])->name('data');
    Route::get('/create', [SurveyController::class, 'create'])->name('create');
    Route::post('/', [SurveyController::class, 'store'])->name('store');
    Route::get('/{survey}', [SurveyController::class, 'edit'])->name('edit');
    Route::patch('/{survey}', [SurveyController::class, 'update'])->name('update');
    Route::delete('/{survey}', [SurveyController::class, 'destroy'])->name('destroy');
    Route::get('/{survey}/preview', [SurveyController::class, 'preview'])->name('preview');
    Route::get('/{survey}/duplicate', [SurveyController::class, 'duplicate'])->name('duplicate');
    Route::post('/{survey}/toggle-status', [SurveyController::class, 'toggleStatus'])->name('toggle-status');

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

    /* // Survey Responses
     Route::group(['as' => 'response.', 'prefix' => '{survey}/response'], function () {
         Route::get('/', [SurveyResponseController::class, 'index'])->name('index');
         Route::get('/data', [SurveyResponseController::class, 'data'])->name('data');
         Route::get('/{response}', [SurveyResponseController::class, 'show'])->name('show');
         Route::delete('/{response}', [SurveyResponseController::class, 'destroy'])->name('destroy');
         Route::get('/export', [SurveyResponseController::class, 'export'])->name('export');

         // Survey Answers for a specific response
         Route::group(['as' => 'answer.', 'prefix' => '{response}/answer'], function () {
             Route::get('/', [SurveyAnswerController::class, 'index'])->name('index');
         });
     });

     // Survey Reports
     Route::group(['as' => 'report.', 'prefix' => '{survey}/report'], function () {
         Route::get('/', [SurveyReportController::class, 'index'])->name('index');
         Route::get('/summary', [SurveyReportController::class, 'summary'])->name('summary');
         Route::get('/question/{question}', [SurveyReportController::class, 'questionReport'])->name('question');
         Route::get('/export', [SurveyReportController::class, 'export'])->name('export');
         Route::get('/charts', [SurveyReportController::class, 'charts'])->name('charts');
     });

     // Survey Settings
     Route::group(['as' => 'settings.', 'prefix' => '{survey}/settings'], function () {
         Route::get('/', [SurveySettingsController::class, 'index'])->name('index');
         Route::patch('/', [SurveySettingsController::class, 'update'])->name('update');
         Route::get('/appearance', [SurveySettingsController::class, 'appearance'])->name('appearance');
         Route::patch('/appearance', [SurveySettingsController::class, 'updateAppearance'])->name('update-appearance');
         Route::get('/notifications', [SurveySettingsController::class, 'notifications'])->name('notifications');
         Route::patch('/notifications', [SurveySettingsController::class, 'updateNotifications'])->name('update-notifications');
     });*/

});
