<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\app\Http\Controllers\Admin\AdsController;
use Modules\Project\app\Http\Controllers\Admin\OptionController;
use Modules\Project\app\Http\Controllers\Admin\ProjectController;
use Modules\Project\app\Http\Controllers\Admin\SeoController;
use Modules\Project\app\Http\Controllers\Admin\StatusController;
use Modules\Project\app\Http\Controllers\Admin\TypeController;
use Modules\Project\app\Http\Controllers\Admin\WebController;

Route::group(['guard' => 'admin'], function () {

    Route::group(['as' => 'type.', 'prefix' => 'type'], function () {
        Route::get('/', [TypeController::class, 'index'])->name('index');
        Route::get('/data', [TypeController::class, 'data'])->name('data');
        Route::get('/create', [TypeController::class, 'create'])->name('create');
        Route::get('/{project_type}', [TypeController::class, 'edit'])->name('edit');
        Route::post('/', [TypeController::class, 'store'])->name('store');
        Route::patch('/{project_type}', [TypeController::class, 'update'])->name('update');
        Route::delete('/{project_type}', [TypeController::class, 'destroy'])->name('destroy');
    });

    Route::group(['as' => 'option.', 'prefix' => 'option'], function () {
        Route::get('/', [OptionController::class, 'index'])->name('index');
        Route::get('/data', [OptionController::class, 'data'])->name('data');
        Route::get('/create', [OptionController::class, 'create'])->name('create');
        Route::get('/{project_option}', [OptionController::class, 'edit'])->name('edit');
        Route::post('/', [OptionController::class, 'store'])->name('store');
        Route::patch('/{project_option}', [OptionController::class, 'update'])->name('update');
        Route::delete('/{project_option}', [OptionController::class, 'destroy'])->name('destroy');
    });

    Route::group(['as' => 'status.', 'prefix' => 'status'], function () {
        Route::get('/', [StatusController::class, 'index'])->name('index');
        Route::get('/data', [StatusController::class, 'data'])->name('data');
        Route::get('/create', [StatusController::class, 'create'])->name('create');
        Route::get('/{project_status}', [StatusController::class, 'edit'])->name('edit');
        Route::post('/', [StatusController::class, 'store'])->name('store');
        Route::patch('/{project_status}', [StatusController::class, 'update'])->name('update');
        Route::delete('/{project_status}', [StatusController::class, 'destroy'])->name('destroy');
    });

    Route::group(['as' => 'web.', 'prefix' => 'web'], function () {
        Route::get('/', [WebController::class, 'index'])->name('index');
        Route::get('/data', [WebController::class, 'data'])->name('data');
        Route::get('/create', [WebController::class, 'create'])->name('create');

        Route::group([], function () {
            Route::get('/{projectId}/show', [WebController::class, 'show'])->name('show');
            Route::get('/{projectId}', [WebController::class, 'edit'])->name('edit');
            Route::patch('/{projectId}', [WebController::class, 'update'])->name('update');
        })->whereNumber('projectId');

        Route::post('/', [WebController::class, 'store'])->name('store');
    });

    Route::group(['as' => 'seo.', 'prefix' => 'seo'], function () {
        Route::get('/', [SeoController::class, 'index'])->name('index');
        Route::get('/data', [SeoController::class, 'data'])->name('data');
        Route::get('/create', [SeoController::class, 'create'])->name('create');

        Route::group([], function () {
            Route::get('/{projectId}/show', [SeoController::class, 'show'])->name('show');
            Route::get('/{projectId}', [SeoController::class, 'edit'])->name('edit');
            Route::patch('/{projectId}', [SeoController::class, 'update'])->name('update');
        })->whereNumber('projectId');

        Route::post('/', [SeoController::class, 'store'])->name('store');
    });

    Route::group(['as' => 'ads.', 'prefix' => 'ads'], function () {
        Route::get('/', [AdsController::class, 'index'])->name('index');
        Route::get('/data', [AdsController::class, 'data'])->name('data');
        Route::get('/create', [AdsController::class, 'create'])->name('create');

        Route::group([], function () {
            Route::get('/{projectId}/show', [AdsController::class, 'show'])->name('show');
            Route::get('/{projectId}', [AdsController::class, 'edit'])->name('edit');
            Route::patch('/{projectId}', [AdsController::class, 'update'])->name('update');
        })->whereNumber('projectId');

        Route::post('/', [AdsController::class, 'store'])->name('store');
    });

    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/data', [ProjectController::class, 'data'])->name('data');
    Route::get('/{project}', [ProjectController::class, 'manage'])->name('manage');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
});
