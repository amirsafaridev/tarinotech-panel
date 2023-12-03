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
            $query->whereHas('project', function (Builder $query) use ($project) {
                $query->where('title', 'like', '%'.$project.'%')
                    ->orWhere('domain', 'like', '%'.$project.'%');

            });
        }

        return $next($query);
    }
}
