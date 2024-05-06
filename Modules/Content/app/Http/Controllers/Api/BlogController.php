<?php

namespace Modules\Content\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Content\app\Filters\Blog\CategoryFilter;
use Modules\Content\app\Filters\Blog\SearchFilter;
use Modules\Content\app\Models\Blog;
use Modules\Content\app\Resources\Blog\BlogCollection;
use Modules\Content\app\Resources\Blog\BlogResource;

class BlogController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $blogs = Blog::query()
                ->where('is_publish', true)
                ->with('category')
                ->filter([
                    SearchFilter::class,
                    CategoryFilter::class,
                ])
                ->latest()
                ->paginate();

            $data = new BlogCollection($blogs);

            return $this->successResponse($data, 'blog list');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function single(string $slug)
    {
        try {
            $blog = Blog::query()
                ->where('is_publish', true)
                ->with('category')
                ->where('slug', $slug)
                ->firstOrFail();

            $data = new BlogResource($blog);

            return $this->successResponse($data, 'blog single');

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
