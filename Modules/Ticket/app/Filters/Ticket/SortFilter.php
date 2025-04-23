<?php

namespace Modules\Ticket\app\Filters\Ticket;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class SortFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $allowedSortKeys = [
            'ticket_details.id',
            'ticket_details.last_response_at',
            'ticket_details.created_at',
            'ticket_priorities.level',
        ];
        $allowedSortOrders = ['desc', 'asc'];

        $sortParam = request('sort');

        if (empty($sortParam) || ! str($sortParam)->contains('-')) {
            $query->orderByDesc('ticket_details.id');

            return $next($query);
        }

        [$sortKey, $sortOrder] = explode('-', $sortParam);

        if (! in_array($sortKey, $allowedSortKeys) || ! in_array($sortOrder, $allowedSortOrders)) {
            $query->orderByDesc('ticket_details.id');

            return $next($query);
        }

        $query->orderBy($sortKey, $sortOrder);

        return $next($query);
    }
}
