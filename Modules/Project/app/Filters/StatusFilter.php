<?php

namespace Modules\Project\app\Filters;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $status = request('status');
        if (is_numeric($status)) {
            $query->where('projects.status_id', $status);
        }

        return $next($query);
    }
}
