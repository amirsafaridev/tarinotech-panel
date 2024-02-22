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
        $data['file_name'] = $this->file_name;
        $data['file_path'] = $this->file_path;
        $data['file_size'] = formatFileSize($this->file_size);
        $data['file_type'] = $this->file_type;
        $data['src'] = route('stream.read', ['path' => str_replace('/', '|', $this->file_path)]);
        $data['file_extension'] = $this->file_extension;

        return $data;
    }
}
