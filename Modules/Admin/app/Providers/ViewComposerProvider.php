<?php

namespace Modules\Admin\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Admin\app\Models\JobTitle;
use Spatie\Permission\Models\Role;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['admin::admin.create', 'admin::admin.edit'], function ($view) {
            $roles = Role::query()->get();
            $jobTitles = JobTitle::query()->get();
            $view->with(compact('roles', 'jobTitles'));
        });

        view()->composer(['admin::admin.index'], function ($view) {
            $jobTitles = JobTitle::query()->get();
            $view->with(compact('jobTitles'));
        });
    }
}
