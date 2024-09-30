<?php

namespace Modules\Project\app\Filters;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Modules\Contract\app\Enums\UserSignableStatus;

class IsUserSignFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $isSigned = request('is_signed_user');

        if (is_numeric($isSigned)) {
            $isSignedCondition = $isSigned == UserSignableStatus::Accepted;
            $query->where('is_signed_user', $isSignedCondition);
        }

        return $next($query);
    }
}
