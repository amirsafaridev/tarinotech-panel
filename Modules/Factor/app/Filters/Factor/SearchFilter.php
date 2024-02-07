<?php

namespace Modules\Factor\app\Filters\Factor;

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
                    ->where('title', 'like', '%'.$search.'%')
                    ->orWhere('identify', 'like', '%'.$search.'%');

            });
        }

        return $next($query);
    }
}
