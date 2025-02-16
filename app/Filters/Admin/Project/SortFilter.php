<?php

namespace App\Filters\Admin\Project;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class SortFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $allowedSortKeys = ['id', 'title', 'price', 'final_price'];
        $allowedSortOrders = ['desc', 'asc'];

        $sortParam = request('sort');

        if (empty($sortParam) || ! str($sortParam)->contains('-')) {
            $query->orderByDesc('id');

            return $next($query);
        }

        [$sortKey, $sortOrder] = explode('-', $sortParam);

        if (! in_array($sortKey, $allowedSortKeys) || ! in_array($sortOrder, $allowedSortOrders)) {
            $query->orderByDesc('id');

            return $next($query);
        }

        $query->orderBy($sortKey, $sortOrder);

        return $next($query);

    }
}
