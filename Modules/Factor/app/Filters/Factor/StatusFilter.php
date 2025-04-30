<?php

namespace Modules\Factor\app\Filters\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $status = request('status');

        if ($status) {
            if (is_array($status)) {
                $query->whereIn('factors.status', $status);
            } else {
                $query->where('factors.status', $status);
            }
        }

        return $next($query);
    }
}
