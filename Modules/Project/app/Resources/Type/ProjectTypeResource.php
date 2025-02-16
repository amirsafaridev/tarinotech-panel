<?php

namespace Modules\Project\app\Resources\Type;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Project\app\Models\ProjectType;

class ProjectTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this ProjectType */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['base_id'] = $this->base_id;
        $data['path'] = $this->path;
        $data['created_at'] = $this->created_at;

        return $data;
    }
}
