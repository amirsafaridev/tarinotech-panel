<?php

namespace Modules\Contract\app\Resources\UserSignable;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Contract\app\Enums\UserSignableStatus;
use Modules\Contract\app\Models\UserSignable;
use Modules\Project\app\Resources\Project\ProjectResource;

class SignableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this UserSignable */
        $data['id'] = $this->id;
        $data['target_type'] = $this->target_type;
        $data['target_id'] = $this->target_id;
        $data['user_id'] = $this->user_id;
        $data['status'] = $this->status;
        $data['status_title'] = UserSignableStatus::getDescription($this->status);
        $data['created_at'] = $this->created_at->toJalali()->format(formatJalaliDateTime());
        $data['updated_at'] = $this->updated_at->toJalali()->format(formatJalaliDateTime());

        if ($this->relationLoaded('target')) {
            $data['project'] = new ProjectResource($this->target->project);
        }

        return $data;
    }
}
