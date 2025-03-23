<?php

namespace Modules\Survey\app\Filters\Response;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class SortFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $allowedSortKeys = ['survey_responses.created_at', 'answers_count'];
        $allowedSortOrders = ['desc', 'asc'];

        $sortParam = request('sort');

        if (empty($sortParam) || ! str($sortParam)->contains('-')) {
            $query->orderByDesc('id');

            return $next($query);
        }

        [$sortKey, $sortOrder] = explode('-', $sortParam);

        if (! in_array($sortKey, $allowedSortKeys) || ! in_array($sortOrder, $allowedSortOrders)) {
            $query->orderByDesc('survey_responses.id');

            return $next($query);
        }

        $query->orderBy($sortKey, $sortOrder);

        return $next($query);

    }
}
