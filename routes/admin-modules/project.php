<?php

use App\Http\Controllers\Admin\Project\AdsProjectController;
use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\Admin\Project\SeoProjectController;
use App\Http\Controllers\Admin\Project\WebProjectController;
use App\Http\Controllers\Admin\ProjectStatusController;
use App\Http\Controllers\Admin\ProjectTypeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {

    Route::controller(ProjectTypeController::class)->group(function () {
        Route::get('/project/type', 'index')->name('project.type.index');
        Route::get('/project/type/data', 'data')->name('project.type.data');
        Route::get('/project/type/create', 'create')->name('project.type.create');
        Route::post('/project/type/store', 'store')->name('project.type.store');
        Route::get('/project/type/{projectType}/edit', 'edit')->name('project.type.edit');
        Route::patch('/project/type/{projectType}/update', 'update')->name('project.type.update');
        Route::delete('/project/type/{projectType}/destroy', 'destroy')->name('project.type.destroy');

    });

    Route::controller(ProjectStatusController::class)->group(function () {
        Route::get('/project/status', 'index')->name('project.status.index');
        Route::get('/project/status/data', 'data')->name('project.status.data');
        Route::get('/project/status/create', 'create')->name('project.status.create');
        Route::post('/project/status/store', 'store')->name('project.status.store');
        Route::get('/project/status/{projectStatus}/edit', 'edit')->name('project.status.edit');
        Route::patch('/project/status/{projectStatus}/update', 'update')->name('project.status.update');
        Route::delete('/project/status/{projectStatus}/destroy', 'destroy')->name('project.status.destroy');
    });

    Route::controller(WebProjectController::class)->group(function () {
        Route::get('/project/web', 'index')->name('project.web.index');
        Route::get('/project/web/create', 'create')->name('project.web.create');
        Route::post('/project/web/store', 'store')->name('project.web.store');

        Route::get('/project/web/{projectId}/show', 'show')->name('project.web.show')
            ->whereNumber('projectId');

        Route::get('/project/web/{projectId}/edit', 'edit')->name('project.web.edit')
            ->whereNumber('projectId');

        Route::patch('/project/web/{projectId}/update', 'update')->name('project.web.update')
            ->whereNumber('projectId');
    });

    Route::controller(SeoProjectController::class)->group(function () {
        Route::get('/project/seo', 'index')->name('project.seo.index');
        Route::get('/project/seo/create', 'create')->name('project.seo.create');
        Route::post('/project/seo/store', 'store')->name('project.seo.store');

        Route::get('/project/seo/{projectId}/show', 'show')->name('project.seo.show')
            ->whereNumber('projectId');

        Route::get('/project/seo/{projectId}/edit', 'edit')->name('project.seo.edit')
            ->whereNumber('projectId');

        Route::patch('/project/seo/{projectId}/update', 'update')->name('project.seo.update')
            ->whereNumber('projectId');
    });

    Route::controller(AdsProjectController::class)->group(function () {
        Route::get('/project/ads', 'index')->name('project.ads.index');
        Route::get('/project/ads/create', 'create')->name('project.ads.create');
        Route::post('/project/ads/store', 'store')->name('project.ads.store');

        Route::get('/project/ads/{projectId}/show', 'show')->name('project.ads.show')
            ->whereNumber('projectId');

        Route::get('/project/ads/{projectId}/edit', 'edit')->name('project.ads.edit')
            ->whereNumber('projectId');

        Route::patch('/project/ads/{projectId}/update', 'update')->name('project.ads.update')
            ->whereNumber('projectId');
    });

    Route::controller(ProjectController::class)->group(function () {
        Route::get('/project', 'index')->name('project.index');
        Route::delete('/project/destroy/{project}', 'destroy')->name('project.destroy');
    });

});
