<?php

namespace Modules\Survey\app\Filters\Survey;

use App\Filters\FilterBase;
use App\Helpers\Helper;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class DateFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $fromDate = request()->input('from_date');
        $toDate = request()->input('to_date');
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');

        // Filter by creation date range
        if ($fromDate) {
            $query->whereDate('surveys.created_at', '>=', Helper::toGregorian($fromDate));
        }

        if ($toDate) {
            $query->whereDate('surveys.created_at', '<=', Helper::toGregorian($toDate));
        }

        // Filter by survey start date
        if ($startDate) {
            $query->whereDate('surveys.start_date', '=', Helper::toGregorian($startDate));
        }

        // Filter by survey end date
        if ($endDate) {
            $query->whereDate('surveys.end_date', '=', Helper::toGregorian($endDate));
        }

        return $next($query);
    }
}
