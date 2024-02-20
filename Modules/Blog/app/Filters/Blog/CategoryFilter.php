<?php

namespace Modules\Blog\app\Filters\Blog;

use App\Filters\FilterBase;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends FilterBase
{
    public function handle(Builder $query, Closure $next)
    {
        $category = request()->input('category');

        if (! is_numeric($category) || $category < 1) {
            return $next($query);
        }

        $query
            ->where('blog_category_id', $category);

        return $next($query);
    }
}
