<?php

namespace Modules\Stream\app\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\Stream\app\Http\Controllers';

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

        $this->mapWebRoutes();
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapWebRoutes(): void
    {
        Route::prefix('/stream')
            ->namespace($this->moduleNamespace)
            ->as('stream.')
            ->group(module_path('Stream', '/routes/web.php'));
    }
}
