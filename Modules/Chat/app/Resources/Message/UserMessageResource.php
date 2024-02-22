<?php

namespace Modules\Chat\app\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\app\Models\Admin;
use Modules\User\app\Models\User;

class UserMessageResource extends JsonResource
{
    private string $type;

    public function __construct($resource, string $type)
    {
        $this->type = $type;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this User|Admin */
        $data['id'] = $this->id;
        $data['type'] = $this->type;
        $data['first_name'] = $this->first_name;
        $data['last_name'] = $this->last_name;
        $data['photo'] = $this->avatar ? asset($this->avatar) : null;

        return $data;
    }
}
