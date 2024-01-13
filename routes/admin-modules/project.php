<?php

use Illuminate\Support\Facades\Route;
use Modules\Project\app\Http\Controllers\Admin\ProjectFacilityController;

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {

    /*Route::controller(ProjectFacilityController::class)->group(function () {
        Route::group(['prefix' => '/project/{project}/'], function () {
            Route::get('facility', 'index')->name('project.facility.index');

            Route::get('facility/create', 'create')->name('project.facility.create');
            Route::post('facility', 'store')->name('project.facility.store');

            Route::get('facility/{facility}/edit', 'edit')->name('project.facility.edit');
            Route::put('facility/{facility}', 'update')->name('project.facility.update');

            Route::patch('facility/{facility}/confirm', 'update')->name('project.facility.confirm');
            Route::delete('facility/{facility}', 'destroy')->name('project.facility.destroy');
        });
    });*/

});
