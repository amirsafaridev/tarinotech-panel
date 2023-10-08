<?php

// Login
use App\Http\Controllers\Admin\AdditionalFeatureController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminGoalController;
use App\Http\Controllers\Admin\AdminPasswordController;
use App\Http\Controllers\Admin\Ajax\Select2Controller;
use App\Http\Controllers\Admin\AutoMessageController;
use App\Http\Controllers\Admin\Blog\BlogCategoryController;
use App\Http\Controllers\Admin\Blog\BlogController;
use App\Http\Controllers\Admin\FreeDayController;
use App\Http\Controllers\Admin\GroupGoalController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PackagePriceController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PresenterController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectStatusController;
use App\Http\Controllers\Admin\ProjectTypeController;
use App\Http\Controllers\Admin\Report\GoalController as GoalControllerReport;
use App\Http\Controllers\Admin\Report\GoalGroupController as GoalGroupControllerReport;
use App\Http\Controllers\Admin\Report\LoginController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SampleMessageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TransactionCategoryController;
use App\Http\Controllers\Admin\UserController;
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

    Route::controller(Select2Controller::class)->group(function () {
        Route::get('/admin/ajax/select2/admin', 'selectAdmin')->name('ajax.select2.admin');
        Route::get('/admin/ajax/select2/user', 'selectUser')->name('ajax.select2.user');
        Route::get('/admin/ajax/select2/project', 'selectProject')->name('ajax.select2.project');
    });

    Route::controller(AdminGoalController::class)->group(function () {
        Route::get('/admin/{admin}/goal', 'index')->name('admin.goal');
        Route::post('/admin/{admin}/goal', 'save')->name('admin.goal.save');
    });

    Route::controller(GroupGoalController::class)->group(function () {
        Route::get('/group-goal', 'index')->name('admin.group-goal');
        Route::post('/group-goal', 'save')->name('admin.group-goal.save');
    });

    Route::controller(GoalControllerReport::class)->group(function () {
        Route::get('/report/goal', 'index')->name('report.goal');
    });

    Route::controller(GoalGroupControllerReport::class)->group(function () {
        Route::get('/report/goal-group', 'index')->name('report.goal-group');
    });

    Route::controller(LoginController::class)->group(function () {
        Route::get('/report/login', 'index')->name('report.login');
        Route::get('/report/{login}/login', 'show')->name('report.login-show')
            ->whereUlid('login');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index')->name('user.index');
        Route::get('/user/data', 'data')->name('user.data');
        Route::get('/user/create', 'create')->name('user.create');
        Route::post('/user/store', 'store')->name('user.store');
        Route::get('/user/{user}/edit', 'edit')->name('user.edit');
        Route::get('/user/{user}/show', 'show')->name('user.show');
        Route::patch('/user/{user}/update', 'update')->name('user.update');
        Route::delete('/user/{user}/destroy', 'destroy')->name('user.destroy');
    });

    Route::controller(PresenterController::class)->group(function () {
        Route::get('/presenter', 'index')->name('presenter.index');
        Route::get('/presenter/data', 'data')->name('presenter.data');
        Route::get('/presenter/create', 'create')->name('presenter.create');
        Route::post('/presenter/store', 'store')->name('presenter.store');
        Route::get('/presenter/{user}/edit', 'edit')->name('presenter.edit');
        Route::get('/presenter/{user}/show', 'show')->name('presenter.show');
        Route::patch('/presenter/{user}/update', 'update')->name('presenter.update');
        Route::delete('/presenter/{user}/destroy', 'destroy')->name('presenter.destroy');
    })->middleware('ensure.presenter');

    Route::controller(RoleController::class)->group(function () {
        Route::get('/role', 'index')->name('role.index');
        Route::get('/role/data', 'data')->name('role.data');
        Route::get('/role/create', 'create')->name('role.create');
        Route::post('/role/store', 'store')->name('role.store');
        Route::get('/role/edit/{role}', 'edit')->name('role.edit');
        Route::patch('/role/update/{role}', 'update')->name('role.update');
        Route::delete('/role/destroy/{role}', 'destroy')->name('role.destroy');
    });

    Route::controller(ProjectController::class)->group(function () {
        Route::get('/project', 'index')->name('project.index');
        Route::get('/project/data', 'data')->name('project.data');
        Route::get('/project/create', 'create')->name('project.create');
        Route::post('/project/store', 'store')->name('project.store');
        Route::get('/project/edit/{project}', 'edit')->name('project.edit');
        Route::patch('/project/update/{project}', 'update')->name('project.update');
        Route::delete('/project/destroy/{project}', 'destroy')->name('project.destroy');
    });

    Route::controller(SettingController::class)->group(function () {
        Route::get('/setting', 'index')->name('setting.index');
        Route::patch('/setting', 'update')->name('setting.update');
    });

    Route::controller(ProjectTypeController::class)->group(function () {
        Route::get('/project-type', 'index')->name('project-type.index');
        Route::get('/project-type/data', 'data')->name('project-type.data');
        Route::get('/project-type/{projectType}/show', 'show')->name('project-type.show');

    });

    Route::controller(ProjectStatusController::class)->group(function () {
        Route::get('/project-status', 'index')->name('project-status.index');
        Route::get('/project-status/data', 'data')->name('project-status.data');
        Route::get('/project-status/create', 'create')->name('project-status.create');
        Route::post('/project-status/store', 'store')->name('project-status.store');
        Route::get('/project-status/{projectStatus}/edit', 'edit')->name('project-status.edit');
        Route::patch('/project-status/{projectStatus}/update', 'update')->name('project-status.update');
        Route::delete('/project-status/{projectStatus}/destroy', 'destroy')->name('project-status.destroy');
    });

    Route::controller(PackagePriceController::class)->group(function () {
        Route::get('/package/{package}/price/{packagePrice}/edit', 'edit')->name('package-price.edit');
        Route::patch('/package/{package}/price/{packagePrice}/update', 'update')->name('package-price.update');
        Route::delete('/package/{package}/price/{packagePrice}/destroy', 'destroy')->name('package-price.destroy');
    });

    Route::controller(PackageController::class)->group(function () {
        Route::get('/package', 'index')->name('package.index');
        Route::get('/package/data', 'data')->name('package.data');
        Route::get('/package/create', 'create')->name('package.create');
        Route::post('/package/store', 'store')->name('package.store');
        Route::get('/package/{package}/edit', 'edit')->name('package.edit');
        Route::patch('/package/{package}/update', 'update')->name('package.update');
        Route::delete('/package/{package}/destroy', 'destroy')->name('package.destroy');
    });

    Route::controller(AdditionalFeatureController::class)->group(function () {
        Route::get('/additional-features', 'index')->name('additional-features.index');
        Route::get('/additional-features/data', 'data')->name('additional-features.data');
        Route::get('/additional-features/create', 'create')->name('additional-features.create');
        Route::post('/additional-features/store', 'store')->name('additional-features.store');
        Route::get('/additional-features/{additionalFeature}/edit', 'edit')->name('additional-features.edit');
        Route::patch('/additional-features/{additionalFeature}/update', 'update')->name('additional-features.update');
        Route::delete('/additional-features/{additionalFeature}/destroy', 'destroy')->name('additional-features.destroy');
    });

    Route::controller(TransactionCategoryController::class)->group(function () {
        Route::get('/transaction-category', 'index')->name('transaction-category.index');
        Route::get('/transaction-category/data', 'data')->name('transaction-category.data');
        Route::get('/transaction-category/create', 'create')->name('transaction-category.create');
        Route::post('/transaction-category/store', 'store')->name('transaction-category.store');
        Route::get('/transaction-category/{transactionCategory}/edit', 'edit')->name('transaction-category.edit');
        Route::patch('/transaction-category/{transactionCategory}/update', 'update')->name('transaction-category.update');
        Route::delete('/transaction-category/{transactionCategory}/destroy', 'destroy')->name('transaction-category.destroy');
    });

    Route::controller(SampleMessageController::class)->group(function () {
        Route::get('/sample-message', 'index')->name('sample-message.index');
        Route::get('/sample-message/data', 'data')->name('sample-message.data');
        Route::get('/sample-message/create', 'create')->name('sample-message.create');
        Route::post('/sample-message/store', 'store')->name('sample-message.store');
        Route::get('/sample-message/{sampleMessage}/edit', 'edit')->name('sample-message.edit');
        Route::patch('/sample-message/{sampleMessage}/update', 'update')->name('sample-message.update');
        Route::delete('/sample-message/{sampleMessage}/destroy', 'destroy')->name('sample-message.destroy');
    });

    Route::controller(FreeDayController::class)->group(function () {
        Route::get('/free-day', 'index')->name('free-day.index');
        Route::get('/free-day/data', 'data')->name('free-day.data');
        Route::get('/free-day/create', 'create')->name('free-day.create');
        Route::post('/free-day/store', 'store')->name('free-day.store');
        Route::get('/free-day/{freeDay}/edit', 'edit')->name('free-day.edit');
        Route::patch('/free-day/{freeDay}/update', 'update')->name('free-day.update');
        Route::delete('/free-day/{freeDay}/destroy', 'destroy')->name('free-day.destroy');
    });

    Route::controller(BlogCategoryController::class)->group(function () {
        Route::get('/blog/category', 'index')->name('blog-category.index');
        Route::get('/blog/category/data', 'data')->name('blog-category.data');
        Route::get('/blog/category/create', 'create')->name('blog-category.create');
        Route::post('/blog/category/store', 'store')->name('blog-category.store');
        Route::get('/blog/category/{blogCategory}/edit', 'edit')->name('blog-category.edit');
        Route::patch('/blog/category/{blogCategory}/update', 'update')->name('blog-category.update');
        Route::delete('/blog/category/{blogCategory}/destroy', 'destroy')->name('blog-category.destroy');
    });

    Route::controller(BlogController::class)->group(function () {
        Route::get('/blog', 'index')->name('blog.index');
        Route::get('/blog/data', 'data')->name('blog.data');
        Route::get('/blog/create', 'create')->name('blog.create');
        Route::post('/blog/store', 'store')->name('blog.store');
        Route::get('/blog/{blog}/edit', 'edit')->name('blog.edit');
        Route::patch('/blog/{blog}/update', 'update')->name('blog.update');
        Route::delete('/blog/{blog}/destroy', 'destroy')->name('blog.destroy');
    });

    Route::controller(AutoMessageController::class)->group(function () {
        Route::get('/auto-message', 'index')->name('auto-message.index');
        Route::get('/auto-message/data', 'data')->name('auto-message.data');
        Route::get('/auto-message/{sampleMessage}/edit', 'edit')->name('auto-message.edit');
        Route::patch('/auto-message/{sampleMessage}/update', 'update')->name('auto-message.update');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::get('/profile/logout', 'logout')->name('profile.logout');
        Route::get('/profile/password', 'password')->name('profile.password');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::patch('/profile/password', 'updatePassword')->name('profile.password.update');
    });
});
