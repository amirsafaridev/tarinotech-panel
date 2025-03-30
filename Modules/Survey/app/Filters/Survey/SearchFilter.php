<?php

namespace Modules\Survey\app\Filters\Survey;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class SearchFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $search = request()->input('search');

        if ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('admins.first_name', 'like', '%'.$search.'%')
                    ->where('admins.last_name', 'like', '%'.$search.'%')
                    ->orWhere('surveys.description', 'like', '%'.$search.'%')
                    ->orWhere('surveys.title', 'like', '%'.$search.'%');

            });
        }

        return $next($query);
    }
}
