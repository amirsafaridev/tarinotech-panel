<?php

namespace Modules\Project\app\Filters\Web;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class PackageFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $packageId = request('package_id');
        if (! empty($packageId) && is_numeric($packageId)) {
            $query->where('project_webs.package_id', $packageId);
        }

        return $next($query);
    }
}
