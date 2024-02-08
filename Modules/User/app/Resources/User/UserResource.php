<?php

namespace Modules\User\app\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Models\User;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var User $this */
        $res['id'] = $this->id;
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
        $res['person_type_title'] = PersonType::getDescription($this->person_type);

        $res['official_bill'] = $this->official_bill;
        $res['email'] = $this->email;
        $res['knowledge_way'] = $this->knowledge_way;
        $res['mobile'] = $this->mobile;

        $res['user_type'] = $this->user_type;
        $res['user_type_title'] = UserType::getDescription($this->user_type);

        return $res;
    }
}
