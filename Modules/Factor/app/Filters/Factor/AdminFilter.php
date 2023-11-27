<?php

namespace Modules\Factor\app\Filters\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class AdminFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $admin = request('admin');

        if ($admin) {
            $query->whereHas('admin', function (Builder $query) use ($admin) {
                $query
                    ->where('first_name', 'like', '%'.$admin.'%')
                    ->orWhere('last_name', 'like', '%'.$admin.'%')
                    ->orWhere('email', 'like', '%'.$admin.'%');

            });
        }

        return $next($query);
    }
}
