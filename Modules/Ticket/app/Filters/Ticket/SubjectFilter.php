<?php

namespace Modules\Ticket\app\Filters\Ticket;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class SubjectFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $subjectId = request('subject_id');

        if ($subjectId) {
            $query->where('ticket_details.subject_id', $subjectId);
        }

        return $next($query);
    }
}
