<?php

namespace Modules\Factor\app\Filters\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class PriceFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $from = str_replace(',', '', request('price_from'));
        $to = str_replace(',', '', request('price_to'));

        if ($from) {
            $query->where('final_price', '>=', $from);
        }

        if ($to) {
            $query->where('final_price', '<=', $to);
        }

        return $next($query);
    }
}
