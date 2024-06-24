<?php

namespace Modules\Chat\app\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Admin\app\Models\Admin;
use Modules\Support\app\Models\ChatBot;
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
        /** @var $this User|Admin|ChatBot */
        $data['id'] = $this->id;
        $data['type'] = $this->type;
        if ($this->type === 'Bot') {
            $data['first_name'] = 'تارینوتک';
            $data['last_name'] = '';
            $data['action'] = $this->action;
            $data['photo'] = asset('default/chat.png');
        } else {
            $data['first_name'] = $this->first_name;
            $data['last_name'] = $this->last_name;
            $data['photo'] = $this->avatar ? asset($this->avatar) : null;
        }

        return $data;
    }
}
