<?php

namespace Modules\Ticket\app\Filters\Ticket;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $statusId = request('status_id');

        if ($statusId) {
            $query->where('ticket_details.status_id', $statusId);
        }

        return $next($query);
    }
}
