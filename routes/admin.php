<?php

// Login
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPasswordController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Admin'], function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Reset Password
    Route::get('password/forget', 'Auth\ForgotPasswordController@index')->name('password.forget');
    Route::post('password/sendOtpCode', 'Auth\ForgotPasswordController@sendOtpCode')->name('password.email');

    Route::get('password/reset', 'Auth\ResetPasswordController@index')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
});

Route::group(['middleware' => ['admin.auth'/*,'acl'*/], 'guard' => 'admin'], function () {
    /* this function for help to route ui dashboard */
    Route::get('/', [HomeController::class, 'redirect'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/permission/sync', [PermissionController::class, 'sync'])->name('permission.sync');

    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin', 'index')->name('admin.index');
        Route::get('/admin/data', 'data')->name('admin.data');
        Route::get('/admin/create', 'create')->name('admin.create');
        Route::post('/admin/store', 'store')->name('admin.store');
        Route::get('/admin/{admin}/show', 'show')->name('admin.show');
        Route::get('/admin/{admin}/edit', 'edit')->name('admin.edit');
        Route::patch('/admin/{admin}/update', 'update')->name('admin.update');
        Route::delete('/admin/{admin}/destroy', 'destroy')->name('admin.destroy');
    });

    Route::controller(AdminPasswordController::class)->group(function () {
        Route::get('/admin/{admin}/password', 'index')->name('admin.password');
        Route::patch('/admin/{admin}/password', 'update')->name('admin.password.update');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('/role', 'index')->name('role.index');
        Route::get('/role/data', 'data')->name('role.data');
        Route::get('/role/create', 'create')->name('role.create');
        Route::post('/role/store', 'store')->name('role.store');
        Route::get('/role/edit/{role}', 'edit')->name('role.edit');
        Route::patch('/role/update/{role}', 'update')->name('role.update');
        Route::delete('/role/destroy/{role}', 'destroy')->name('role.destroy');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::get('/profile/logout', 'logout')->name('profile.logout');
        Route::get('/profile/password', 'password')->name('profile.password');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::patch('/profile/password', 'updatePassword')->name('profile.password.update');
    });
});
