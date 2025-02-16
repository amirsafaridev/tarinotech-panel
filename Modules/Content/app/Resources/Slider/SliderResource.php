<?php

namespace Modules\Content\app\Resources\Slider;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Content\app\Models\Slider;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this Slider */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['link'] = $this->link;
        $data['description'] = $this->description;
        $data['photo'] = asset($this->photo);

        return $data;
    }
}
