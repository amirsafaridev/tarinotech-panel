<?php

namespace App\Filters\Admin\Project;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class BaseIdFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $baseId = request('base_id');
        if (is_numeric($baseId)) {
            $query->where('project_base_id', $baseId);
        }

        return $next($query);
    }
}
