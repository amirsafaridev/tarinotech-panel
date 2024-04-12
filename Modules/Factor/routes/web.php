<?php

use Modules\Factor\app\Http\Controllers\Web\FactorController;

Route::group(['as' => 'factor.'], function () {
    Route::get('/{identify}', [FactorController::class, 'index'])
        ->name('index')
        ->whereAlphaNumeric('identify');
});
