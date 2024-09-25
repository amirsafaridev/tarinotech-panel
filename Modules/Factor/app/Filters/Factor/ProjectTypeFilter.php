<?php

namespace Modules\Factor\app\Filters\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ProjectTypeFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $projectType = request('project_type');

        if ($projectType && is_numeric($projectType)) {
            $query->whereHas('project', function (Builder $query) use ($projectType) {
                $query->where('base_id', $projectType);
            });
        }

        return $next($query);
    }
}
