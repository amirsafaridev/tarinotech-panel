<?php

namespace Modules\Project\app\Providers;

use App\Enums\Database\Role\PermissionName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Route as RouteIlluminate;
use Illuminate\Support\Facades\Route;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Models\Project;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\Project\app\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();

        $this->bindAdminModels();
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
        Route::prefix('api/v1/project')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Project', '/routes/api.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapAdminRoutes(): void
    {
        $prefix = config('routes.admin-prefix');
        Route::prefix($prefix.'/project')
            ->namespace($this->moduleNamespace)
            ->middleware(['web', 'admin.auth', 'acl', 'admin.scope'])
            ->as('admin.project.')
            ->group(module_path('Project', '/routes/admin.php'));
    }

    private function bindAdminModels(): void
    {
        $this->bindProjectModel();
        $this->bindFactorModel();
    }

    private function bindProjectModel()
    {
        Route::bind('project', function ($value, RouteIlluminate $route) {

            if ($this->isAdminRoute($route) && $this->hasProjectSelfPermission()) {

                Project::addGlobalScope('project_self_scope', function (Builder $builder) {
                    $builder->where('admin_id', auth('admin')->id());
                });

            }

            return Project::findOrFail($value);
        });
    }

    private function bindFactorModel()
    {
        Route::bind('factor', function ($value, RouteIlluminate $route) {

            if ($this->isAdminRoute($route) && $this->hasProjectSelfPermission()) {
                Factor::addGlobalScope('project_self_scope', function (Builder $builder) {
                    $builder->where('admin_id', auth('admin')->id());
                });
            }

            return Factor::findOrFail($value);
        });
    }

    private function hasProjectSelfPermission(): bool
    {
        return hasAdminPermission(PermissionName::PROJECT_SELF);
    }

    protected function isAdminRoute(RouteIlluminate $route): bool
    {
        return str_starts_with($route->getName(), 'admin.');
    }
}
