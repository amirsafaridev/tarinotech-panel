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

use App\Enums\Database\Print\PrintableType;
use Modules\Contract\app\Http\Controllers\Admin\Preview\FactorController;
use Modules\Contract\app\Http\Controllers\Admin\Preview\WebProjectController;
use Modules\Contract\app\Http\Controllers\Admin\PreviewController;
use Modules\Contract\app\Http\Controllers\Admin\SignableController;
use Modules\Contract\app\Http\Controllers\Admin\SignController;

Route::group(['guard' => 'admin'], function () {

    Route::get('/preview/{id}/project-web', [WebProjectController::class, 'index'])
        ->name('web-project.preview');

    Route::get('/preview/{id}/factor', [FactorController::class, 'index'])
        ->name('factor.preview');

    /*Route::get('/preview/{targetType}/{targetId}', [PreviewController::class, 'index'])
        ->whereNumber('targetId')
        ->whereIn('targetType', [
            PrintableType::ProjectWeb,
            PrintableType::ProjectSeo,
            PrintableType::ProjectAds,
            PrintableType::Factor,
        ])
        ->name('preview');*/

    Route::group(['prefix' => 'sign', 'as' => 'sign.'], function () {
        Route::get('/', [SignController::class, 'index'])->name('index');
        Route::get('/data', [SignController::class, 'data'])->name('data');
        Route::get('/{signable}', [SignController::class, 'edit'])->name('edit');
        Route::patch('/{signable}', [SignController::class, 'update'])->name('update');
        Route::delete('/{signable}', [SignController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'project', 'as' => 'project.'], function () {
        Route::get('/web/{projectWeb}', [SignableController::class, 'web'])->name('web');
        Route::get('/seo/{projectAds}', [SignableController::class, 'ads'])->name('ads');
        Route::get('/ads/{projectSeo}', [SignableController::class, 'seo'])->name('seo');
    });
});
