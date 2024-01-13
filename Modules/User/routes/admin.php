<?php

use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\Admin\KnowledgeWayController;
use Modules\User\app\Http\Controllers\Admin\PresenterController;
use Modules\User\app\Http\Controllers\Admin\UserController;
use Modules\User\app\Http\Controllers\Admin\UserImportController;

Route::group(['guard' => 'admin', 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/index', [UserController::class, 'index'])->name('index');

    /* Import */
    Route::get('/import', [UserImportController::class, 'index'])->name('import.index');
    Route::post('/import', [UserImportController::class, 'import'])->name('import.store');

    Route::get('/data', [UserController::class, 'data'])->name('data');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::get('/{user}', [UserController::class, 'edit'])->name('edit');
    Route::get('/{user}/show', [UserController::class, 'show'])->name('show');

    Route::post('/index', [UserController::class, 'store'])->name('store');
    Route::patch('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

});

Route::group(['guard' => 'admin', 'prefix' => 'presenter', 'as' => 'presenter.'], function () {
    Route::get('/', [PresenterController::class, 'index'])->name('index');
    Route::get('/data', [PresenterController::class, 'data'])->name('data');
    Route::get('/create', [PresenterController::class, 'create'])->name('create');
    Route::get('/{user}', [PresenterController::class, 'edit'])->name('edit');
    Route::get('/{user}/show', [PresenterController::class, 'show'])->name('show');

    Route::post('/', [PresenterController::class, 'store'])->name('store');
    Route::patch('/{user}', [PresenterController::class, 'update'])->name('update');
    Route::delete('/{user}', [PresenterController::class, 'destroy'])->name('destroy');
});

Route::group(['guard' => 'admin', 'prefix' => 'knowledge-way', 'as' => 'knowledge-way.'], function () {
    Route::get('/', [KnowledgeWayController::class, 'index'])->name('index');
    Route::get('/data', [KnowledgeWayController::class, 'data'])->name('data');
    Route::get('/create', [KnowledgeWayController::class, 'create'])->name('create');
    Route::get('/{knowledgeWay}', [KnowledgeWayController::class, 'edit'])->name('edit');

    Route::post('/', [KnowledgeWayController::class, 'store'])->name('store');
    Route::patch('/{knowledgeWay}', [KnowledgeWayController::class, 'update'])->name('update');
    Route::delete('/{knowledgeWay}', [KnowledgeWayController::class, 'destroy'])->name('destroy');
});
