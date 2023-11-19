<?php

namespace Modules\Admin\app\Providers;

use Illuminate\Support\ServiceProvider;
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
            $view->with('roles', $roles);
        });
    }
}
