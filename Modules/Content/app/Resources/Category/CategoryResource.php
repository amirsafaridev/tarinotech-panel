<?php

namespace Modules\Content\app\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Content\app\Models\BlogCategory;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this BlogCategory */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['slug'] = $this->slug;

        return $data;
    }
}
