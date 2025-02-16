<?php

namespace Modules\Factor\app\Filters\Factor;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Modules\Contract\app\Enums\UserSignableStatus;

class ProjectIsSignUserFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $isSigned = request('project_is_signed_user');

        if (is_numeric($isSigned)) {
            $isSignedCondition = $isSigned == UserSignableStatus::Accepted;
            $query->where('projects.is_signed_user', $isSignedCondition);
        }

        return $next($query);
    }
}
