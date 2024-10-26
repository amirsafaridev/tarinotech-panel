<?php

namespace Modules\Factor\app\Filters\Factor;

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
                    ->where('factors.title', 'like', '%'.$search.'%')
                    ->orWhere('factors.id', 'like', '%'.$search.'%');

            });
        }

        return $next($query);
    }
}
