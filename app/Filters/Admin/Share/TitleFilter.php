<?php

namespace App\Filters\Admin\Share;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class TitleFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $title = request('title');
        if ($title) {
            $query->where('title', 'like', '%'.$title.'%');
        }

        return $next($query);
    }
}
