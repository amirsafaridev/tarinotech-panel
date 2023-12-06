<?php

namespace App\Filters\Admin\Admin;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class JobTitleFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $jobTitleId = request('job_title');

        if (is_numeric($jobTitleId)) {
            $query->where('job_title_id', $jobTitleId);
        }

        return $next($query);
    }
}
