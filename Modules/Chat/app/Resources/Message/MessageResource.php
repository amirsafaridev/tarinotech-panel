<?php

namespace Modules\Chat\app\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Blog\app\Models\BlogCategory;

class MessageResource extends JsonResource
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
