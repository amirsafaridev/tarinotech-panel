<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\app\Http\Controllers\Admin\SettingController;

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

/* SETTING ROUTES */
Route::group([], function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::patch('/', [SettingController::class, 'update'])->name('update');
});
