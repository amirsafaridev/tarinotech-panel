<?php

namespace App\Http\Middleware;

use App\Enums\Database\Role\PermissionName;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Project\app\Models\Project;
use Symfony\Component\HttpFoundation\Response;

class AdminApplyScope
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (hasAdminPermission(PermissionName::PROJECT_SELF)) {
            Project::addGlobalScope('active', function (Builder $builder) {
                $builder->where('admin_id', auth()->id());
            });
        }

        return $next($request);
    }
}
