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

            $query->whereHas('project', function (Builder $query) use ($isSignedCondition) {
                $query->where('is_signed', $isSignedCondition);
            });
        }

        return $next($query);
    }
}
