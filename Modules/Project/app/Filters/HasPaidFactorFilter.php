<?php

namespace Modules\Project\app\Filters;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Modules\Factor\app\Enums\FactorStatus;

class HasPaidFactorFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $hasPaidFactor = request('has_paid_factor');

        if (is_numeric($hasPaidFactor) && $hasPaidFactor == 1) {
            $paidStatuses = [
                FactorStatus::Paid,
                FactorStatus::PaidManual,
                FactorStatus::PaidWithCheque
            ];
            
            $query->whereHas('factors', function ($factorQuery) use ($paidStatuses) {
                $factorQuery->whereIn('status', $paidStatuses);
            });
        }

        return $next($query);
    }
} 