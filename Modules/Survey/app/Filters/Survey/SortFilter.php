<?php

namespace Modules\Survey\app\Filters\Survey;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class SortFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $sort = request()->input('sort');

        if ($sort) {
            [$column, $direction] = explode('-', $sort);

            switch ($column) {
                case 'questions_count':
                    $query->orderBy('questions_count', $direction);
                    break;
                case 'responses_count':
                    $query->orderBy('responses_count', $direction);
                    break;
                case 'title':
                    $query->orderBy('surveys.title', $direction);
                    break;
                case 'start_date':
                    $query->orderBy('surveys.start_date', $direction);
                    break;
                case 'end_date':
                    $query->orderBy('surveys.end_date', $direction);
                    break;
                case 'created_at':
                    $query->orderBy('surveys.created_at', $direction);
                    break;
                default:
                    $query->orderBy('surveys.created_at', 'desc');
            }
        } else {
            $query->orderBy('surveys.created_at', 'desc');
        }

        return $next($query);
    }
}
