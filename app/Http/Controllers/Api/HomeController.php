<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Resources\Blog\BlogResource;

class HomeController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $blogs = Blog::query()
                ->where('is_publish', true)
                ->with('category')
                ->latest()
                ->limit(10)
                ->get();

            return $this->successResponse([
                'blogs' => BlogResource::collection($blogs),
            ], 'home data');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
