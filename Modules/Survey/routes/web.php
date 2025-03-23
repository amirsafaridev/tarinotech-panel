<?php

use Illuminate\Support\Facades\Route;
use Modules\Survey\app\Http\Controllers\Web\SurveyController;

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

Route::group([], function () {
    Route::get('survey/{accessToken}', [SurveyController::class, 'show'])->name('survey.public.show');
    Route::post('survey/{accessToken}/submit', [SurveyController::class, 'submit'])->name('survey.public.submit');
    Route::get('survey/{accessToken}/thankYou', [SurveyController::class, 'thankYou'])->name('survey.public.thankYou');
});
