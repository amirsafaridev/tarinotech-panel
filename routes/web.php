<?php

use App\Http\Controllers\DeployController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/test', [TestController::class, 'index']);
Route::get('/test/send-email', [TestController::class, 'sendEmail']);
Route::get('/test/aws-upload', [TestController::class, 'awsUpload']);
Route::get('/test/aws-list', [TestController::class, 'awsList']);
Route::get('/deploy', [DeployController::class, 'index']);
