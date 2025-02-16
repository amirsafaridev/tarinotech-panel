<?php

use Illuminate\Support\Facades\Route;
use Modules\FreeDay\app\Http\Controllers\Admin\FreeDayController;
use Modules\FreeDay\app\Http\Controllers\Admin\FreeDayImportController;

Route::group(['guard' => 'admin'], function () {
    Route::get('/', [FreeDayController::class, 'index'])->name('index');
    Route::get('/data', [FreeDayController::class, 'data'])->name('data');
    Route::get('/create', [FreeDayController::class, 'create'])->name('create');

    Route::get('/import', [FreeDayImportController::class, 'index'])->name('import.index');
    Route::post('/import', [FreeDayImportController::class, 'import'])->name('import.store');

    Route::get('/{freeDay}', [FreeDayController::class, 'edit'])->name('edit');
    Route::post('/', [FreeDayController::class, 'store'])->name('store');
    Route::patch('/{freeDay}', [FreeDayController::class, 'update'])->name('update');
    Route::delete('/{freeDay}', [FreeDayController::class, 'destroy'])->name('destroy');
});
