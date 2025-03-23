<?php

namespace Modules\Survey\app\Filters\Survey;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $status = request()->input('status');

        if ($status !== null && $status !== '') {
            $query->where('surveys.is_active', (bool) $status);
        }

        return $next($query);
    }
}
