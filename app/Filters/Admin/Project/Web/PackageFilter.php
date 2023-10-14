<?php

namespace App\Filters\Admin\Project\Web;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use function request;

class PackageFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $packageIds = request('package_id', []);
        if (! empty($packageIds)) {
            $query->whereIn('package_id', array_filter($packageIds, 'is_numeric'));
        }

        return $next($query);
    }
}
