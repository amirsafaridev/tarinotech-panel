<?php

namespace Modules\Survey\app\Filters\Response;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class SearchFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $search = request()->input('search');

        if ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('survey_responses.respondent_email', 'like', '%'.$search.'%')
                    ->orWhere('survey_responses.respondent_name', 'like', '%'.$search.'%');

            });
        }

        return $next($query);
    }
}
