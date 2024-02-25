<?php

namespace Modules\Chat\app\Resources\Chat;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Support\app\Models\ChatUser;

class ChatUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this ChatUser */
        $data['id'] = $this->id;
        $data['unread'] = $this->unread;
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;

        return $data;
    }
}
