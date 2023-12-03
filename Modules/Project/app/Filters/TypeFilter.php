<?php

namespace Modules\Project\app\Filters;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class TypeFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $typeId = request('type');
        if (is_numeric($typeId)) {
            $query->where('type_id', $typeId);
        }

        return $next($query);
    }
}
