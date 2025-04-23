<?php

namespace Modules\Ticket\app\Filters\Ticket;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class PriorityFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $priorityId = request('priority_id');

        if ($priorityId) {
            $query->where('ticket_details.priority_id', $priorityId);
        }

        return $next($query);
    }
}
