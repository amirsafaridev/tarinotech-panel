<?php

namespace App\Http\Middleware;

use App\Enums\Database\Role\PermissionName;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Models\Project;
use Symfony\Component\HttpFoundation\Response;

class AdminApplyScope
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->hasProjectSelfPermission()) {
            if (! $this->shouldSkipProjectScope($request->route()->getName())) {
                $this->applyProjectScope();
            }

            $this->applyFactorScope();
        }

        return $next($request);
    }

    /**
     * Check if the current admin has the PROJECT_SELF permission.
     */
    private function hasProjectSelfPermission(): bool
    {
        return hasAdminPermission(PermissionName::PROJECT_SELF);
    }

    /**
     * Apply the project scope to the Project model.
     */
    private function applyProjectScope(): void
    {
        Project::addGlobalScope('project_self_scope', function (Builder $builder) {
            $builder->where('admin_id', auth()->id());
        });
    }

    /**
     * Apply the factor scope to the Factor model.
     */
    private function applyFactorScope(): void
    {
        Factor::addGlobalScope('factor_self_scope', function (Builder $builder) {
            $builder->where('factors.admin_id', auth()->id());
        });
    }

    /**
     * Determine if the project scope should be skipped for the given route name.
     */
    private function shouldSkipProjectScope(?string $routeName): bool
    {
        $skippedRoutes = [
            'admin.factor.*',
            'admin.contract.factor.preview',
        ];

        foreach ($skippedRoutes as $pattern) {
            if (fnmatch($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }
}
