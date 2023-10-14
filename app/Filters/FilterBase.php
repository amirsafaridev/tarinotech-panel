<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

abstract class FilterBase
{
    abstract public function handle(Builder $query, Closure $next);
}
