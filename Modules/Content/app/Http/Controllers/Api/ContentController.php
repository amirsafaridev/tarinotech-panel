<?php

namespace Modules\Content\app\Http\Controllers\Api;

use App\Enums\Database\Slider\SliderStatus;
use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Exception;
use Modules\Content\app\Models\Blog;
use Modules\Content\app\Models\Slider;
use Modules\Content\app\Resources\Blog\BlogResource;
use Modules\Content\app\Resources\Slider\SliderResource;

class ContentController extends Controller
{
    use HasApiResponse;

    public function index()
    {
        try {
            $sliders = Slider::query()
                ->where('status', SliderStatus::Active)
                ->where('published_at', '<=', now())
                ->where('archived_at', '>=', now())
                ->latest()
                ->get();

            $blogs = Blog::query()
                ->where('is_publish', true)
                ->with('category')
                ->limit(5)
                ->latest()
                ->get();

            $data['blogs'] = BlogResource::collection($blogs);

            $data['sliders'] = SliderResource::collection($sliders);

            return $this->successResponse($data, 'main');
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
