<?php

namespace Modules\Project\app\Filters\Seo;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class PackageFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $packageId = request('package_id');
        if (! empty($packageIds) && is_numeric($packageId)) {
            $query->where('project_seo.package_id', $packageId);
        }

        return $next($query);
    }
}
