<?php

namespace Modules\Survey\app\Filters\Survey;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class RequiresAuthFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $requiresAuth = request()->input('requires_auth');

        if ($requiresAuth !== null && $requiresAuth !== '') {
            $query->where('surveys.requires_auth', (bool) $requiresAuth);
        }

        return $next($query);
    }
}
