<?php

namespace Modules\Factor\app\Filters\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Modules\Contract\app\Enums\SignableStatus;

class ProjectIsSignFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $isSigned = request('project_is_signed');

        if (is_numeric($isSigned)) {
            $isSignedCondition = $isSigned == SignableStatus::Signed;
            $query->where('projects.is_signed', $isSignedCondition);
        }

        return $next($query);
    }
}
