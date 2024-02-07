<?php

namespace Modules\Blog\app\Resources\Blog;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Resources\Category\CategoryResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this Blog */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['slug'] = $this->slug;
        $data['photo'] = $this->photo === null ? null : asset($this->photo);
        $data['body'] = $this->body;
        $data['is_publish'] = $this->is_publish;
        $data['meta_description'] = $this->meta_description;
        $data['meta_keywords'] = $this->meta_keywords;
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        if ($this->relationLoaded('category')) {
            $data['category'] = new CategoryResource($this->category);
        }

        return $data;
    }
}
