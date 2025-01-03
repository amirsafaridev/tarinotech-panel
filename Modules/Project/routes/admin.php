<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\app\Http\Controllers\Admin\AdsController;
use Modules\Project\app\Http\Controllers\Admin\BusinessDomainController;
use Modules\Project\app\Http\Controllers\Admin\FacilityController;
use Modules\Project\app\Http\Controllers\Admin\ProjectController;
use Modules\Project\app\Http\Controllers\Admin\ProjectFacilityRenewalController;
use Modules\Project\app\Http\Controllers\Admin\ProjectRenewalController;
use Modules\Project\app\Http\Controllers\Admin\SeoController;
use Modules\Project\app\Http\Controllers\Admin\SeoFactorController;
use Modules\Project\app\Http\Controllers\Admin\StatusController;
use Modules\Project\app\Http\Controllers\Admin\TypeController;
use Modules\Project\app\Http\Controllers\Admin\WebController;
use Modules\Project\app\Http\Controllers\Admin\WebFactorController;
use Modules\Project\app\Http\Controllers\Admin\WebImportController;
use Modules\Project\app\Http\Controllers\Admin\WebRequirementController;
use Modules\Project\app\Http\Controllers\Admin\WebStatusController;

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

    Route::group(['as' => 'business_domain.', 'prefix' => 'business_domain'], function () {
        Route::get('/', [BusinessDomainController::class, 'index'])->name('index');
        Route::get('/data', [BusinessDomainController::class, 'data'])->name('data');
        Route::get('/create', [BusinessDomainController::class, 'create'])->name('create');
        Route::get('/{businessDomain}', [BusinessDomainController::class, 'edit'])->name('edit');
        Route::post('/', [BusinessDomainController::class, 'store'])->name('store');
        Route::patch('/{businessDomain}', [BusinessDomainController::class, 'update'])->name('update');
        Route::delete('/{businessDomain}', [BusinessDomainController::class, 'destroy'])->name('destroy');
    });

    Route::group(['as' => 'facility.', 'prefix' => 'facility'], function () {
        Route::get('/', [FacilityController::class, 'index'])->name('index');
        Route::get('/data', [FacilityController::class, 'data'])->name('data');
        Route::get('/create', [FacilityController::class, 'create'])->name('create');
        Route::post('/', [FacilityController::class, 'store'])->name('store');
        Route::get('/{facility}', [FacilityController::class, 'edit'])->name('edit');
        Route::patch('/{facility}', [FacilityController::class, 'update'])->name('update');
        Route::delete('/{facility}', [FacilityController::class, 'destroy'])->name('destroy');
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
        Route::get('/create', [WebController::class, 'create'])->name('create');

        /* Import */
        Route::get('/import', [WebImportController::class, 'index'])->name('import.index');
        Route::post('/import', [WebImportController::class, 'import'])->name('import.store');

        Route::group([], function () {

            /* Status */
            Route::get('/{projectId}/status', [WebStatusController::class, 'index'])->name('edit.status');
            Route::patch('/{projectId}/status', [WebStatusController::class, 'update'])->name('update.status');

            Route::get('/{projectId}', [WebController::class, 'edit'])->name('edit');
            Route::get('/{projectId}/auto-factor', [WebFactorController::class, 'make'])->name('auto-factor');
            Route::patch('/{projectId}', [WebController::class, 'update'])->name('update');
            Route::delete('/{projectId}', [WebController::class, 'destroy'])->name('destroy');

            /* Requirement */
            Route::get('/{projectId}/requirement', [WebRequirementController::class, 'index'])->name('requirement');
            Route::patch('/{projectId}/requirement', [WebRequirementController::class, 'update'])->name('requirement.update');

        })->whereNumber('projectId');

        Route::post('/', [WebController::class, 'store'])->name('store');
    });

    Route::group(['as' => 'seo.', 'prefix' => 'seo'], function () {
        Route::get('/', [SeoController::class, 'index'])->name('index');
        Route::get('/create', [SeoController::class, 'create'])->name('create');

        Route::group([], function () {
            Route::get('/{projectId}', [SeoController::class, 'edit'])->name('edit');
            Route::get('/{projectId}/auto-factor', [SeoFactorController::class, 'make'])->name('auto-factor');
            Route::patch('/{projectId}', [SeoController::class, 'update'])->name('update');
            Route::delete('/{projectId}', [SeoController::class, 'destroy'])->name('destroy');

        })->whereNumber('projectId');

        Route::post('/', [SeoController::class, 'store'])->name('store');
    });

    Route::group(['as' => 'ads.', 'prefix' => 'ads'], function () {
        Route::get('/', [AdsController::class, 'index'])->name('index');
        Route::get('/create', [AdsController::class, 'create'])->name('create');

        Route::group([], function () {
            Route::get('/{projectId}', [AdsController::class, 'edit'])->name('edit');
            Route::patch('/{projectId}', [AdsController::class, 'update'])->name('update');
            Route::delete('/{projectId}', [AdsController::class, 'destroy'])->name('destroy');

        })->whereNumber('projectId');

        Route::post('/', [AdsController::class, 'store'])->name('store');
    });

    Route::group(['as' => 'renewal.', 'prefix' => 'renewal'], function () {
        Route::get('/', [ProjectRenewalController::class, 'index'])->name('index');

        Route::group(['prefix' => '{project_renewal_id}'], function () {
            Route::get('/', [ProjectRenewalController::class, 'show'])
                ->name('show');

            Route::group(['as' => 'facility.', 'prefix' => 'facility'], function () {

                Route::patch('/{project_facility_renewal_id}', [ProjectFacilityRenewalController::class, 'update'])
                    ->whereNumber('project_facility_renewal_id')->name('update');

                Route::delete('/{project_facility_renewal_id}', [ProjectFacilityRenewalController::class, 'destroy'])
                    ->whereNumber('project_facility_renewal_id')->name('destroy');

                Route::get('/create', [ProjectFacilityRenewalController::class, 'create'])->name('create');

                Route::post('/store', [ProjectFacilityRenewalController::class, 'store'])->name('store');

                Route::get('/make-factor', [ProjectFacilityRenewalController::class, 'makeFactor'])->name('make-factor');

            });

        })->whereNumber('project_renewal_id');
    });

    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/data', [ProjectController::class, 'data'])->name('data');
    Route::get('/{project}/print', [ProjectController::class, 'print'])->name('print');
    Route::get('/{project}', [ProjectController::class, 'manage'])->name('manage');
    Route::get('/{project}/renewal', [ProjectController::class, 'renewal'])->name('renewal');
});
