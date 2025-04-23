<?php

namespace App\Filters\Admin\Share;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class IDFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $id = request('id');
        if (is_numeric($id)) {
            $query->where('id', $id);
        }

        return $next($query);
    }
}
