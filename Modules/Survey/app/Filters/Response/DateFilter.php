<?php

namespace Modules\Survey\app\Filters\Response;

use App\Filters\FilterBase;
use App\Helpers\Helper;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class DateFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $validator = Validator::make(request()->all(), [
            'from_date' => 'nullable|jdate',
            'to_date' => 'nullable|jdate|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
            return $next($query);
        }

        if ($fromDate) {
            $gregorianFromDate = Helper::toGregorian($fromDate);
            $query->where('survey_responses.created_at', '>=', Carbon::parse($gregorianFromDate)->startOfDay());
        }

        if ($toDate) {
            $gregorianToDate = Helper::toGregorian($toDate);
            $query->where('survey_responses.created_at', '<=', Carbon::parse($gregorianToDate)->endOfDay());
        }

        return $next($query);
    }
}
