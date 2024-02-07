<?php

namespace Modules\Blog\app\Filter\Blog;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

use function request;

class CategoryFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $category = request()->input('category');

        if (! is_numeric($category)) {
            return $next($query);
        }

        $query
            ->where('blog_category_id', $category);

        return $next($query);
    }
}
