<?php

namespace Modules\Factor\app\Resources\Factor;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Factor\app\Models\TransactionCategory;

class FactorItemCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this TransactionCategory */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['created_at'] = $this->created_at->toJalali()->format(formatJalaliDateTime());
        $data['updated_at'] = $this->updated_at;

        return $data;
    }
}
