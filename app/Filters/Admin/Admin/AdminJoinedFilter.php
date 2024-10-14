<?php

namespace App\Filters\Admin\Admin;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class AdminJoinedFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $admin = request('admin');

        if ($admin) {
            $query->where(function ($query) use ($admin) {
                $query->where('admins.first_name', 'like', '%'.$admin.'%')
                    ->orWhere('admins.last_name', 'like', '%'.$admin.'%')
                    ->orWhere('admins.email', 'like', '%'.$admin.'%');
            });
        }

        return $next($query);
    }
}
