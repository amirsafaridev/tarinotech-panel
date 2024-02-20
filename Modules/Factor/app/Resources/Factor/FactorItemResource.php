<?php

namespace Modules\Factor\app\Resources\Factor;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Factor\app\Models\FactorItem;

class FactorItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this FactorItem */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['transaction_category_id'] = $this->transaction_category_id;
        $data['price'] = $this->price;
        $data['tax_rate'] = $this->tax_rate;
        $data['tax_amount'] = $this->tax_amount;
        $data['discount'] = $this->discount;
        $data['final_price'] = $this->final_price;
        $data['created_at'] = $this->created_at->toJalali()->format(formatJalaliDateTime());
        $data['updated_at'] = $this->updated_at;
        if ($this->relationLoaded('category')) {
            $data['category'] = new FactorItemCategoryResource($this->category);
        }

        return $data;
    }
}
