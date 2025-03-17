<?php

use Illuminate\Support\Facades\Route;
use Modules\Personnel\app\Http\Controllers\DailyActivityController;

Route::middleware(['auth'])->group(function() {
    // Daily Activity Routes
    Route::prefix('daily-activity')->name('daily-activity.')->group(function() {
        Route::get('/', [DailyActivityController::class, 'index'])->name('index');
        Route::post('/toggle', [DailyActivityController::class, 'toggle'])->name('toggle');
        Route::post('/{activity}/request-edit', [DailyActivityController::class, 'requestEdit'])->name('request-edit');
    });

    // Admin Routes
    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function() {
        Route::prefix('personnel')->name('personnel.')->group(function() {
            Route::get('/active-hours', [DailyActivityController::class, 'adminIndex'])->name('active-hours');
            Route::post('/active-hours/{activity}/approve', [DailyActivityController::class, 'approveEdit'])->name('approve-edit');
            Route::post('/active-hours/{activity}/reject', [DailyActivityController::class, 'rejectEdit'])->name('reject-edit');
        });
    });
}); 