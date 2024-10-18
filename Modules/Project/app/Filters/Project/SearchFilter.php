<?php

namespace Modules\Project\app\Filters\Project;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $search = request()->input('search');

        if ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('projects.title', 'like', '%'.$search.'%')
                    ->orWhere('projects.id', 'like', '%'.$search.'%')
                    ->orWhere('projects.domain', 'like', '%'.$search.'%');

            });
        }

        return $next($query);
    }
}
