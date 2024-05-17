<?php

namespace Modules\Factor\app\Resources\Factor;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Resources\Project\ProjectResource;

class FactorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var $this Factor */
        $data['id'] = $this->id;
        $data['title'] = $this->title;
        $data['identify'] = $this->identify;
        $data['transaction_id'] = $this->transaction_id;
        $data['project_id'] = $this->project_id;
        $data['final_price'] = $this->final_price;
        $data['payment_link'] = route('factor.factor.index', $this->identify);
        $data['status'] = $this->status;
        $data['status_title'] = FactorStatus::getDescription($this->status);
        $data['is_official'] = $this->is_official;
        $data['expired_at'] = $this->expired_at ? $this->expired_at->toJalali()->format(formatJalaliDateTime()) : '';
        $data['paid_at'] = $this->paid_at ? $this->paid_at->toJalali()->format(formatJalaliDateTime()) : '';
        $data['created_at'] = $this->created_at->toJalali()->format(formatJalaliDateTime());
        $data['updated_at'] = $this->updated_at->toJalali()->format(formatJalaliDateTime());
        if ($this->relationLoaded('project')) {
            $data['project'] = new ProjectResource($this->project);
        }

        if ($this->relationLoaded('items')) {
            $data['items'] = FactorItemResource::collection($this->items);
        }

        return $data;
    }
}
