<?php

namespace Modules\Ticket\app\Filters\Ticket;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $search = request()->input('search');

        if ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('chats.title', 'like', '%'.$search.'%')
                    ->orWhere('ticket_details.id', 'like', '%'.$search.'%')
                    ->orWhere('users.first_name', 'like', '%'.$search.'%')
                    ->orWhere('users.last_name', 'like', '%'.$search.'%')
                    ->orWhere('users.email', 'like', '%'.$search.'%');
            });
        }

        return $next($query);
    }
}
