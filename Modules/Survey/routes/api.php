<?php

use Illuminate\Support\Facades\Route;
use Modules\Survey\app\Http\Controllers\Api\SurveyController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('surveys', [SurveyController::class, 'index'])->name('survey.index');
});
