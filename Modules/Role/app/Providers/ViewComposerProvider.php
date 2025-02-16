<?php

namespace Modules\Role\app\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

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
        view()->composer(['role::admin.create', 'role::admin.edit'], function ($view) {
            $permissions = Permission::query()
                ->get();
            $view->with('permissions', $permissions);
        });
    }
}
