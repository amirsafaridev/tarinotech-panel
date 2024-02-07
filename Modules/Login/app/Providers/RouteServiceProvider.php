<?php

namespace Modules\Login\app\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\Login\app\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapAdminRoutes();

    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/va/1login')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Login', '/routes/api.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapAdminRoutes(): void
    {
        $prefix = config('routes.admin-prefix');
        Route::prefix($prefix.'/login')
            ->namespace($this->moduleNamespace)
            ->middleware(['web', 'admin.auth', 'acl'])
            ->as('admin.login.')
            ->group(module_path('Login', '/routes/admin.php'));
    }
}
