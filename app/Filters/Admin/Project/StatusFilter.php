<?php

namespace App\Filters\Admin\Project;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $statusIds = request('status_id', []);
        if (! empty($statusIds)) {
            $query->whereIn('project_status_id', array_filter($statusIds, 'is_numeric'));
        }

        return $next($query);
    }
}
