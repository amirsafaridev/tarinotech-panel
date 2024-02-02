<?php

namespace Modules\Auth\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\app\Models\User;

use function asset;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var User $this */
        $res['first_name'] = $this->first_name;
        $res['last_name'] = $this->last_name;
        $res['en_first_name'] = $this->en_first_name;
        $res['en_last_name'] = $this->en_last_name;
        $res['father_name'] = $this->father_name;
        $res['national_photo'] = $this->national_photo;
        $res['national_id'] = $this->national_id;
        $res['document_id'] = $this->document_id;
        $res['avatar'] = $this->avatar ? asset($this->avatar) : null;
        $res['dob'] = $this->dob;
        $res['person_type'] = $this->person_type;
        $res['official_bill'] = $this->official_bill;
        $res['email'] = $this->email;
        $res['knowledge_way'] = $this->knowledge_way;
        $res['mobile'] = $this->mobile;
        $res['user_type'] = $this->user_type;

        return $res;
    }
}
