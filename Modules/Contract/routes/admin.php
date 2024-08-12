<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Contract\app\Http\Controllers\Admin\Preview\FactorController;
use Modules\Contract\app\Http\Controllers\Admin\Preview\SeoProjectController;
use Modules\Contract\app\Http\Controllers\Admin\Preview\WebProjectController;
use Modules\Contract\app\Http\Controllers\Admin\SignableAttachmentController;
use Modules\Contract\app\Http\Controllers\Admin\SignableController;
use Modules\Contract\app\Http\Controllers\Admin\SignController;
use Modules\Contract\app\Http\Controllers\Admin\UserSignController;

Route::group(['guard' => 'admin'], function () {

    Route::get('/preview/{id}/project-web', [WebProjectController::class, 'index'])
        ->name('web-project.preview');

    Route::get('/preview/{id}/project-seo', [SeoProjectController::class, 'index'])
        ->name('seo-project.preview');

    Route::get('/preview/{id}/factor', [FactorController::class, 'index'])
        ->name('factor.preview');

    Route::group(['prefix' => 'sign/user', 'as' => 'sign.user.'], function () {
        Route::get('/', [UserSignController::class, 'index'])->name('index');
        Route::get('/data', [UserSignController::class, 'data'])->name('data');
        Route::get('/{user_signable}', [UserSignController::class, 'edit'])->name('edit');
        Route::patch('/{user_signable}', [UserSignController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'sign', 'as' => 'sign.'], function () {
        Route::get('/', [SignController::class, 'index'])->name('index');
        Route::get('/data', [SignController::class, 'data'])->name('data');
        Route::get('/{signable}', [SignController::class, 'edit'])->name('edit');
        Route::patch('/{signable}', [SignController::class, 'update'])->name('update');
        Route::delete('/{signable}', [SignController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'project', 'as' => 'project.'], function () {
        Route::get('/web/{projectWeb}', [SignableController::class, 'web'])->name('web');
        Route::get('/ads/{projectAds}', [SignableController::class, 'ads'])->name('ads');
        Route::get('/seo/{projectSeo}', [SignableController::class, 'seo'])->name('seo');
    });

    Route::group(['prefix' => 'attachment', 'as' => 'attachment.'], function () {
        Route::post('/upload', [SignableAttachmentController::class, 'upload'])->name('upload');
        Route::DELETE('/destroy', [SignableAttachmentController::class, 'destroy'])->name('destroy');
    });
});
