<?php

use Illuminate\Support\Facades\Route;
use Modules\Ticket\app\Http\Controllers\Admin\TicketController;
use Modules\Ticket\app\Http\Controllers\Admin\TicketEventController;
use Modules\Ticket\app\Http\Controllers\Admin\TicketPriorityController;
use Modules\Ticket\app\Http\Controllers\Admin\TicketStatusController;
use Modules\Ticket\app\Http\Controllers\Admin\TicketSubjectController;
use Modules\Ticket\app\Http\Controllers\Admin\TicketTransitionController;

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

    // Ticket Transition Routes
    Route::group(['prefix' => 'transitions', 'as' => 'transitions.'], function () {
        Route::get('/', [TicketTransitionController::class, 'index'])->name('index');
        Route::get('/create', [TicketTransitionController::class, 'create'])->name('create');
        Route::post('/', [TicketTransitionController::class, 'store'])->name('store');
        Route::get('/{ticketTransition}', [TicketTransitionController::class, 'edit'])->name('edit');
        Route::patch('/{ticketTransition}', [TicketTransitionController::class, 'update'])->name('update');
        Route::delete('/{ticketTransition}', [TicketTransitionController::class, 'destroy'])->name('destroy');
    });

    // Ticket Subject Routes
    Route::group(['prefix' => 'subjects', 'as' => 'subjects.'], function () {
        Route::get('/', [TicketSubjectController::class, 'index'])->name('index');
        Route::get('/create', [TicketSubjectController::class, 'create'])->name('create');
        Route::post('/', [TicketSubjectController::class, 'store'])->name('store');
        Route::get('/{ticketSubject}', [TicketSubjectController::class, 'edit'])->name('edit');
        Route::patch('/{ticketSubject}', [TicketSubjectController::class, 'update'])->name('update');
        Route::delete('/{ticketSubject}', [TicketSubjectController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [TicketSubjectController::class, 'restore'])->name('restore');
    });

    // Ticket Event Routes
    Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
        Route::get('/', [TicketEventController::class, 'index'])->name('index');
    });
});
