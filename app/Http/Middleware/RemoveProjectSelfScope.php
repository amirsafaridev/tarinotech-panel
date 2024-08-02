<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Project\app\Models\Project;
use Symfony\Component\HttpFoundation\Response;

class RemoveProjectSelfScope
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {

        Project::withoutGlobalScope('project_self_scope');

        return $next($request);
    }
}
