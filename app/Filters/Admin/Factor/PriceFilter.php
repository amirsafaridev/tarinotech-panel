<?php

namespace App\Filters\Admin\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use function request;

class PriceFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $priceFrom = str_replace(',', '', request('price_from'));
        $priceTo = str_replace(',', '', request('price_to'));

        if ($priceFrom) {
            $query->where('final_price', '>=', $priceFrom);
        }

        if ($priceTo) {
            $query->where('final_price', '<=', $priceTo);
        }

        return $next($query);
    }
}
