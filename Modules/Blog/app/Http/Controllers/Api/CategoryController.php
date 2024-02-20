<?php

namespace Modules\Blog\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Resources\Category\CategoryResource;

class CategoryController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $categories = BlogCategory::withCount('blogs')
                ->where('id', '>', 1)
                ->orderByDesc('blogs_count')
                ->get();

            $data = CategoryResource::collection($categories);

            return $this->successResponse($data, 'category list');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
