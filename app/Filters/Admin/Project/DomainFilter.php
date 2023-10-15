<?php

namespace App\Filters\Admin\Project;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class DomainFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $domain = request('domain');
        if ($domain) {
            $query->where('domain', 'like', '%'.$domain.'%');
        }

        return $next($query);
    }
}
