<?php

use Illuminate\Support\Facades\Route;
use Modules\Ticket\app\Http\Controllers\Admin\TicketController;

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

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [TicketController::class, 'index'])->name('index');
    Route::get('/data', [TicketController::class, 'data'])->name('data');
    Route::get('/{chat}/message', [TicketController::class, 'message'])->name('message');
    Route::patch('/{chat}', [TicketController::class, 'update'])->name('update');
    Route::delete('/{chat}', [TicketController::class, 'destroy'])->name('destroy');
});
