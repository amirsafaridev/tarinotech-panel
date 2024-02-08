<?php

namespace Modules\Chat\app\Resources\Attachment;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Support\app\Models\ChatMessageAttachment;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this ChatMessageAttachment */
        $data['id'] = $this->id;
        $data['file_path'] = $this->file_path;
        $data['file_size'] = $this->file_size;
        $data['file_type'] = $this->file_type;

        return $data;
    }
}
