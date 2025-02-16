<?php

namespace Modules\Chat\app\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\app\Models\Admin;
use Modules\Chat\app\Resources\Attachment\AttachmentResource;
use Modules\Support\app\Models\ChatBot;
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
        $data['created_at'] = $this->created_at->toJalali()->format(formatJalaliDateTime());
        $data['chat_id'] = $this->chat_id;

        if ($this->relationLoaded('user')) {
            $type = 'User';
            if ($this->user_type === Admin::class) {
                $type = 'Support';
            }
            if ($this->user_type === ChatBot::class) {
                $type = 'Bot';
            }
            $data['user'] = new UserMessageResource($this->user, $type);
        }

        if ($this->relationLoaded('attachments')) {
            $data['attachments'] = AttachmentResource::collection($this->attachments);
        }

        if ($this->relationLoaded('replay')) {
            $data['replay'] = new $this($this->replay);
        }

        return $data;
    }
}
