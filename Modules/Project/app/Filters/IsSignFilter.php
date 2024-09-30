<?php

namespace Modules\Project\app\Filters;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Modules\Contract\app\Enums\SignableStatus;

class IsSignFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $isSigned = request('is_signed');

        if (is_numeric($isSigned)) {
            $isSignedCondition = $isSigned == SignableStatus::Signed;
            $query->where('is_signed', $isSignedCondition);
        }

        return $next($query);
    }
}
