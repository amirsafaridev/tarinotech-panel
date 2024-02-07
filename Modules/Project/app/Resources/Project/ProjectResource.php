<?php

namespace Modules\Project\app\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Resources\Type\ProjectTypeResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this Project */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['domain'] = $this->domain;
        $data['type_id'] = $this->type_id;
        $data['base_id'] = $this->base_id;
        $data['status_id'] = $this->status_id;
        $data['created_at'] = $this->created_at;

        if ($this->relationLoaded('type')) {
            $data['type'] = new ProjectTypeResource($this->type);
        }

        return $data;
    }
}
