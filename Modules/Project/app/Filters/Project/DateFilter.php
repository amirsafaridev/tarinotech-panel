<?php

namespace Modules\Project\app\Filters\Project;

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
        $dateColumn = request('date_column');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $validator = Validator::make(request()->all(), [
            'date_column' => 'in:projects.created_at,projects.paid_at',
            'from_date' => 'nullable|jdate',
            'to_date' => 'nullable|jdate|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
            return $next($query);
        }

        if ($dateColumn) {
            if ($fromDate) {
                $gregorianFromDate = Helper::toGregorian($fromDate);
                $query->where($dateColumn, '>=', Carbon::parse($gregorianFromDate)->startOfDay());
            }

            if ($toDate) {
                $gregorianToDate = Helper::toGregorian($toDate);
                $query->where($dateColumn, '<=', Carbon::parse($gregorianToDate)->endOfDay());
            }
        }

        return $next($query);
    }
}
