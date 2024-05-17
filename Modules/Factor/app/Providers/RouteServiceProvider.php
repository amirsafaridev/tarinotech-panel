<?php

namespace Modules\Factor\app\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\Factor\app\Http\Controllers';

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

        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1/factor')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Factor', '/routes/api.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapAdminRoutes(): void
    {
        $prefix = config('routes.admin-prefix');
        Route::prefix($prefix.'/factor')
            ->namespace($this->moduleNamespace)
            ->middleware(['web', 'admin.auth', 'acl', 'admin.scope'])
            ->as('admin.factor.')
            ->group(module_path('Factor', '/routes/admin.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapWebRoutes(): void
    {
        Route::prefix('/factor')
            ->namespace($this->moduleNamespace)
            ->middleware(['web'])
            ->as('factor.')
            ->group(module_path('Factor', '/routes/web.php'));
    }
}
