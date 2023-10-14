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
            $cleanDomain = cleanAlphaNumeric($domain);
            $query->where('domain', 'like', '%'.$cleanDomain.'%');
        }

        return $next($query);
    }
}
