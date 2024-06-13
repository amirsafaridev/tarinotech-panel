<?php

namespace Modules\Chat\app\Resources\Chat;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Project\app\Resources\Project\ProjectResource;
use Modules\Support\app\Models\Chat;
use Modules\User\app\Models\User;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this Chat */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['photo'] = $this->logo === null ? null : asset($this->logo);
        $data['type'] = $this->type;
        $data['type_title'] = ChatType::getDescription($this->type);
        $data['status'] = $this->status;
        $data['status_title'] = ChatStatus::getDescription($this->status);
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        $data['update_ago'] = $this->updated_at->ago();
        $data['selected'] = false;
        if ($this->relationLoaded('project')) {
            $data['project'] = new ProjectResource($this->project);
        }
        if ($this->relationLoaded('users')) {
            $user = auth()->user();
            $data['user'] = new ChatUserResource(
                $this->users
                    ->where('user_id', $user->id)
                    ->where('user_type', User::class)
                    ->first()
            );
        }

        return $data;
    }
}
