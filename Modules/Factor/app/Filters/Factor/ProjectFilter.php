<?php

namespace Modules\Factor\app\Filters\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ProjectFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $project = request('project');

        if ($project) {

            $query->where(function ($query) use ($project) {
                $query->where('projects.title', 'like', '%'.$project.'%')
                    ->orWhere('projects.domain', 'like', '%'.$project.'%');
            });

        }

        return $next($query);
    }
}
