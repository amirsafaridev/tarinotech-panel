<?php

namespace Modules\Chat\app\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\app\Models\Admin;
use Modules\User\app\Models\User;

class UserMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this User|Admin */
        $data['first_name'] = $this->first_name;
        $data['last_name'] = $this->last_name;
        $data['photo'] = $this->avatar ? asset($this->avatar) : null;

        return $data;
    }
}
