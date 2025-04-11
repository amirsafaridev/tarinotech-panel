<?php

use Illuminate\Support\Facades\Route;
use Modules\Ticket\app\Http\Controllers\Admin\TicketController;
use Modules\Ticket\app\Http\Controllers\Admin\TicketPriorityController;
use Modules\Ticket\app\Http\Controllers\Admin\TicketStatusController;
use Modules\Ticket\app\Http\Controllers\Admin\TicketStatusTransitionController;

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

    // Ticket Status Routes
    Route::group(['prefix' => 'statuses', 'as' => 'statuses.'], function () {
        Route::get('/', [TicketStatusController::class, 'index'])->name('index');
        Route::get('/create', [TicketStatusController::class, 'create'])->name('create');
        Route::post('/', [TicketStatusController::class, 'store'])->name('store');
        Route::get('/{ticketStatus}', [TicketStatusController::class, 'edit'])->name('edit');
        Route::patch('/{ticketStatus}', [TicketStatusController::class, 'update'])->name('update');
        Route::delete('/{ticketStatus}', [TicketStatusController::class, 'destroy'])->name('destroy');
    });

    // Ticket Priority Routes
    Route::group(['prefix' => 'priorities', 'as' => 'priorities.'], function () {
        Route::get('/', [TicketPriorityController::class, 'index'])->name('index');
        Route::get('/create', [TicketPriorityController::class, 'create'])->name('create');
        Route::post('/', [TicketPriorityController::class, 'store'])->name('store');
        Route::get('/{ticketPriority}', [TicketPriorityController::class, 'edit'])->name('edit');
        Route::patch('/{ticketPriority}', [TicketPriorityController::class, 'update'])->name('update');
        Route::delete('/{ticketPriority}', [TicketPriorityController::class, 'destroy'])->name('destroy');
    });

    // Ticket Status Transition Routes
    Route::group(['prefix' => 'transitions', 'as' => 'transitions.'], function () {
        Route::get('/', [TicketStatusTransitionController::class, 'index'])->name('index');
        Route::get('/create', [TicketStatusTransitionController::class, 'create'])->name('create');
        Route::post('/', [TicketStatusTransitionController::class, 'store'])->name('store');
        Route::get('/{ticketStatusTransition}', [TicketStatusTransitionController::class, 'edit'])->name('edit');
        Route::patch('/{ticketStatusTransition}', [TicketStatusTransitionController::class, 'update'])->name('update');
        Route::delete('/{ticketStatusTransition}', [TicketStatusTransitionController::class, 'destroy'])->name('destroy');
    });
});
