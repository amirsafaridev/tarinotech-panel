<?php

namespace Modules\Factor\app\Filters\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class GatewayFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $gateway = request('gateway');

        if (is_numeric($gateway)) {
            $query->where('gateway', $gateway);
        }

        return $next($query);
    }
}
