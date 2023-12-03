<?php

namespace App\Filters\Admin\Package;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class PackageID extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $id = request('package_id');
        if (is_numeric($id)) {
            $query->where('package_id', $id);
        }

        return $next($query);
    }
}
