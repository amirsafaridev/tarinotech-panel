<?php

namespace App\Filters\Admin\User;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class UserSearchFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $user = request('user');
        if ($user) {
            $query->where('first_name', 'like', '%'.$user.'%');
            $query->orWhere('last_name', 'like', '%'.$user.'%');
            $query->orWhere('mobile', 'like', '%'.$user.'%');
            $query->orWhere('email', 'like', '%'.$user.'%');
        }

        return $next($query);
    }
}
