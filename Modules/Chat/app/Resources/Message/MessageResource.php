<?php

namespace Modules\Chat\app\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Chat\app\Resources\Attachment\AttachmentResource;
use Modules\Support\app\Models\ChatMessage;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this ChatMessage */
        $data['id'] = $this->id;
        $data['content'] = $this->content;
        $data['created_at'] = $this->created_at;

        if ($this->relationLoaded('user')) {
            $data['user'] = new UserMessageResource($this->user);
        }

        if ($this->relationLoaded('attachments')) {
            $data['attachments'] = AttachmentResource::collection($this->attachments);
        }

        return $data;
    }
}
